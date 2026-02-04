#!/usr/bin/env pwsh
# Generate demo data for RU Platform
Set-Location -Path (Split-Path -Parent $MyInvocation.MyCommand.Path)
Set-Location -Path ..\

Write-Host "Running migrations (fresh) and seeding demo data..." -ForegroundColor Green
# Prefer Sail if available
if (Test-Path './vendor/bin/sail') {
    ./vendor/bin/sail artisan migrate:fresh --seed
} else {
    php artisan migrate:fresh --seed
}

Write-Host "Demo data created." -ForegroundColor Green
Write-Host "Admin user: admin@ru.local / password" -ForegroundColor Yellow
Write-Host "Run: php artisan serve or use Sail to run the app." -ForegroundColor Cyan
