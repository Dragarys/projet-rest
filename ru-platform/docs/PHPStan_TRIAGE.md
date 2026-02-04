# PHPStan Baseline Triage (auto-generated)

**Summary**

- Baseline file: `phpstan-baseline.neon` (generated locally)
- Total ignored errors in baseline: 158
- Baseline was added on branch: `ci/trigger-phpstan-baseline`

**Top categories observed**

- staticMethod.notFound (calls to Eloquent static methods like `Model::create`, `Model::orderBy`) — common due to dynamic Eloquent methods
- property.notFound (access to model properties not known to static analysis)
- parameterTypeMismatch / returnTypeMismatch in controllers and model interactions

**Why this baseline exists**

To unblock CI quickly and enable PHPStan in CI while keeping the existing code working, we generated a baseline to ignore current issues. The baseline enables us to enforce new issues while we triage and fix existing items incrementally.

**Prioritized next steps (suggested)**

1. Add model PHPDocs and/or create PHPStan stub files for Eloquent models to help static analysis detect dynamic properties and methods (e.g., `@property`, `@method` annotations). This will address many `property.notFound` and `staticMethod.notFound` items.

2. Install and configure Larastan (PHPStan extension for Laravel) or appropriate Eloquent stubs which are commonly used to improve Laravel model analysis.

3. Start triaging baseline entries by file priority:
   - Fix critical controllers affecting API behavior (e.g., `OrderController`, `PaymentController`, `DishController`, `StatsController`). Unit and feature tests should accompany fixes.
   - Fix model-related issues (add PHPDocs and cast types where missing).

4. Create small PRs that remove some entries from the baseline and re-run PHPStan to shrink baseline iteratively. Each PR should aim to fix a small group of related baseline entries.

5. Once the baseline is significantly reduced (< ~20 entries), consider removing it and raising PHPStan level gradually.

**How I can help next**

- Create small focused PRs fixing high-impact items and updating tests. ✅
- Add Larastan or model stubs and demonstrate reduction in baseline. ✅
- Continue monitoring CI and complete PR merge when approved. ✅

---

> Note: The baseline was generated locally using `phpstan.phar` and committed to `ci/trigger-phpstan-baseline`. CI should pick it up once the branch run completes successfully. If you want, I can open the PR for you (requires GitHub token or `gh` CLI), or you can create it manually using the already opened page at: https://github.com/Dragarys/projet-rest/pull/new/ci/trigger-phpstan-baseline
