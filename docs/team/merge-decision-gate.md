# Final Merge Decision Gate (Integrator)

Only the integrator merges to `main`.

## Required Merge Conditions

1. PR targets `main`.
2. Branch naming contract is valid.
3. Commit message contract is valid.
4. PR template is complete (Problem/Scope/Files/Test/Risk/Rollback).
5. CI checks are all green.
6. Required approvals are present.
7. No unresolved conflicts.
8. Rollback method is documented and feasible.

If any condition fails: do not merge.

## Merge Method

1. Squash merge only.
2. Merge one PR at a time.
3. Run smoke checks after each merge.

## Post-Merge Validation

1. Confirm app boots and key pages load.
2. Confirm no immediate regression in changed area.
3. If issue appears, revert quickly using documented rollback.

## Rollback Standard

1. Preferred: `git revert <merge-commit>`
2. Open hotfix PR if additional repair is needed.
3. Document incident and prevention steps.

