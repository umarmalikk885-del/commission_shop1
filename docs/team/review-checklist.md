# PR Review Checklist

Use this checklist for every PR.

## Correctness

1. Business logic is correct.
2. Edge cases are handled.
3. Error paths are handled safely.

## Regression Risk

1. Existing behavior is preserved outside scope.
2. Backward compatibility considered.
3. Side effects identified and tested.

## Security

1. No secrets/tokens hardcoded.
2. Input validation and sanitization are correct.
3. Authorization/permission paths are intact.

## Data and Compatibility

1. Migration/DB impacts are clearly documented.
2. Rollback plan exists if schema/data changed.
3. API/contracts remain compatible (or breaking changes documented).

## UX / Frontend

1. UI text and visibility are correct.
2. Dark/light mode behavior is consistent where applicable.
3. No layout regressions in scope pages.

## Test Evidence

1. Relevant tests updated/added.
2. Local checks executed.
3. CI checks are green.

## AI-Specific

1. AI-generated sections are explicitly reviewed.
2. AI assumptions were validated against codebase.
3. No unverified AI output remains.

