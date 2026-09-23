# Stage 01 — Employee Search

## Difficulty

Easy

## Vulnerability

Reflected Cross-Site Scripting (XSS)

## Scenario

CompanyAdmin provides an internal employee directory for authorized company users.

The directory allows users to search employees by:

* Name
* Employee code
* Department

The search page displays the submitted search term back to the user.

Your task is to investigate how the search input is processed and determine whether it can be abused.

---

## Objective

Identify the vulnerability in the employee search functionality and exploit it to retrieve the Stage 01 flag.

After obtaining the flag, submit it through the flag submission form to unlock Stage 02.

---

## Hint

> The application remembers what you searched for.

---

## Learning Goals

This stage introduces:

* Reflected XSS
* User-controlled input
* HTML output contexts
* Output encoding
* Basic browser-side security testing
* Understanding the difference between safe and unsafe HTML output

---

## Application Flow

The vulnerable functionality works approximately like this:

1. The user submits a search query.
2. The application searches the employee database.
3. The application displays the search term on the result page.
4. The search term is inserted into the HTML response.

The important question is:

> Is the search term safely encoded before being inserted into HTML?

---

## Intended Investigation

Start with a normal search:

```text
/stages/stage01/?q=Alice
```

Observe that the application displays the search term on the page.

Next, test whether HTML markup is interpreted.

For example:

```html
<b>TEST</b>
```

URL-encoded:

```text
%3Cb%3ETEST%3C%2Fb%3E
```

If the result is rendered as HTML instead of displayed as plain text, investigate the output location more closely.

---

## Vulnerable Code

The vulnerable output is:

```php
<strong><?= $search ?></strong>
```

The value of `$search` is controlled by the HTTP request:

```php
$search = $_GET['q'] ?? '';
```

The application therefore places attacker-controlled data directly into an HTML response.

---

## Why This Is Vulnerable

The application correctly escapes some values:

```php
value="<?= e($search) ?>"
```

and employee database values are also escaped.

However, the search term is inserted into the result heading without output encoding:

```php
<strong><?= $search ?></strong>
```

This creates an HTML injection point.

Because the browser interprets the response as HTML, attacker-controlled markup may become part of the page.

---

## Exploitation

The purpose of this lab is to understand how reflected XSS works in practice.

A basic proof of concept can use an HTML element:

```html
<b>TEST</b>
```

A JavaScript-based proof of concept can then be tested in the browser.

For example:

```html
<script>alert(document.domain)</script>
```

The browser should interpret the injected markup as part of the response.

For this lab, the application also exposes a stage-specific secret endpoint.

The intended challenge is to investigate whether the XSS vulnerability can be used to interact with resources available to the current page and retrieve the stage secret.

---

## Secret Endpoint

Stage 01 contains a challenge-specific endpoint:

```text
/stages/stage01/?action=secret
```

The endpoint returns JSON containing the stage flag.

The endpoint exists specifically to support the XSS exercise.

In a real application, exposing sensitive information to an unauthenticated or insufficiently protected endpoint would itself be a security problem.

For this training lab, the endpoint is intentionally included so that the XSS vulnerability has a meaningful objective.

---

## Impact

Reflected XSS can allow attacker-controlled JavaScript to execute in a victim's browser under the security context of the vulnerable application.

Depending on the application's functionality and security controls, potential consequences can include:

* Unauthorized actions performed through the victim's browser
* Sensitive data exposure
* Session-related attacks
* DOM manipulation
* Phishing interfaces
* Modification of page content
* Interaction with application endpoints available to the victim

The actual impact depends on the application's authentication, authorization, cookies, CSP, and other security controls.

---

## Remediation

User-controlled data must be safely encoded for its output context.

For HTML output, use appropriate HTML encoding:

```php
htmlspecialchars(
    $value,
    ENT_QUOTES | ENT_SUBSTITUTE,
    'UTF-8'
);
```

The vulnerable code:

```php
<strong><?= $search ?></strong>
```

should become:

```php
<strong><?= e($search) ?></strong>
```

where `e()` performs context-appropriate HTML escaping.

---

## Secure Version

The helper used by this project is:

```php
function e(string $value): string
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}
```

The secure output is:

```php
<strong><?= e($search) ?></strong>
```

This causes HTML characters supplied by the user to be represented as text instead of being interpreted as markup.

---

## Defense in Depth

Output encoding should be the primary defense.

Additional protections may include:

* Content Security Policy (CSP)
* Secure cookie configuration
* HttpOnly cookies
* SameSite cookies
* Avoiding dangerous DOM sinks
* Context-aware output encoding
* Input validation where appropriate

Input filtering alone should not be treated as the primary XSS defense.

---

## Flag Submission

After retrieving the flag, submit it using the form at the bottom of the Stage 01 page.

The application:

1. Reads the submitted flag.
2. Calculates its SHA-256 hash.
3. Compares the hash against the stored stage hash.
4. Marks the stage as completed if the hashes match.
5. Unlocks the next stage.

The database stores the flag hash rather than the plaintext flag.

---

## Flag Generation

Flags should be randomly generated for each stage.

Example:

```bash
php -r 'echo "FLAG-STAGE01-" . bin2hex(random_bytes(24)) . PHP_EOL;'
```

Example output:

```text
FLAG-STAGE01-7f3c91a84e2d6b519c0a4f8e73bd1c56c5a93f7d
```

The exact value should be different for each installation.

Generate its SHA-256 hash:

```bash
printf '%s' 'YOUR_FLAG_HERE' | sha256sum
```

Store only the resulting hash in the `flags.flag_hash` column.

The plaintext flag belongs in the local `.env` file and must never be committed to Git.

---

## Verification

Check that the environment contains the flag:

```bash
grep '^STAGE01_FLAG=' .env
```

Check the stored database hash:

```sql
SELECT flag_hash
FROM flags
WHERE stage_id = (
    SELECT id
    FROM stages
    WHERE stage_number = 1
);
```

The hash calculated from `.env` must exactly match the database value.

---

## Completion

After submitting the correct flag, verify progress:

```sql
SELECT
    user_id,
    stage_id,
    completed,
    completed_at
FROM progress
WHERE user_id = 1;
```

Stage 01 should have:

```text
completed = 1
```

Stage 02 should then become available from the main dashboard.

---

## OWASP Context

This stage covers Cross-Site Scripting (XSS), historically categorized under Injection in the OWASP Top 10.

The exact OWASP category and taxonomy can differ between OWASP Top 10 versions.

The core lesson remains:

> Never place untrusted data into an HTML context without appropriate context-aware output encoding.
