# Second Developer Quick Guide (AI + PR Workflow)

Use this file as your day-to-day instruction sheet.

## Your Role

1. Work only on assigned tickets.
2. Use a feature/fix/chore branch for every task.
3. Open a PR for every change.
4. Never push directly to `main`.
5. Ask the integrator if scope is unclear.

## Work You Can Perform

1. UI fixes on assigned pages.
2. Bug fixes in assigned modules.
3. Tests related to your changes.
4. Documentation updates.
5. Small refactors inside assigned files without behavior changes.

## Work You Must Not Perform Alone

1. Direct merge or push to `main`.
2. Disabling CI checks or PR checks.
3. Editing secrets, credentials, or `.env` production values.
4. Large database or auth/security changes without integrator approval.
5. Broad cross-module rewrites outside your ticket scope.

## Daily Workflow

1. `git checkout main`
2. `git pull origin main`
3. `git checkout -b feature/<ticket>-<short-name>` (or `fix/...`, `chore/...`)
4. Make focused changes and commit every 30-90 minutes.
5. `git push -u origin <branch-name>`
6. Open Draft PR early.
7. Before Ready for Review: run tests/build, update from `main`, resolve conflicts.
8. Fill all PR template sections.

## AI Workflow (Codex)

1. Use `docs/team/ai-prompt-template.md` for every AI task.
2. Always include:
   - task
   - scope
   - allowed files
   - forbidden files
   - required tests
3. Review all AI-generated code line-by-line.
4. Reject AI changes that touch unrelated files.
5. Run local validation before push.
6. AI cannot approve PRs or decide merge readiness.

## PR Ready Checklist

1. Branch name follows contract.
2. Commit messages follow `type(scope): short summary`.
3. PR template is complete.
4. Test evidence is attached.
5. Risk and rollback notes are present.
6. CI checks are green.

## Useful Commands

```bash
git checkout main
git pull origin main
git checkout -b feature/123-short-task
git add .
git commit -m "fix(module): short summary"
git push -u origin feature/123-short-task
gh pr create --fill
```

## If You Are Stuck

1. Stop and write what is blocked.
2. Share files touched, error text, and what you already tried.
3. Ask integrator for scope decision before continuing.
