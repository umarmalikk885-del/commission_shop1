# Second Codex Handoff Guide

Use this file when starting a second Codex session on this project.

## Purpose

Give the second Codex the same operating rules, quality bar, and workflow used by the first Codex.

## Project Context

1. Stack: Laravel + Blade + JS.
2. Main branch is protected.
3. All work must go through PR checks and approval.
4. Integrator performs final merge to `main`.

## Non-Negotiable Rules

1. Do not push directly to `main`.
2. Work only on ticket scope files.
3. Do not change unrelated functionality.
4. Do not modify secrets, credentials, or production env values.
5. Keep commit and branch naming contracts from `CONTRIBUTING.md`.

## Required Workflow

1. Read `CONTRIBUTING.md`.
2. Read `docs/team/second-developer-guide.md`.
3. Create branch:
   - `feature/<ticket>-<short-name>`
   - `fix/<ticket>-<short-name>`
   - `chore/<short-name>`
4. Make minimal scoped changes.
5. Run required local validation.
6. Open PR with template.
7. Wait for review and required checks.

## AI Behavior Contract

1. AI may propose code and tests.
2. AI must not decide merge readiness.
3. AI output must be reviewed line-by-line by developer.
4. AI must not bypass CI, review, or branch protection.
5. AI must reject requests that expand scope without approval.

## Editing Guardrails

1. Prefer targeted edits over broad refactors.
2. Keep naming and style consistent with nearby code.
3. Preserve existing page behavior unless task explicitly changes it.
4. If unsure, stop and ask for clarification instead of guessing.
5. Document assumptions in PR description.

## Quality Gate Before PR

1. Branch name passes policy.
2. Commit messages follow `type(scope): short summary`.
3. PR includes:
   - Problem
   - Scope
   - Files Changed
   - Test Evidence
   - Risks
   - Rollback
4. CI checks are green.

## Copy-Paste Starter Prompt (for Second Codex)

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

1. `CONTRIBUTING.md`
2. `docs/team/ai-prompt-template.md`
3. `docs/team/daily-operating-sop.md`
4. `docs/team/review-checklist.md`
5. `docs/team/merge-decision-gate.md`

## One-Command Machine Setup

Run this on the second machine:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\tools\setup-second-codex.ps1 -RepoOwner umarmalikk885-del -RepoName commission_shop1 -TargetRoot "$HOME\Desktop" -GitUserName "Second Dev Name" -GitUserEmail "seconddev@example.com"
```
