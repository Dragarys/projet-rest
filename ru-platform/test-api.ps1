$ErrorActionPreference = 'Stop'

$baseUrl = 'http://127.0.0.1:8000'
$suffix = (Get-Date).ToString('yyyyMMddHHmmss')

Write-Host 'Login...' -ForegroundColor Cyan
$login = Invoke-RestMethod -Method Post -Uri "$baseUrl/api/auth/login" -ContentType 'application/json' -Body (@{
    email = 'admin@ru.local'
    password = 'password'
} | ConvertTo-Json)

$token = $login.token
if (-not $token) {
    throw 'Login failed: no token returned.'
}

$headers = @{
    Authorization = "Bearer $token"
    'Content-Type' = 'application/json'
}

Write-Host 'Create ingredient...' -ForegroundColor Cyan
$ingredient = Invoke-RestMethod -Method Post -Uri "$baseUrl/api/ingredients" -Headers $headers -Body (@{
    name = "Base Ingredient $suffix"
    unit = 'kg'
} | ConvertTo-Json)

Write-Host 'Add stock...' -ForegroundColor Cyan
Invoke-RestMethod -Method Post -Uri "$baseUrl/api/stock/movements" -Headers $headers -Body (@{
    ingredient_id = $ingredient.id
    delta_qty = 200
    reason = 'Initial stock for test'
} | ConvertTo-Json) | Out-Null

Write-Host 'Create dishes...' -ForegroundColor Cyan
$dishDefs = @(
    @{ name = "Pesto Pasta $suffix"; price = 7.5; image_url = "/images/dishes/pesto_pasta.jpg" },
    @{ name = "Salade Fraiche $suffix"; price = 5.2; image_url = "/images/dishes/salad_bowl.jpg" },
    @{ name = "Pasta Classique $suffix"; price = 6.8; image_url = "/images/dishes/plate_pasta.jpg" },
    @{ name = "Riz Saute $suffix"; price = 6.0; image_url = "/images/dishes/fried_rice.jpg" },
    @{ name = "Dessert Gourmand $suffix"; price = 4.5; image_url = "/images/dishes/gourmet_dessert.jpg" },
    @{ name = "Dessert Myrtille $suffix"; price = 4.2; image_url = "/images/dishes/blueberry_dessert.jpg" }
)

$dishes = @()
foreach ($d in $dishDefs) {
    $dish = Invoke-RestMethod -Method Post -Uri "$baseUrl/api/dishes" -Headers $headers -Body (@{
        name = $d.name
        description = 'Plat test'
        price = $d.price
        image_url = $d.image_url
        ingredients = @(
            @{ id = $ingredient.id; qty = 0.5 }
        )
    } | ConvertTo-Json)
    $dishes += $dish
}

Write-Host 'Create menu...' -ForegroundColor Cyan
$menuDate = (Get-Date).AddDays((Get-Random -Minimum 0 -Maximum 30)).ToString('yyyy-MM-dd')
$service = if ((Get-Random -Minimum 0 -Maximum 2) -eq 0) { 'lunch' } else { 'dinner' }

$menuItems = @()
foreach ($d in $dishes) {
    $menuItems += @{ dish_id = $d.id; quantity_limit = 50 }
}

$menu = Invoke-RestMethod -Method Post -Uri "$baseUrl/api/menus" -Headers $headers -Body (@{
    menu_date = $menuDate
    service = $service
    items = $menuItems
} | ConvertTo-Json)

Write-Host 'Create order...' -ForegroundColor Cyan
$order = Invoke-RestMethod -Method Post -Uri "$baseUrl/api/orders" -Headers $headers -Body (@{
    menu_id = $menu.id
    items = @(
        @{ dish_id = $dishes[0].id; quantity = 2 }
    )
} | ConvertTo-Json)

if (-not $order.id) {
    Write-Host 'Order response was missing id:' -ForegroundColor Yellow
    $order | ConvertTo-Json -Depth 6
    throw 'Order creation failed: no id returned.'
}

Write-Host 'Pay order...' -ForegroundColor Cyan
try {
    Invoke-RestMethod -Method Post -Uri "$baseUrl/api/orders/$($order.id)/pay" -Headers $headers | Out-Null
}
catch {
    Write-Host "Pay failed for order id $($order.id)." -ForegroundColor Red
    throw
}

Write-Host 'Done.' -ForegroundColor Green
