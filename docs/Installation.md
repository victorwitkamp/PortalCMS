# Installation / Deployment (IIS + PHP + MySQL)

This document describes how to deploy PortalCMS on IIS, including the file
permissions the application needs to actually run. Most of these were
discovered the hard way (500 errors, silently-missing PDFs, etc.) — treat
this list as required, not optional.

## Layout

The IIS site's physical path must point at the repo's `portal/` subfolder,
**not** the repo root. `config/`, `src/`, `vendor/` and `database/` should sit
as siblings one level above the site root so they are never web-accessible:

```
repo-root/
  config/
  database/
  src/
  vendor/
  portal/        <- IIS site physical path points here
```

If the repo root also needs to serve `.well-known/acme-challenge` (e.g. for
Let's Encrypt/win-acme HTTP-01 renewal) while the site root points at
`portal/`, add it back as an IIS virtual directory:

```powershell
& "$env:windir\System32\inetsrv\appcmd.exe" add vdir /app.name:"<site>/" `
    /path:"/.well-known" /physicalPath:"<repo-root>\.well-known"
```

## Build steps

```powershell
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

`npm run build` (`build/build-assets.mjs`) populates `portal/dist/` from
`node_modules` (vendor CSS/JS/fonts, copied via `cpy`) and `portal/dist/merged/`
(DataTables/FullCalendar bundles, concatenated inline — that part is
project-specific enough that no package fits it). It's not a bundler — none
of the app's own JS is compiled, only pre-built vendor files are
copied/concatenated. Re-run it after any `package.json` change. `npm run lint`
runs ESLint over `portal/includes/js`.

## Configuration

Copy `config/config.development.php.example` to `config/config.development.php`
and fill in `DB_HOST`/`DB_NAME`/`DB_USER`/`DB_PASS`, plus a random
`ENCRYPTION_KEY`/`HMAC_SALT`. Also required but **missing from the example
file**: `DEFAULT_CONTROLLER` (`'home'`) and `DEFAULT_ACTION` (`'index'`) —
the app throws a fatal error on every request without them.

## Database

Import a schema from `database/` (the most complete dump is under
`database/20191104/`), then seed `permissions`, `roles`, `role_perm`, `pages`,
and `mail_templates` — these tables ship with structure only, no rows, in the
repo's schema dumps. Do **not** assume `site_settings` or `users` need
seeding from the old dumps; those are environment-specific and should be set
up fresh per deployment.

## Required writable directories

The site's anonymous authentication identity — check with
`Get-WebConfiguration -Filter "/system.webServer/security/authentication/anonymousAuthentication" -PSPath "IIS:\Sites\<site>"`,
it was `IUSR` in this deployment, **not** the app pool identity — needs
Modify rights on:

| Path                          | Used for                                   |
|--------------------------------|---------------------------------------------|
| `portal/` (site root)          | `errors.log` (see below)                    |
| `portal/content/temp/`         | TCPDF font/image cache                      |
| `portal/content/attachments/`  | mail attachment uploads                     |
| `portal/content/logo/`         | site logo upload                            |
| `portal/content/invoices/`     | generated invoice PDFs — **create this dir manually, it does not exist in a fresh checkout** |

Grant with (repeat per directory, or grant once on `portal/` — permissions
are inherited by new subdirectories automatically):

```powershell
icacls "<site-root>\portal" /grant "IUSR:(OI)(CI)M" /T /Q
icacls "<site-root>\portal" /grant "IIS_IUSRS:(OI)(CI)M" /T /Q
icacls "<site-root>\portal" /grant "IIS AppPool\<poolname>:(OI)(CI)M" /T /Q
```

If a directory under `content/` is missing entirely (TCPDF/PHP will not
create it for you), you'll get errors like:

```
TCPDF ERROR: Unable to create output file: <path>\content\invoices\<file>.pdf
```

Create the directory — permissions are inherited automatically once the
parent (`portal/`) has been granted as above.

## Error logging

`config/error-reporting.php` sets `display_errors = 0` (do not change this —
see "IIS FastCGI notes" below) and logs to `<DOCUMENT_ROOT>/errors.log`. That
file is created on first write, so no manual step is needed beyond the
directory permissions above.

## IIS FastCGI notes

By default, PHP-CGI's `fastcgi.logging` setting echoes anything PHP logs
(including plain `E_WARNING`s, not just fatals) to stderr, and IIS's FastCGI
handler is commonly configured with `stderrMode="ReturnStdErrIn500"`, which
turns *any* PHP warning into an HTTP 500 — regardless of `display_errors`.

Do not "fix" this by setting `display_errors = 1`; that only exposes the
warning text instead of preventing the 500. The actual fix, applied without
touching the shared, server-wide `php.ini` (which would affect every other
PHP site on the box), is a **second, site-scoped FastCGI application**
pointed only at this site's handler mapping:

```powershell
$phpExe = "<path>\php-cgi.exe"
& appcmd.exe set config -section:system.webServer/fastCgi `
    /+"[fullPath='$phpExe',arguments='-d fastcgi.logging=0']" /commit:apphost
```

Then in this site's `portal/web.config`, override the `*.php` handler to use
that specific `fullPath|arguments` combination instead of the bare exe path
(see the existing `web.config` in this repo for the exact `<handlers>`
block). Other sites on the same box keep using the original, unmodified
FastCGI application and are unaffected.

## Tooling

- **PHP style**: `vendor/bin/php-cs-fixer fix` (config in `.php-cs-fixer.dist.php`,
  `@PSR12` plus a few extra rules). Run with `--dry-run --diff` to check
  without modifying files.
- **JS lint**: `npm run lint` (ESLint flat config in `eslint.config.js`),
  covers `portal/includes/js` — the only JS in the repo that isn't a
  third-party vendor bundle.
- **CI**: `.github/workflows/ci.yml` runs both of the above plus `npm run build`
  and a `php -l` syntax check on every push/PR. There is no automated test
  suite yet (no PHPUnit) — that's the next highest-value addition, since most
  bugs found in this app so far were only caught by manually clicking through
  it.
