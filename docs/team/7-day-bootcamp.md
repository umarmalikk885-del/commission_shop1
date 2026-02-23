# 7-Day Bootcamp: Second Developer + AI Workflow

## Objective

Make a second developer production-ready in 7 days using Codex + PR workflow with one integrator.

## Day 1 - Setup and Baseline

1. Create remote repository and set default branch to `main`.
2. Enable branch protection on `main`.
3. Require PR for all merges.
4. Require CI checks from `.github/workflows/ci.yml` and `.github/workflows/pr-governance.yml`.
5. Define integrator role (single person responsible for final merge).
6. Configure labels used in triage and review.
7. Decide whether to use direct `feature -> main` (default) or optional `develop` branch.

## Day 2 - Branch and PR Drill

1. Developer A creates `feature/<ticket>-<short-name>`.
2. Developer B creates `fix/<ticket>-<short-name>`.
3. Both push changes and open PRs using template.
4. Integrator reviews but does not merge unless all checks pass.

## Day 3 - AI Prompt Discipline

Use prompt format:

1. Task
2. Scope
3. Constraints
4. Allowed files
5. Forbidden files
6. Required tests

Developers must verify AI output line-by-line.

## Day 4 - Conflict Simulation

1. Both developers edit overlapping area intentionally.
2. Update branch with latest `main`.
3. Resolve conflicts in branch (PR author only).
4. Re-run tests and update PR.

## Day 5 - Review Quality Drill

1. Apply checklist from `docs/team/review-checklist.md`.
2. Intentionally reject one PR for missing tests.
3. Developer fixes and re-submits.
4. Integrator approves only after all gates pass.

## Day 6 - Release Candidate Flow

1. Merge PR-1.
2. Run smoke checks.
3. Merge PR-2.
4. Run smoke checks again.
5. Document rollback steps for both merges.

## Day 7 - Production Readiness

Run full simulation:

1. Parallel branches
2. AI-assisted implementation
3. PR validation checks
4. Conflict resolution
5. Integrator merge decision
6. Post-merge verification

## Workflow Validation Scenarios

1. Parallel non-overlapping work: two branches and two PRs merge with no regression.
2. Parallel overlapping work: one conflict is resolved by PR author and checks pass.
3. Failed-check scenario: PR with failing tests/build cannot merge.
4. Missing-review scenario: PR without required approval cannot merge.
5. Rollback scenario: bad merge is reverted cleanly with one revert commit.
6. AI-hallucination scenario: incorrect AI proposal is caught in review and blocked.

## Rollout and Monitoring

### First Two Weeks

1. Integrator performs a daily audit of PR quality.
2. Track top conflict sources and recurring review comments.

### Weekly Metrics

1. PR lead time.
2. Review turnaround time.
3. Failed-check rate.
4. Post-merge incident count.

### Stabilization Target

By week 3, both developers deliver PRs independently with less than 10% rework.

## Assumptions and Defaults

1. Platform is GitHub (or equivalent Git remote).
2. Codex is used only on feature branches.
3. One integrator owns final merge decisions.
4. Direct push to `main` is disabled.
5. 7-day fast-track training is selected over longer tracks.
6. Existing application logic remains unchanged; this plan is process-only.

## Success Criteria

1. Both developers can independently open compliant PRs.
2. No direct pushes to `main`.
3. Integrator follows merge gate every time.
4. CI and governance checks block non-compliant PRs.
