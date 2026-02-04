# Larastan (PHPStan for Laravel) setup

This change adds `nunomaduro/larastan` to `require-dev` and includes the Larastan extension in `phpstan.neon`.

Why:
- Larastan provides Laravel-aware PHPStan rules and model analysis improvements which will reduce false positives and help detect real issues.

What CI will do:
- The existing CI job installs dev dependencies (Composer), so Larastan will be available during CI runs.
- PHPStan will use `vendor/nunomaduro/larastan/extension.neon` to enhance checks.

Next steps (suggested):
- Monitor CI runs for any new PHPStan findings introduced by Larastan and triage them.
- Gradually increase `level` in `phpstan.neon` and fix new issues.
- Optionally add a dedicated workflow job that runs `composer require --dev nunomaduro/larastan` in a controlled way if needed.
