Title: feat: add Larastan (Laravel PHPStan integration)

Summary:
- Added `nunomaduro/larastan` to `require-dev` and included Larastan extension in `phpstan.neon` to improve Laravel-specific static analysis.
- Fixed model relationships and controller code to satisfy Larastan rules.

Changes:
- Composer: added `nunomaduro/larastan` (dev).
- phpstan.neon: included Larastan extension.
- Updated models with typed relationships and property-read annotations.
- Updated `StatsController` to use typed closures and `pluck` for stock aggregation.

Testing:
- All unit and feature tests pass locally (42 tests).
- Ran PHPStan with Larastan locally; no errors remain.

Notes:
- Larastan package warns it is abandoned in favor of `larastan/larastan`; consider migrating later.
- Reviewers: @dragarys

Merge checklist:
- [ ] CI green
- [ ] Review approved
- [ ] Auto-merge enabled (optional)
