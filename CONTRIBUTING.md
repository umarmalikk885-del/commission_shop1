# Contributing Guide

This project uses a two-developer workflow with AI assistance and one integrator for final merges.

## Collaboration Model

1. Both developers work in parallel on separate branches.
2. Codex/AI is allowed only on feature branches.
3. Every change goes through a Pull Request (PR).
4. One integrator performs the final merge to `main`.
5. Direct pushes to `main` are not allowed.

## Branch Naming Contract

Allowed patterns:

1. `feature/<ticket>-<short-name>`
2. `fix/<ticket>-<short-name>`
3. `chore/<short-name>`

Examples:

1. `feature/123-packing-popup`
2. `fix/456-dark-mode-text`
3. `chore/update-docs`

## Commit Message Contract

Use:

`type(scope): short summary`

Allowed `type` values:

1. `feat`
2. `fix`
3. `refactor`
4. `chore`
5. `docs`
6. `test`

Examples:

1. `feat(bakery): add packing modal keyboard navigation`
2. `fix(payment): keep refresh button text visible in dark mode`
3. `docs(workflow): add integrator merge checklist`

## Pull Request Requirements

Each PR must include:

1. Problem
2. Scope
3. Files changed
4. Test evidence
5. Risks
6. Rollback plan

Use the repository PR template.

## Review Checklist

Reviewers must check:

1. Logic correctness
2. Regression risk
3. Security impact
4. Migration/compatibility impact
5. UX impact
6. Test coverage/evidence

Detailed checklist: `docs/team/review-checklist.md`.

## Integrator Merge Gate

Integrator merges only when all are true:

1. CI checks are green
2. Required approval exists
3. No unresolved conflicts
4. PR template is complete
5. Rollback note is present

Detailed merge gate: `docs/team/merge-decision-gate.md`.

## AI Usage Rules

1. AI prompts must include scope and excluded files.
2. AI output must be reviewed line-by-line by the developer.
3. AI does not decide merge readiness.
4. AI output must never include secrets or credentials.
5. No AI-generated commit is pushed without local test run.

Prompt template: `docs/team/ai-prompt-template.md`.

## Daily SOP

1. `checkout main -> pull -> create/update feature branch`
2. Commit in small chunks every 30-90 minutes
3. Push often and keep PR in Draft early
4. Rebase/update from `main` before final review
5. Attach test evidence before requesting merge

Detailed SOP: `docs/team/daily-operating-sop.md`.

