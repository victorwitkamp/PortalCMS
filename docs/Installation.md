# Installation / Deployment (IIS + PHP + MySQL)

This document describes how to deploy PortalCMS on IIS, including the file
permissions the application needs to actually run. Most of these were
discovered the hard way (500 errors, silently-missing PDFs, etc.) — treat
this list as required, not optional.

## Layout

The IIS site's physical path must point at the repo's `public/` subfolder,
**not** the repo root. `config/`, `src/`, `vendor/` and `db/` should sit
as siblings one level above the site root so they are never web-accessible:

```
repo-root/
  bin/
  config/
  db/
  src/
  vendor/
  var/
  public/        <- IIS site physical path points here
```

If the repo root also needs to serve `.well-known/acme-challenge` (e.g. for
Let's Encrypt/win-acme HTTP-01 renewal) while the site root points at
`public/`, add it back as an IIS virtual directory:

```powershell
& "$env:windir\System32\inetsrv\appcmd.exe" add vdir /app.name:"<site>/" `
    /path:"/.well-known" /physicalPath:"<repo-root>\.well-known"
```

## Build steps

```powershell
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=production --no-debug
npm install
npm run build
```

`npm run build` (`build/build-assets.mjs`) populates `public/dist/` from
`node_modules` (vendor CSS/JS/fonts, copied via `cpy`) and `public/dist/merged/`
(DataTables/FullCalendar bundles, concatenated inline — that part is
project-specific enough that no package fits it). It's not a bundler — none
of the app's own JS is compiled, only pre-built vendor files are
copied/concatenated. Re-run it after any `package.json` change. `npm run lint`
runs ESLint over `public/includes/js`.

## Configuration

Copy `config/config.development.php.example` to `config/config.development.php`
and fill in `DB_HOST`/`DB_NAME`/`DB_USER`/`DB_PASS`, plus a random
`ENCRYPTION_KEY`/`HMAC_SALT`.

Set `APPLICATION_ENV=production` in the IIS FastCGI environment for production
deployments. `bin/console --env=production --no-debug` can override the
environment for deployment commands.

FrameworkBundle uses the explicit `PORTALCMSSESSID` cookie. Deploying this
version signs users out once because older session cookies are intentionally
not reused.

## Database

Create an empty MySQL 8 database named by `DB_NAME`, then run:

```powershell
vendor\bin\doctrine-migrations.bat migrate --no-interaction
```

On Linux:

```bash
vendor/bin/doctrine-migrations migrate --no-interaction
```

The guarded baseline creates an empty database or reconciles a known legacy
schema directly with the current schema. After migration, seed the
environment-specific `site_settings`,
initial user/role/permission records, pages, and required system
`mail_templates`.

### Importing another PortalCMS instance

Do not replay the old dated SQL files or import a dump over the configured
database. Export the source instance, then use the single supported importer
with a new target database name:

```powershell
php db/import.php `
  --dump="C:\backups\instance.sql" `
  --database=portalcms_import
```

The target must not exist and may not be the `DB_NAME` from
`config/config.development.php`. The importer safely redirects any embedded
`CREATE DATABASE`/`USE` statements, imports with the native MySQL client,
and applies the squashed Doctrine baseline. It prints migration status when
complete; `Executed`, `Available`, and the current version must each identify
the single baseline, while `New` and `Executed Unavailable` must be `0`.

Use `--mysql-bin="C:\path\to\mysql.exe"` when the MySQL client is not on
`PATH`.

The importer preserves the failed target database for diagnosis. Once a full
import has been tested through the application, update `DB_NAME` during the
deployment switch. The source dump may contain personal data and must remain
outside the repository.

## Required writable directories

The site's anonymous authentication identity — check with
`Get-WebConfiguration -Filter "/system.webServer/security/authentication/anonymousAuthentication" -PSPath "IIS:\Sites\<site>"`,
it was `IUSR` in this deployment, **not** the app pool identity — needs
Modify rights on:

| Path                          | Used for                                   |
|--------------------------------|---------------------------------------------|
| `public/` (site root)          | `errors.log` (see below)                    |
| `public/content/temp/`         | TCPDF font/image cache                      |
| `public/content/attachments/`  | mail attachment uploads                     |
| `public/content/logo/`         | site logo upload                            |
| `public/content/invoices/`     | generated invoice PDFs — **create this dir manually, it does not exist in a fresh checkout** |
| `var/cache/`                   | compiled FrameworkBundle container and routes |
| `var/log/`                     | FrameworkBundle-compatible runtime log directory |

Grant with (repeat per directory, or grant once on `public/` — permissions
are inherited by new subdirectories automatically):

```powershell
icacls "<site-root>\public" /grant "IUSR:(OI)(CI)M" /T /Q
icacls "<site-root>\public" /grant "IIS_IUSRS:(OI)(CI)M" /T /Q
icacls "<site-root>\public" /grant "IIS AppPool\<poolname>:(OI)(CI)M" /T /Q
icacls "<site-root>\var" /grant "IUSR:(OI)(CI)M" /T /Q
icacls "<site-root>\var" /grant "IIS_IUSRS:(OI)(CI)M" /T /Q
icacls "<site-root>\var" /grant "IIS AppPool\<poolname>:(OI)(CI)M" /T /Q
```

If a directory under `content/` is missing entirely (TCPDF/PHP will not
create it for you), you'll get errors like:

```
TCPDF ERROR: Unable to create output file: <path>\content\invoices\<file>.pdf
```

Create the directory — permissions are inherited automatically once the
parent (`public/`) has been granted as above.

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
    /+"[fullPath='$phpExe',arguments='-d fastcgi.logging=0 -d session.use_strict_mode=1']" /commit:apphost
```

Then in this site's `public/web.config`, override the `*.php` handler to use
that specific `fullPath|arguments` combination instead of the bare exe path
(see the existing `web.config` in this repo for the exact `<handlers>`
block). Other sites on the same box keep using the original, unmodified
FastCGI application and are unaffected.

`session.use_strict_mode=1` is a PHP runtime setting and must remain enabled in
the site-scoped FastCGI application. FrameworkBundle configures the remaining
session properties: native storage, a 1,800-second garbage-collection
lifetime, cookie path `/`, Secure auto-detection, HttpOnly, and SameSite=Lax.

## Tooling

- **PHP style**: `vendor/bin/php-cs-fixer fix` (config in `.php-cs-fixer.dist.php`,
  `@PSR12` plus a few extra rules). Run with `--dry-run --diff` to check
  without modifying files.
- **JS lint**: `npm run lint` (ESLint flat config in `eslint.config.js`),
  covers `public/includes/js` — the only JS in the repo that isn't a
  third-party vendor bundle.
- **CI**: `.github/workflows/build.yml` validates Composer, installs
  production dependencies, checks PHP syntax, runs `npm run build`, and
  verifies representative build output on every push/PR. There is no PHPUnit
  suite yet.
