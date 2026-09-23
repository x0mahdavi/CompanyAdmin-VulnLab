# Stage 01 — Employee Search

## Difficulty

Easy

## Scenario

CompanyAdmin provides an internal employee directory for authorized company users.

The directory includes a search feature that allows users to search employees by:

- Name
- Employee code
- Department

Your task is to investigate how the search input is processed and displayed.

## Objective

Find the security weakness in the employee search functionality and use it to retrieve the stage flag.

## Hint

> The application remembers what you searched for.

## Learning Goals

This stage introduces:

- Reflected XSS
- User-controlled input
- HTML output contexts
- Input/output handling
- Basic browser-side security testing

## Intended Investigation

Inspect the search functionality and observe what happens when special HTML characters are included in the search query.

Compare how database values are displayed with how the search term itself is displayed.

## Impact

An attacker may be able to execute attacker-controlled JavaScript in another user's browser when a malicious search URL is visited.

Potential impact includes:

- Session-related attacks
- DOM manipulation
- Phishing interfaces
- Sensitive information exposure depending on application context

## Remediation

User-controlled data must be safely encoded before being inserted into HTML.

For example:

```php
htmlspecialchars(
    $value,
    ENT_QUOTES | ENT_SUBSTITUTE,
    'UTF-8'
);
