# PortalCMS Improvement Plan

Last reviewed: 2026-07-26

## Purpose

This is the active improvement plan following the feature-first and Symfony
refactors. It replaces the earlier findings list with an implementation order,
explicit architectural decisions, database work, tests, and completion
criteria.

The existing `src/Core`, `src/Features`, and `src/View` split remains. This
plan does not introduce generic `Manager`, `Handler`, `Query`, or application-
wide `Service` layers.

## Current Audit

| Finding | Current status | Priority |
|---|---|---|
| Mail attachments under the web root | Still exploitable; client filenames and executable extensions are accepted | Critical |
| Page editing authorization and stored XSS | Still open; the controller has no permission check and stored HTML is rendered raw | Critical |
| Activity transaction boundaries | Invoice item and activity writes are separate | High |
| Password reset | Still open; configured URL is stale, responses enumerate users, and tokens are stored raw | High |
| Invoice mail transaction and delivery reliability | Still open; queue creation has multiple flushes and SMTP delivery cannot be claimed atomically | High |
| SMTP TLS, credentials, and logging | TLS verification is disabled and logs/credentials are unsafe | High |
| Colliding workflow/entity names | Activity and Email still require aliases | Medium |
| Money representation | Still open; float arithmetic and decimal-to-int truncation can lose cents | Medium |
| Behavioral tests | Functional coverage is still too limited for the remaining security and transaction work | Medium |

## Delivery Rules

Every implementation phase must follow these rules:

1. Add a focused regression test that fails before the behavioral change.
2. Keep controllers responsible for HTTP, authorization, input mapping, and
   response selection only.
3. Keep entities responsible for mapped state and state transitions only.
4. Keep repositories responsible for queries plus `persist()` and `remove()`.
5. Put transaction ownership and the final `flush()` in one feature workflow.
6. Add an incremental Doctrine migration for every schema change. Do not edit
   the executed squashed baseline.
7. Execute each new migration locally and against an imported legacy backup;
   finish with zero pending migrations.
8. Use one canonical route. Do not add compatibility aliases or redirects for
   removed application URLs.
9. Run PHP lint, PHP-CS-Fixer, container lint, PHPUnit, PHPStan, Composer audit,
   migration status, schema validation, JavaScript lint, and the asset build.

## Phase 0: Behavioral Test Foundation

This phase must remain small so that it does not delay the critical security
fixes.

### 0.1 Add test packages

Add development dependencies:

- `phpunit/phpunit`
- `symfony/browser-kit`
- `symfony/css-selector`
- `phpstan/phpstan`
- `phpstan/phpstan-doctrine`
- `phpstan/phpstan-symfony`

