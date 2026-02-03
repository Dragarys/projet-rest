param(
    [switch]$SkipTests,
    [switch]$SkipCoverage,
    [switch]$SkipServe,
    [switch]$OnlyServe,
    [switch]$OnlyTests,
    [switch]$OnlyCoverage,
    [switch]$Help
)

$ErrorActionPreference = 'Stop'

if ($Help) {
    Write-Host 'Usage:' -ForegroundColor Cyan
    Write-Host '  .\\run-all.ps1                # tests + coverage + serve'
    Write-Host '  .\\run-all.ps1 -SkipTests'
    Write-Host '  .\\run-all.ps1 -SkipCoverage'
    Write-Host '  .\\run-all.ps1 -SkipServe'
    Write-Host '  .\\run-all.ps1 -OnlyServe'
    Write-Host '  .\\run-all.ps1 -OnlyTests'
    Write-Host '  .\\run-all.ps1 -OnlyCoverage'
    return
}

if ($OnlyServe) {
    $SkipTests = $true
    $SkipCoverage = $true
    $SkipServe = $false
}

if ($OnlyTests) {
    $SkipTests = $false
    $SkipCoverage = $true
    $SkipServe = $true
}

if ($OnlyCoverage) {
    $SkipTests = $true
    $SkipCoverage = $false
    $SkipServe = $true
}

if (-not $SkipTests) {
    Write-Host 'Running tests...' -ForegroundColor Cyan
    php artisan test
}

if (-not $SkipCoverage) {
    Write-Host 'Generating coverage HTML...' -ForegroundColor Cyan
    php artisan test --coverage-html storage\coverage
}

if (-not $SkipServe) {
    Write-Host 'Starting server at http://127.0.0.1:8000' -ForegroundColor Green
    php artisan serve
}
