#!/usr/bin/env pwsh
# Run PHPStan locally using Docker images (no Composer/PHP installed locally required)
# Usage: ./scripts/run_phpstan.ps1

param(
    [switch]$GenerateBaseline
)

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Error "Docker is required to run this script. Please install Docker Desktop."
    exit 1
}

Write-Host "Installing PHP dependencies using the official Composer image..." -ForegroundColor Green
docker run --rm -v "${PWD}:/app" -w /app composer:2.5 install --no-progress --no-interaction
if ($LASTEXITCODE -ne 0) { Write-Error "Composer install failed"; exit 2 }

Write-Host "Running PHPStan analysis..." -ForegroundColor Green
# Run PHPStan inside a PHP container so required extensions are available
$cmd = "vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G"
if ($GenerateBaseline) { $cmd += ' --generate-baseline=phpstan-baseline.neon' }

docker run --rm -v "${PWD}:/app" -w /app php:8.2-cli bash -lc "php -v; $cmd"
$exit = $LASTEXITCODE
if ($exit -ne 0) {
    Write-Error "PHPStan reported issues (exit code $exit)"
    if ($GenerateBaseline) { Write-Host "Baseline generation requested - check phpstan-baseline.neon" -ForegroundColor Yellow }
}
exit $exit
