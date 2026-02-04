# PHPStan — Static Analysis Guide

This project runs PHPStan in CI. If you don't have Composer/PHP locally you can still run PHPStan using Docker.

Quick commands (from project root):

- Run PHPStan using the helper script (Windows PowerShell):

  ./scripts/run_phpstan.ps1

- Create a baseline (to ignore existing issues temporarily):

  ./scripts/run_phpstan.ps1 -GenerateBaseline

What happens in CI

- CI runs `vendor/bin/phpstan analyse` and will fail if issues are found.
- On first failure CI will try to generate `phpstan-baseline.neon` and commit it back to the repository automatically. That baseline will be used to keep CI green while issues are fixed gradually.

How to triage issues

1. Run `./scripts/run_phpstan.ps1` locally to reproduce issues.
2. Inspect the output and open issues for each group of findings.
3. Fix high-confidence problems (undefined variables, missing types, wrong return types) and add tests.
4. If a finding is a false-positive, add it to `phpstan-baseline.neon` or file a bug report.

Tips

- Set `phpstan.neon` level higher or lower to adjust strictness.
- Prefer small, test-backed fixes to reduce the baseline over time.
