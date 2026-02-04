Write-Host 'Polling origin/ci/trigger-phpstan-baseline for phpstan-baseline.neon (up to 12 tries, 20s interval)...'
$found = $false
$i = 0
while ($i -lt 12) {
    git fetch origin
    $exists = git ls-tree -r origin/ci/trigger-phpstan-baseline --name-only | Select-String -Quiet 'phpstan-baseline.neon'
    if ($exists) {
        Write-Host "Found baseline on remote (try $($i+1))"
        $found = $true
        break
    } else {
        Write-Host "Try $($i+1): not found, sleeping 20s"
        Start-Sleep -Seconds 20
    }
    $i = $i + 1
}
if (-not $found) {
    Write-Host 'Baseline not found after polling period'
    exit 1
}
exit 0
