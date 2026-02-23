# Daily Operating SOP

## Start of Day

1. `git checkout main`
2. `git pull origin main`
3. `git checkout -b feature/<ticket>-<short-name>` (or `fix/...`, `chore/...`)

## During Development

1. Commit every 30-90 minutes with valid message format.
2. Push regularly to remote branch.
3. Open PR as Draft early.

## Before Marking PR Ready

1. Pull latest `main` and rebase/merge branch.
2. Resolve conflicts in your branch.
3. Run local tests/build.
4. Complete all PR template sections.

## Merge Stage

1. Integrator verifies all gates from `docs/team/merge-decision-gate.md`.
2. Integrator merges one PR at a time using squash merge.
3. Run post-merge smoke checks after each merge.

## Non-Negotiable Rules

1. No direct push to `main`.
2. No force merge on unresolved conflicts.
3. No merge with failing checks.
4. No AI-generated commit pushed without local validation.

