name: PHPStan Triage
about: Triage and fix findings reported by PHPStan static analysis
title: '[phpstan] - <short description>'
labels: bug, phpstan
assignees: ''

---

**Summary**

_A short description of the PHPStan issue and why it needs attention._

**PHPStan output (paste a short excerpt)**

```
# paste the relevant lines from PHPStan here
```

**Files involved**

- path/to/file.php

**Suggested fix**

_Explain the proposed change (code fix, add type, adjust test, or ignore in baseline)_

**References**

- Link to the CI run or commit where the issue was introduced

**Checklist**
- [ ] Reproduce locally using `./scripts/run_phpstan.ps1`
- [ ] Add unit tests if applicable
- [ ] Update `phpstan-baseline.neon` if the issue is a known false positive
- [ ] Submit PR with the fix
