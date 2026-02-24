# Second Codex Checklist (commission_shop1)

## Purpose
- [ ] Use this checklist to onboard the second developer/Codex with the same workflow and quality bar used by the first developer.

## Quick Start (Second Machine)
- [ ] Accept the GitHub invite to `umarmalikk885-del/commission_shop1`.
- [ ] Install required tools (if missing):
```powershell
winget install --id Git.Git -e
winget install --id GitHub.cli -e
```
- [ ] Authenticate GitHub CLI:
```powershell
gh auth login --web --git-protocol https
gh auth status
```
- [ ] Clone the repository and open project folder:
```powershell
git clone https://github.com/umarmalikk885-del/commission_shop1.git
cd commission_shop1
```
- [ ] Set Git identity on second machine:
```powershell
git config user.name "Second Dev Name"
git config user.email "seconddev@example.com"
```

## Branch and PR Workflow
- [ ] Use only approved branch patterns:
  - `feature/<ticket>-<short-name>`
  - `fix/<ticket>-<short-name>`
  - `chore/<short-name>`
- [ ] Start from latest `main` and create work branch:
```powershell
git checkout main
git pull origin main
git checkout -b feature/123-task-name
```
- [ ] Commit and push:
```powershell
git add .
git commit -m "fix(module): short summary"
git push -u origin feature/123-task-name
```
- [ ] Create PR to `main`:
```powershell
gh pr create --repo umarmalikk885-del/commission_shop1 --base main --head feature/123-task-name --fill
```

## Required Reading Before Coding
- [ ] `CONTRIBUTING.md`
- [ ] `docs/team/second-developer-guide.md` - TODO: missing in current repo.
- [ ] `docs/team/second-codex-guide.md` - TODO: missing in current repo.

## Non-Negotiable Rules
- [ ] Do not push directly to `main`.
- [ ] Work only in ticket scope files.
- [ ] Do not change unrelated functionality.
- [ ] Do not modify secrets, credentials, or production environment values.
- [ ] Keep commit and branch naming contracts from `CONTRIBUTING.md`.

## AI Behavior Contract
- [ ] AI may propose code and tests.
- [ ] AI must not decide merge readiness.
- [ ] AI output must be reviewed line-by-line by the developer.
- [ ] AI must not bypass CI, review, or branch protection.
- [ ] AI must reject scope expansion requests unless explicitly approved.

## Editing Guardrails
- [ ] Prefer targeted edits over broad refactors.
- [ ] Keep naming and style consistent with nearby code.
- [ ] Preserve existing behavior unless task explicitly changes it.
- [ ] If unsure, stop and ask for clarification.
- [ ] Document assumptions in PR description.

## PR Quality Gate
- [ ] Branch name passes policy.
- [ ] Commit messages follow `type(scope): short summary`.
- [ ] PR includes:
  - `Problem`
  - `Scope`
  - `Files Changed`
  - `Test Evidence`
  - `Risks`
  - `Rollback`
- [ ] CI checks are green.

## Starter Prompt Template
```text
You are the second Codex working in commission_shop1.
Follow CONTRIBUTING.md and docs/team/second-developer-guide.md strictly.
Task:
<add task>

Scope:
<what is in scope>

Out of Scope:
<what must not change>

Allowed Files:
<exact files>

Forbidden Files:
<exact files>

Required Validation:
<tests/build/checks>

Output:
1) Summary of changes
2) Exact file list
3) Risks and rollback note
4) Validation evidence
```

## Reference Files
- [ ] `CONTRIBUTING.md`
- [ ] `docs/team/ai-prompt-template.md`
- [ ] `docs/team/daily-operating-sop.md`
- [ ] `docs/team/review-checklist.md`
- [ ] `docs/team/merge-decision-gate.md`

## Notes
- [ ] This checklist does not bypass CI, review, or branch protection.
- [ ] Integrator retains final merge responsibility to `main`.