Add `phpunit.xml.dist`, `phpstan.neon.dist`, and PSR-4 autoloading for
`PortalCMS\Tests\`.

### 0.2 Add application test support

- Add a test environment configuration with a dedicated MySQL database.
- Boot the real `Core\Kernel` from functional tests.
- Provide factories for an authenticated administrator, a regular user,
  permissions, and the minimum settings rows.
- Keep test data creation in tests; do not copy production records.
- Make database reset deterministic by running migrations before each suite
  and wrapping individual tests in transactions where possible.

### 0.3 Extend CI

- Add a MySQL service to GitHub Actions.
- Run the squashed baseline against an empty database.
- Run the importer against a sanitized fixture representing a legacy schema.
- Run PHPUnit and PHPStan after migration.
- Preserve the existing lint and asset jobs.

### Acceptance criteria

- A kernel smoke test loads `/Login`.
- A database test persists and reloads one entity.
- CI fails for a pending migration, invalid mapping, PHPStan error, or failed
  behavioral test.

## Phase 1: Move Mail Attachments Outside `public`

This is the first production code change because the current upload path can
lead directly to remote code execution.

### 1.1 Add immediate containment

- Deny direct HTTP access to `public/content/attachments` in IIS.
- Disable script handlers below all writable public content directories.
- Keep logos public because they are decoded and re-encoded as JPEG by GD.
- Do not treat web-server rules as the final storage solution.

### 1.2 Introduce private attachment storage

Create `Features\Email\Attachment\AttachmentStorage` with these operations:

- `store(UploadedFile): StoredAttachment`
- `path(string $storageKey): string`
- `remove(string $storageKey): void`
- `exists(string $storageKey): bool`

Use `var/storage/email-attachments` by default, configurable through an
environment variable. The directory must be outside `public`, non-executable,
and writable only by the application identity.

`StoredAttachment` is a specific immutable value containing:

- opaque generated storage key
- original display filename
- detected MIME type
- byte size

Do not preserve the client filename as the physical filename. Use a random
128-bit key or Symfony UID and store the file without an executable extension.

### 1.3 Enforce an explicit allowlist

Detect MIME type from file content with Fileinfo/Symfony Mime. Initially allow
only formats the application needs:

- PDF
- JPEG, PNG, GIF, and WebP
- plain text and CSV
- non-macro OOXML documents if required

Reject PHP, PHAR, HTML, SVG, JavaScript, XML, shell files, executable binaries,
archives, and MIME/extension mismatches. Keep the existing 5 MB byte limit and
add type-specific limits if operational use requires larger documents.

### 1.4 Change persistence

Add an incremental migration that records:

- `storage_key`
- `original_name`
- `mime_type`
- `size`

Stop building paths from `path + name + extension`. Existing records must be
migrated by copying files to private storage and assigning generated keys.
Abort migration/deployment with a clear report for missing or unreadable
legacy files.

Because template attachments can be copied to scheduled mail, make file
ownership explicit. Prefer one stored blob referenced by multiple attachment
records and delete the physical blob only when no database reference remains.

### 1.5 Add authorized delivery

- Add one canonical `GET /Email/Attachments/{id}` route.
- Require the same permission used to view mail/template details.
- Resolve the attachment by ID and stream it with `BinaryFileResponse`.
- Set `Content-Disposition: attachment` and
  `X-Content-Type-Options: nosniff`.
- Update mail views to use the controller route.
- Let the SMTP/Mailer adapter read the private filesystem path directly.

### Tests and acceptance criteria

- Uploading a `.php`, dual-extension, spoofed MIME, SVG, or oversized file is
  rejected and creates no file.
- Two files with the same client name receive different storage keys.
- A valid PDF can be attached to an email and downloaded by an authorized
  user.
- An unauthorized user receives 403; a missing record receives 404.
- No attachment exists below `public`.
- Migrated legacy attachments remain usable.

## Phase 2: Protect Page Editing and Rich HTML

### 2.1 Close the authorization gap immediately

- Inject the current `Authorization` service into `PageController`.
- Require `site-settings` for both GET and POST.
- Add functional tests for anonymous, authenticated unauthorized, and
  authorized users.

This immediate check is intentionally implemented before the later
SecurityBundle migration.

### 2.2 Add Symfony HTML Sanitizer

Install `symfony/html-sanitizer` and configure a named `page_content`
sanitizer using the safe-elements baseline. Pin/update Symfony packages to a
security-supported 7.4 patch and run `composer audit`; affected sanitizer
versions below 7.4.12 must not be installed.

Define the page policy explicitly:

- allow headings, paragraphs, lists, tables, emphasis, and links
- allow images only when the business requirement is confirmed
- allow only `https`, `mailto`, and safe local link URLs
- remove scripts, styles, event attributes, forms, iframes, embedded objects,
  and unsafe URL schemes
- enforce a maximum input length

### 2.3 Sanitize at the write boundary

- Add `Pages\Application\Pages` to authorize the update workflow and sanitize
  input before `Page::changeContent()`.
- Store only sanitized HTML.
- Make intentional raw rendering obvious by naming the view value
  `trustedPageHtml`.
- Escape the page name and textarea value in the editor.
- Add a migration/one-time command that sanitizes existing page rows and
  reports changed content before applying it.

Symfony reference:
[HTML Sanitizer](https://symfony.com/doc/7.4/html_sanitizer.html).

### Tests and acceptance criteria

- A user without `site-settings` cannot view or submit the editor.
- Script tags, event handlers, `javascript:` URLs, forms, and iframes are
  removed.
- Allowed formatting survives.
- Existing stored malicious HTML is cleaned.
- The home page renders the sanitized HTML and no other unescaped page scalar.

## Phase 3: Fix Activity Logging and Transaction Boundaries

### 3.1 Make invoice item changes atomic

- Move invoice item mutation plus activity creation into an Invoices workflow.
- Persist both objects before one final flush.
- Wrap the operation with `EntityManagerInterface::wrapInTransaction()`.
- Do the same for invoice item deletion and other mutations that emit an
  activity entry.
- Ensure `ActivityLog::add()` can stage an entry without owning a flush.

### 3.2 Define audit failure behavior

Audit creation is part of the transaction. If it fails, the invoice mutation
must roll back. Log the exception outside the failed transaction without
including sensitive form data.

### Tests and acceptance criteria

- Long invoice item names produce valid activity entries.
- Simulated activity persistence failure leaves no invoice item behind.
- Successful changes create exactly one audit entry.
- MySQL strict mode is enabled in CI.

## Phase 4: Repair and Harden Password Reset

### 4.1 Generate the canonical route

- Inject `UrlGeneratorInterface` into `PasswordReset`.
- Generate `login.password_reset` with `UrlGeneratorInterface::ABSOLUTE_URL`.
- Remove `EMAIL_PASSWORD_RESET_URL` from configuration and examples.

### 4.2 Prevent user enumeration

- Return the same response, redirect, flash text, and approximate timing
  whether the account exists or not.
- Record internal delivery failures through the logger, not the browser.
- Rate-limit requests by normalized login identifier and remote address.

### 4.3 Store only token hashes

- Generate at least 32 random bytes for the URL token.
- Store `hash('sha256', $token)` and never the raw token.
- Hash the submitted token before lookup and use constant-time comparison
  where a direct comparison remains.
- Keep a one-hour expiration and make tokens single-use.
- Add a migration if the current hash column length or name changes.

### 4.4 Revoke authentication state

After a successful reset:

- change the password hash
- clear the reset token
- clear the remember-me token
- clear the stored session ID
- reject any existing session whose ID no longer matches the user record
- commit all changes once

### Tests and acceptance criteria

- Generated mail contains the canonical absolute URL.
- Existing and unknown users receive indistinguishable browser responses.
- The database never contains the raw reset token.
- Expired and reused tokens fail.
- Existing sessions and remember-me cookies fail after reset.

## Phase 5: Secure SMTP Configuration, Secrets, and Logs

### 5.1 Restore TLS verification

- Remove the PHPMailer `SMTPOptions` that disable peer and hostname checks.
- Treat verification failure as a configuration/certificate error.
- If a private CA is required, configure its CA bundle explicitly; never
  enable `allow_self_signed` in production.

### 5.2 Move credentials out of settings rows

- Configure SMTP through `MAILER_DSN` or individual environment/Symfony secret
  values.
- Remove the SMTP password from `site_settings` and from editable form input.
- Keep non-secret presentation settings, such as sender display name, in
  Settings only if administrators are expected to edit them.
- Document secret rotation and environment requirements in Installation.

### 5.3 Introduce structured logging

Install `symfony/monolog-bundle`.

- Write application and mail logs under `var/log`.
- Use channels such as `security`, `mail`, and `database`.
- Redact credentials, reset tokens, session IDs, recipient lists where not
  required, and message bodies.
- Remove `phpmailer.log` and application logs from `public`.
- Add retention/rotation configuration.

### Tests and acceptance criteria

- SMTP rejects an untrusted certificate.
- No credential exists in HTML, logs, or `site_settings`.
- No log file is reachable through the web server.
- Production logging does not contain message bodies or secrets.

## Phase 6: Migrate Authentication and Authorization to SecurityBundle

This phase replaces, rather than wraps indefinitely, the current custom
authentication/authorization implementation.

### 6.1 Add SecurityBundle

- Install `symfony/security-bundle`.
- Adapt `Users\Entity\User` to Symfony's user interfaces.
- Configure the Doctrine user provider and password hasher.
- Implement a form-login authenticator that preserves the canonical `/Login`
  workflow.
- Migrate remember-me, logout, login throttling, and access-denied behavior.

### 6.2 Replace permission loops with voters

- Add feature-specific voter attributes such as `PAGE_EDIT`,
  `SETTINGS_EDIT`, `MAIL_MANAGE`, and `INVOICE_MANAGE`.
- Map the existing role/permission records to voter decisions.
- Use controller authorization attributes or explicit `denyAccessUnlessGranted`
  calls.
- Remove the old `Authentication`, `AuthenticationListener`, and
  `Authorization` services after all routes are migrated.

### Tests and acceptance criteria

- Existing password hashes continue to work or upgrade transparently.
- All protected routes have explicit access tests.
- Login throttling, remember-me, logout, and session revocation work.
- There is one authentication system and one authorization system.

## Phase 7: Make Mail Queueing Transactional and Delivery Claimable

### 7.1 Make queue creation atomic

- Add Doctrine associations from scheduled mail to its batch and invoice where
  appropriate instead of requiring early ID-generating flushes.
- Create the batch, scheduled messages, recipients, attachments, and invoice
  status changes in one feature workflow and one transaction.
- Flush once after the full graph is valid.
- Add a uniqueness rule preventing the same invoice from being queued twice.

### 7.2 Adopt Symfony Mailer

Install `symfony/mailer` and replace PHPMailer behind the existing transport
boundary. Configure TLS through the DSN and keep peer verification enabled by
default.

Symfony reference:
[Mailer](https://symfony.com/doc/7.4/mailer.html).

### 7.3 Adopt Messenger for asynchronous delivery

Add:

- `symfony/messenger`
- `symfony/doctrine-messenger`

Use a specific `DeliverScheduledMail` message containing only the scheduled
mail ID. Register a cohesive `MailDelivery` class as the Messenger consumer;
do not create a generic application Handler layer.

Create the Messenger transport table through a migration with `auto_setup`
disabled in production.

Symfony reference:
[Messenger](https://symfony.com/doc/7.4/messenger.html).

### 7.4 Add an atomic delivery claim

Extend scheduled-mail state with:

- `processing`
- `sent`
- `failed`
- `delivery_unknown`
- attempt count and timestamps
- stable message ID
- optional claim token/worker ID

Claim with one conditional database update from `scheduled` to `processing`.
Only the successful claimant may send.

SMTP cannot guarantee exactly-once delivery when a process crashes after the
server accepts a message but before the database commit. Handle that ambiguity
explicitly:

- use a stable `Message-ID`
- mark uncertain attempts `delivery_unknown`
- do not automatically resend uncertain deliveries
- provide an authorized manual resolution/retry workflow

### Tests and acceptance criteria

- Concurrent workers cannot claim the same message.
- Queue creation rolls back completely on failure.
- Confirmed failures retry according to a bounded policy.
- An uncertain post-send crash does not trigger automatic duplicate delivery.
- Batch status is derived consistently from child message states.

## Phase 8: Introduce Exact Money Semantics

### 8.1 Choose one representation

Use integer cents plus an explicit currency (`EUR`) throughout PHP and invoice
storage. Do not use binary floats for arithmetic.

Add a small immutable `Core\Money\Money` value only if it centralizes parsing,
addition, comparison, and formatting. It must not become a generic DTO layer.

### 8.2 Migrate inputs and entities

- Parse Dutch/decimal form input into cents with validation.
- Change contract costs and invoice item prices to cents.
- Calculate totals through integer addition.
- Format cents at the view/PDF boundary.
- Add migrations with explicit rounding rules for existing decimals.
- Produce a before/after reconciliation report and abort on values that cannot
  be converted exactly.

### Tests and acceptance criteria

- `10.10 + 0.20` produces exactly `10.30`.
- Contract-to-invoice conversion preserves cents.
- Negative and malformed amounts are rejected.
- HTML and PDF output use the same formatting.
- Imported legacy financial totals reconcile before and after migration.

## Phase 9: Clean Names and Persistence Boundaries

Do this after transaction ownership is established; renaming unstable
responsibilities earlier would cause avoidable churn.

### 9.1 Remove repository flush methods

- Keep query, `persist`, and `remove` operations in repositories.
- Move final flush/transaction ownership into cohesive feature workflows.
- Avoid controller-owned multi-repository transactions.

### 9.2 Resolve only real naming collisions

Keep plain entity nouns under `Entity`. Apply targeted workflow names:

- `Activity\Activity` -> `Activity\ActivityLog`
- `Email\Entity\MailSchedule` -> `Email\Entity\ScheduledMail`
- `Email\Schedule\MailSchedule` -> `Email\Schedule\MailQueue`

Review `Email\Batch\MailBatch` and `Email\Template\MailTemplate` after their
workflows are narrowed. Prefer a precise capability or a plural collection
name over `Manager` or generic `Service`. Do not rename classes merely to add
suffixes.

### Acceptance criteria

- No PHP import uses `as` to distinguish a workflow from its entity.
- No repository exposes `flush()`.
- Each cross-repository mutation has one named transaction owner.

## Recommended Execution Order

1. Phase 0: minimal test foundation
2. Phase 1: private attachment storage
3. Phase 2: page authorization and HTML sanitization
4. Phase 3: atomic invoice item and activity writes
5. Phase 4: password reset
6. Phase 5: SMTP TLS, secrets, and Monolog
7. Phase 6: SecurityBundle
8. Phase 7: Mailer, Messenger, and delivery claims
9. Phase 8: exact money
10. Phase 9: names and transaction boundaries

Phases 1 through 5 are security/reliability work and should not wait for the
later architectural cleanups. Each phase should be independently deployable,
fully migrated, and leave no temporary compatibility layer behind.
