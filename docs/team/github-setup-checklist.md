# GitHub Setup Checklist (One-Time)

This checklist maps repository settings to the workflow contracts.

## Repository Baseline

1. Create remote repository.
2. Set default branch to `main`.
3. Add collaborators (Developer A, Developer B, Integrator).

## Branch Protection: `main`

Enable:

1. Require a pull request before merging.
2. Require approvals before merging (minimum: 1).
3. Require status checks to pass before merging.
4. Select required checks:
   1. `php-lint`
   2. `php-tests`
   3. `frontend-build`
   4. `branch-name-check`
   5. `commit-message-check`
   6. `pr-template-check`
5. Dismiss stale approvals when new commits are pushed.
6. Require branches to be up to date before merging.
7. Restrict who can push to `main` (none or integrator only).
8. Disallow force pushes.
9. Disallow branch deletion.

## Merge Options

1. Enable squash merge.
2. Disable merge commit.
3. Disable rebase merge (optional but recommended for strict policy).
4. Disable auto-merge unless integrator policy allows it.

## Labels (Manual)

Create labels:

1. `priority:high`
2. `priority:medium`
3. `priority:low`
4. `risk:high`
5. `risk:low`
6. `needs-tests`
7. `ready-for-review`
8. `ready-to-merge`

## Team Policy

1. One integrator is assigned.
2. Integrator uses `docs/team/merge-decision-gate.md`.
3. Developers follow `CONTRIBUTING.md` and `docs/team/daily-operating-sop.md`.

