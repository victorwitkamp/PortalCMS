# PortalCMS Architecture And Refactoring History

Updated: 2026-07-24.

This is the authoritative description of the implemented PortalCMS
architecture. It consolidates the former database proposal, database and
mapper migration trackers, class-structure plan, feature refactor, Symfony
runtime/session plan, and component backlog. Those intermediate documents
were removed after implementation; this file preserves the decisions and
technical history that still matter.

## Refactoring History

### Symfony components before FrameworkBundle

The application first adopted focused Symfony components while retaining its
legacy runtime: HttpFoundation for requests, responses, redirects, cookies,
and uploaded files; Routing for route attributes; Validator, Serializer, and
PropertyAccess for typed request input; DependencyInjection and HttpKernel for
composition and dispatch. This reduced global access before changing the
application's folder structure or runtime.

Generic `Data`/`Dto` objects and per-feature data factories were rejected.
Typed input classes are named for the operation they represent and are mapped
through the shared `RequestInputMapper`. Symfony Form remains unnecessary
while forms are rendered manually.

### Legacy database and Doctrine

The original database history was a collection of dated SQL snapshots and
incremental scripts. Its abandoned `version` table was not reliable, and two
same-date production schemas already differed, so a linear version-number
upgrade was unsafe. The implemented solution uses schema introspection:

- `Version20260724000002` is the single, forward-only baseline; it folds the
  former 24 migrations into a structured definition of all 19 application
  tables
- each table is reconciled independently, so the baseline supports empty,
  partial, known legacy, and already-current schemas
- the known `users.user_password_reset_hash` rename and historical timestamp
  type changes are handled explicitly
- contract date/time/money conversion, zero-date repair, aggregate keys,
  foreign keys, and required mail-recipient ownership are part of the same
  baseline
- unknown extra columns are preserved rather than dropped; this protects
  instance-specific additions such as the historical `roles.type` column

The former versions from `Version20260722000001` through
`Version20260724000001` remain part of the repository history, but are no
longer runtime migrations. Existing installations replace those 24 metadata
rows with the squashed version when the baseline runs.

Doctrine ORM was chosen over standalone Eloquent and Cycle ORM. Eloquent's
Active Record and magic property model did not provide the desired strongly
typed entities. Cycle had a suitable object model but a smaller ecosystem.
Doctrine provides mature Data Mapper semantics, typed attribute-mapped
entities, DBAL, migrations, change tracking, and custom repositories without
requiring DoctrineBundle.

MySQL DDL implicitly commits, so migrations are deliberately configured with
`all_or_nothing=false` and `transactional=false`. Treating DDL as
transactional creates invalid savepoints and cannot provide rollback
guarantees on MySQL.

### Mapper removal

The legacy persistence layer consisted of static raw-PDO `*Mapper` classes
returning inconsistent arrays and `stdClass` rows. It was replaced in stages:

- mapped state moved to typed Doctrine entities
- database queries and persistence boundaries moved to repositories
- real state transitions moved to entity methods
- construction of wide entities moved to feature factories
- request arrays moved to validated input classes
- mappers were retained temporarily as adapters, then removed after all
  callers used repositories

Entities do not query or flush the database. Their responsibility is mapped
state, invariants, associations, and state transitions. Repositories own
database access. Workflow classes coordinate multiple objects or external
effects.

### Feature-first structure

An intermediate global `Entity`, `Repository`, and `Application\Service`
layout was rejected because related code remained scattered. Business code is
now grouped under `src/Features/<Feature>`, while framework integration remains
in `src/Core` and shared templates remain in `src/View`. Entity names stay as
plain domain nouns because the `Entity` namespace already identifies their
ORM role.

Template files were moved out of Core and given purpose-specific names.
`Dashboard` became the `Home` feature, with page-data coordination in
`Home\Service\HomeService`. Generic view filenames such as `index.php`,
`edit.php`, and `table.php` were removed.

### FrameworkBundle and sessions

FrameworkBundle and Symfony Runtime now own kernel boot, the compiled
container, controller resolution, routes, exception events, sessions, and the
web/console lifecycle. The custom container factory, router loader, filename
dispatcher, session facade, CSRF helper, and read-time XSS filtering were
removed.

HttpFoundation native sessions use `PORTALCMSSESSID`, Secure auto-detection,
HttpOnly, SameSite=Lax, a 1,800-second lifetime, and PHP strict session mode.
FlashBag owns one-time feedback. Security CSRF uses distinct tokens for login,
username changes, and password changes. Plates remains the HTML template
engine; Twig, SecurityBundle, DoctrineBundle, and Symfony Form were not added.

### Database archive consolidation

The legacy SQL archive was removed after its schema knowledge had been encoded
in the guarded migrations and import tooling. `db/import.php` is now the
single supported import path for a dump from another PortalCMS instance. It
redirects embedded database-name statements into a newly created isolated
database, handles the historical `band_contracts` name, and applies the
single squashed baseline.

The importer was validated against a real October 2023 backup containing all
19 tables and application data. That test exposed and fixed Dutch
`DD-MM-YYYY` contract dates, stale attachment references, orphaned mail
recipients, and MySQL's invalid nested-savepoint behavior. The backup is not
stored in the repository because it contains production data.

## Current Structure

Application code now has three roots:

```text
src/
  Core/
  Features/
  View/
```

`Core` owns shared runtime and framework integration. `View` owns shared,
application-wide HTML templates. Business code and feature-owned templates are
grouped by feature:

```text
src/Features/
  Activity/
  Contracts/
  Email/
  Events/
  Home/
  Invoices/
  Members/
  Pages/
  Products/
  Settings/
  Users/
```

Shared templates have purpose-specific names and contain no PHP classes:

```text
src/View/
  Error/
    ErrorPage.php
    PermissionDeniedPage.php
  Layout/
    ApplicationLayout.php
    AuthenticationLayout.php
  Partials/
    FlashMessages.php
    Footer.php
    PrimaryNavigation.php
    PrimaryNavigationMenu.php
```

The Home feature separates HTTP handling, page-data coordination, and
feature-owned templates:

```text
src/Features/Home/
  Controller/HomeController.php
  Service/HomeService.php
  View/Templates/
    HomePage.php
    Layout/
    Widget/
```

The old global `Controllers`, `Entity`, `Repository`, and `Modules` trees are
removed. The former mixed view tree has been split between shared templates in
`src/View` and feature-owned templates. Composer now uses only PSR-4
autoloading:

```text
PortalCMS\         -> src/
DoctrineMigrations\ -> db/migrations/
```

## Naming Rules

- Doctrine entities use a plain domain noun below a feature's `Entity`
  namespace, such as `Features\Invoices\Entity\Invoice`.
- The `Entity` namespace identifies ORM classes. An `Entity` suffix is not
  repeated on every class.
- Repositories are concrete Doctrine custom repositories named
  `<Entity>Repository`.
- Factories exist only for construction that maps many fields, derives
  defaults, or creates child objects.
- Input classes are named for the form or operation they represent, such as
  `CreateInvoicesInput`. There are no generic `Data` or `Dto` classes.
- A cohesive workflow may use the same noun as its entity when its role
  namespace makes the distinction explicit. For example:
  `Email\Entity\MailSchedule` is persisted state and
  `Email\Schedule\MailSchedule` coordinates scheduling.
- There is no generic application-wide `Service`, `Handler`, `Query`,
  `Manager`, `Helper`, or `Model` layer. A narrowly scoped feature service is
  permitted when it coordinates data for one cohesive feature and no more
  precise role name applies. `Home\Service\HomeService` is the current
  example.
- Interfaces are used only at replaceable external boundaries. The current
  example is `Email\Transport\MailTransport`.
- Full-page templates use a descriptive `*Page.php` name. Reusable template
  fragments live in `Partials`, feature layout variants in `Layout`, and home
  widgets in `Widget`. Generic template names such as `index.php`, `edit.php`,
  and `table.php` are not used.

## Responsibility Rules

Entities:

- define Doctrine mapping and associations
- own state transitions and aggregate child collections
- do not query a database, read a request, render output, or send email

Repositories:

- extend `Doctrine\ORM\EntityRepository`
- contain queries and Doctrine `persist`, `remove`, and `flush` boundaries
- do not implement entity state changes, rendering, request parsing, or
  cross-feature workflows

Factories:

- construct or update complex entities from typed inputs
- do not access Doctrine or flush

Controllers:

- define explicit Symfony routes and HTTP methods
- receive `HttpFoundation\Request`
- map substantial form bodies to typed input classes
- authorize and coordinate entities, repositories, factories, workflows, and
  views
- return `HttpFoundation\Response`

Views:

- render already-loaded data
- never call repositories
- keep shared layouts, errors, and partials under `src/View`
- keep feature-owned Plates templates under
  `src/Features/<Feature>/View/Templates`
- keep rendering classes in the owning code namespace, including
  `Core\View\TemplateRenderer` and `Invoices\View\InvoicePdf`

## Aggregate Boundaries

| Feature | Repository roots | Owned state |
|---|---|---|
| Activity | `Activity` | none |
| Contracts | `Contract` | contract fields |
| Email | `MailSchedule`, `MailBatch`, `MailTemplate` | recipients and attachments |
| Events | `Event` | none |
| Invoices | `Invoice` | `InvoiceItem` |
| Members | `Member` | member fields |
| Pages | `Page` | none |
| Products | `Product` | none |
| Settings | `SiteSetting` | none |
| Users | `User`, `Role`, `Permission` | user-role and role-permission associations |

`InvoiceItem`, `MailRecipient`, and `MailAttachment` intentionally have no
custom repositories. Their aggregate roots persist and remove them through
Doctrine cascade and orphan-removal mappings.

## Runtime

`public/index.php` boots `Core\Kernel` through Symfony Runtime. FrameworkBundle
builds the compiled container, creates the HttpFoundation request, dispatches
it, sends the response, and terminates the kernel. `bin/console` uses the same
kernel and supports Symfony environment/debug options.

The runtime uses:

- Symfony FrameworkBundle as the runtime and composition root
- Symfony Runtime for web and console entrypoints
- Symfony HttpKernel for request dispatch and exception events
- Symfony Routing attributes and URL generation with explicit route methods
- Symfony HttpFoundation requests, responses, redirects, files, cookies,
  native sessions, and flash messages
- Symfony Security CSRF with action-specific token IDs
- Symfony Serializer, Validator, and PropertyAccess for typed form input
- Doctrine ORM custom repositories and one shared entity manager
- Plates for HTML views
- TCPDF for the invoice PDF view

`Core\Kernel` extends the FrameworkBundle kernel and uses `MicroKernelTrait`.
PHP configuration in `config/packages/framework.php`, `config/services.php`,
and `config/routes.php` owns framework integration. The legacy filename
dispatcher, manual container factory, custom route loader, and static session
layer are removed.

Doctrine discovers only `src/Features/*/Entity`. The migrations metadata table
is excluded from ORM schema comparisons.

## Class And Method Catalog

The catalog lists methods declared by PortalCMS classes. Repository classes
also inherit Doctrine's `find`, `findAll`, `findBy`, `findOneBy`, and `count`.
Constructors on controllers and workflows receive the dependencies described
by their class responsibility.

### Core

- `Config\Config`: `get()` reads application configuration.
- `Controller\AbstractController`: `render()`, `redirectToRoute()`,
  `redirectToLocalPath()`, `session()`, `addFlash()`,
  `notFoundResponse()`, and `forbiddenResponse()` provide shared response
  helpers.
- `Controller\ErrorController`: `notFound()`, `permissionError()`,
  `exception()` render HTTP error responses.
- `Database\DoctrineConfiguration`: `createEntityManager()` configures
  feature metadata and the MySQL connection.
- `Database\Migrations\AbstractGuardedMigration`: `schemaManager()`,
  `tableExists()`, `ensureTable()`, `columnExists()`, `ensureColumn()`,
  `ensureIndex()`, `ensureForeignKey()`, and `renameColumnIfNeeded()` support
  idempotent legacy reconciliation in the squashed baseline.
- `Http\ExceptionListener`: `onKernelException()` and
  `getSubscribedEvents()` convert exceptions to responses.
- `Http\InvalidInputException`: `fromViolations()` and `errors()` preserve
  field validation errors.
- `Http\RemoteAddress`: `getIpAddress()` resolves the request IP.
- `Http\RequestInputMapper`: `map()`, `mapQuery()`, and `mapArray()` normalize,
  deserialize, and validate typed inputs.
- `Kernel`: `getProjectDir()` identifies the project root; FrameworkBundle
  provides boot, container, routing, session, and request lifecycle behavior.
- `Security\Encryption`: `encrypt()` and `decrypt()` wrap configured
  encryption.
- `View\TemplateRenderer`: `render()` returns HTML and `response()` returns an
  HTML response. It resolves site context and request-local flash/navigation
  values lazily.
- `View\HTMLEntities`: `encode()` and `decode()` transform HTML entities.
- `View\Text`: `get()` reads translated/static UI text.
- `db/import.php` contains the standalone
  `LegacyDatabaseImporter::run()` CLI workflow for isolated legacy dump
  imports. It is deployment tooling and is intentionally outside the
  application service container.

### Activity

- `Activity\Entity\Activity`: `record()` creates an activity entry.
- `Activity\Repository\ActivityRepository`: `findRecent()`, `save()`, and
  `flush()` query and persist activity.
- `Activity\Activity`: `load()` retrieves recent activity and `add()` records
  activity with user and remote-address context.

### Contracts

- `Contracts\Entity\Contract`: mapped contract state; construction initializes
  database-managed timestamps.
- `Contracts\Input\ContractInput`: typed and validated contract form fields.
- `Contracts\Factory\ContractFactory`: `create()` builds a contract and
  `update()` applies input to an existing contract.
- `Contracts\Repository\ContractRepository`: `findAllOrdered()`, `save()`,
  `remove()`, and `flush()`.
- `Contracts\Controller\ContractsController`: `index()`, `new()`, `create()`,
  `edit()`, `update()`, `delete()`, and `details()`.

### Home

- `Home\Service\HomeService`: `data()` loads settings, page content, events,
  and permission-dependent data for the home page.
- `Home\Controller\HomeController`: `index()` renders the home page.

### Email

- `Email\Entity\MailSchedule`: `create()`, `addRecipient()`,
  `addAttachment()`, `copyAttachment()`, `recipients()`, `attachments()`,
  `recipientsOfType()`, `isScheduled()`, `markFailed()`, and `markSent()`.
- `Email\Entity\MailRecipient`: `address()` changes recipient details. The
  constructor attaches a new recipient to its schedule.
- `Email\Entity\MailAttachment`: `attachToMail()`, `attachToTemplate()`,
  `describeFile()`, and `copyToMail()`.
- `Email\Entity\MailBatch`: `create()`, `markReady()`, `markExecuted()`, and
  `useTemplate()`.
- `Email\Entity\MailTemplate`: `create()`, `rename()`, `changeSubject()`,
  `changeBody()`, `changeType()`, `markSystemTemplate()`, `addAttachment()`,
  `removeAttachment()`, `attachments()`, and `isSystem()`.
- `Email\Repository\MailScheduleRepository`: `findAllOrdered()`,
  `findHistory()`, `findByBatchId()`, `findScheduledByBatchId()`, `save()`,
  `remove()`, and `flush()`.
- `Email\Repository\MailBatchRepository`: `findAllOrdered()`,
  `countMessages()`, `save()`, `remove()`, and `flush()`.
- `Email\Repository\MailTemplateRepository`: `findAllOrdered()`,
  `findByType()`, `findSystem()`, `save()`, `remove()`, `findAttachment()`,
  and `flush()`.
- `Email\Schedule\MailSchedule`: `create()`, `queue()`, `flush()`,
  `findDateSent()`, `delete()`, `send()`, `sendScheduled()`, and
  `createFromMemberTemplate()`.
- `Email\Batch\MailBatch`: `create()`, `send()`, and `delete()`.
- `Email\Template\MailTemplate`: `system()`, `create()`, `update()`,
  `delete()`, `uploadAttachment()`, and `deleteAttachments()`.
- `Email\Message\EmailMessage`: constructor-only typed outgoing message value.
- `Email\Recipient\EmailRecipient`: constructor-only typed recipient value.
- `Email\Transport\MailTransport`: `send()` and `lastError()` define the
  transport boundary.
- `Email\SMTP\SMTPConfiguration`: constructor-built, typed SMTP settings.
- `Email\SMTP\SMTPTransport`: `send()` and `lastError()` implement the
  transport with PHPMailer.
- `Email\Input\MailTemplateInput`: validated `subject` and `body`.
- `Email\Input\ScheduleMemberMailInput`: validated `templateid` and normalized
  integer `recipients`.
- `Email\Controller\EmailController`: `batches()`, `messages()`, `history()`,
  `details()`, `viewTemplates()`, `editTemplate()`, `newTemplate()`,
  `generate()`, `generateMember()`, `selectMemberYear()`, `scheduleMembers()`,
  `createTemplate()`, `updateTemplate()`, `deleteTemplate()`,
  `uploadAttachment()`, `deleteAttachments()`, `sendMessages()`,
  `deleteMessages()`, `sendBatches()`, and `deleteBatches()`.

### Events

- `Events\Entity\Event`: `create()`, `update()`, `reschedule()`, `rename()`,
  `changeDescription()`, and `setStatus()`.
- `Events\Input\EventInput`: typed title, start/end times, description, and
  status.
- `Events\Repository\EventRepository`: `findBetween()`, `findUpcoming()`,
  `save()`, `remove()`, and `flush()`.
- `Events\Controller\EventsController`: `index()`, `add()`, `create()`,
  `edit()`, `update()`, `delete()`, `details()`, `loadCalendarEvents()`, and
  `reschedule()`.

### Invoices

- `Invoices\Entity\Invoice`: `addItem()`, `removeItem()`, `items()`, `total()`,
  `markPdfWritten()`, `markMailed()`, `isDraft()`, `hasPdf()`, and
  `isMailed()`.
- `Invoices\Entity\InvoiceItem`: constructor creates an owned item;
  `rename()` and `changePrice()` update it.
- `Invoices\Input\CreateInvoicesInput`: validated year, month, normalized
  contract IDs, and invoice date.
- `Invoices\Input\InvoiceItemInput`: validated item name and non-negative
  price.
- `Invoices\Factory\InvoiceFactory`: `createForContract()` derives the invoice
  number and default room/storage items.
- `Invoices\Repository\InvoiceRepository`: `findByNumber()`,
  `findByContractId()`, `findByContractIdAndYear()`, `findAllOrdered()`,
  `findByYear()`, `findYears()`, `countByYear()`, `countAll()`, `findItem()`,
  `save()`, `remove()`, and `flush()`.
- `Invoices\View\InvoicePdf`: `render()`, `write()`, `remove()`, and `path()`.
- `Invoices\Controller\InvoicesController`: `index()`, `add()`, `create()`,
  `details()`, `delete()`, `addItem()`, `deleteItem()`, `renderPdf()`,
  `writePdf()`, and `scheduleMail()`.

### Members

- `Members\Entity\Member`: mapped member state; construction initializes
  database-managed timestamps.
- `Members\Input\MemberInput`: typed member, address, contact, preference, and
  payment form fields.
- `Members\Factory\MemberFactory`: `create()`, `update()`, and
  `copyForYear()`.
- `Members\Repository\MemberRepository`: `findRows()`,
  `findRowsWithEmail()`, `findYears()`, `findPaymentTypes()`, `countByYear()`,
  `emailExistsForYear()`, `save()`, `remove()`, and `flush()`.
- `Members\Controller\MembershipController`: `index()`, `new()`, `create()`,
  `edit()`, `update()`, `delete()`, `status()`, `newFromExisting()`, `copy()`,
  and `profile()`.

### Pages

- `Pages\Entity\Page`: `changeContent()` owns the content mutation.
- `Pages\Input\PageInput`: validated page content.
- `Pages\Repository\PageRepository`: inherited `find()` loads pages and
  `flush()` commits entity changes.
- `Pages\Controller\PageController`: `edit()` and `update()`.

### Products

- `Products\Entity\Product`: mapped product state. The feature currently has
  no active controller workflow.
- `Products\Repository\ProductRepository`: `findAllOrdered()`, `save()`,
  `remove()`, and `flush()`.

### Settings

- `Settings\Entity\SiteSetting`: `changeValue()` owns a setting mutation.
- `Settings\Repository\SiteSettingRepository`: `findSetting()`,
  `findValue()`, and `flush()`.
- `Settings\Input\SiteSettingsInput`: typed nullable setting values and
  `values()` for repository-independent form extraction.
- `Settings\SiteSetting`: `save()`, `get()`, `values()`, `uploadLogo()`, and
  `error()` coordinate persisted settings and local logo storage.
- `Settings\Controller\SettingsController`: `siteSettings()`, `save()`,
  `activity()`, `logo()`, `uploadLogo()`, and `debug()`.

### Users

- `Users\Entity\User`: `create()`, `changeUsername()`,
  `changePasswordHash()`, `setRememberMeToken()`, `setSessionId()`,
  `connectFacebook()`, `recordFailedLogin()`, `resetFailedLogins()`,
  `markLoggedIn()`, `isLoginBlocked()`, `setPasswordResetToken()`,
  `clearPasswordResetToken()`, `addRole()`, `hasRole()`, and `removeRole()`.
- `Users\Entity\Role`: `create()`, `rename()`, `addPermission()`,
  `hasPermission()`, and `removePermission()`.
- `Users\Entity\Permission`: `rename()`.
- `Users\Repository\UserRepository`: `usernameExists()`, `emailExists()`,
  `findByLogin()`, `findByRememberToken()`, `findByFacebookId()`,
  `findByUsernameOrEmail()`, `findByResetToken()`, `findAllOrdered()`,
  `findRoles()`, `save()`, `remove()`, and `flush()`.
- `Users\Repository\RoleRepository`: `findAllOrdered()`, `findPermissions()`,
  `findSelectablePermissions()`, `isAssignedToUsers()`, `save()`, `remove()`,
  and `flush()`.
- `Users\Repository\PermissionRepository`: `findAllOrdered()` and
  `findByUserId()`.
- `Users\Input\CreateUserInput`: validated username, email, and password.
- `Users\Authentication\Authentication`: `isLoggedIn()`, `userId()`, `login()`,
  `loginFromRememberMeCookie()`, `loginWithFacebook()`, `logout()`, and
  `takeResponseCookie()`.
- `Users\Authentication\AuthenticationListener`: `onKernelRequest()`,
  `onKernelResponse()`, and `getSubscribedEvents()` protect routes and apply
  remember-me cookies.
- `Users\Authorization\Authorization`: `hasPermission()`.
- `Users\Password`: `change()`, `verify()`, `hash()`, and
  `isStrongEnough()`.
- `Users\PasswordReset`: `request()`, `verify()`, `reset()`, and `error()`.
- `Users\Controller\LoginController`: `index()`, `login()`,
  `requestPasswordReset()`, `submitPasswordResetRequest()`,
  `passwordReset()`, `resetPassword()`, and `activate()`.
- `Users\Controller\LogoutController`: `index()`.
- `Users\Controller\AccountController`: `index()`, `changeUsername()`,
  `changePassword()`, and `clearFacebook()`.
- `Users\Controller\ProfileController`: `index()`.
- `Users\Controller\UserManagementController`: `users()`, `profile()`,
  `roles()`, `role()`, `addUser()`, `createUser()`, `deleteUser()`,
  `createRole()`, `deleteRole()`, `assignRole()`, `unassignRole()`,
  `assignPermission()`, and `unassignPermission()`.

## Database And Imports

There is one Doctrine migration under `db/migrations`:
`Version20260724000002`. This irreversible, guarded baseline creates the
current 19-table application schema on an empty database and reconciles known
legacy schemas in place. It contains the behavior previously spread across 24
migrations, including typed contract fields, auth timestamp repair, missing
primary keys, foreign keys, and required mail-recipient ownership.

The configured database is at `Version20260724000002`: one version is
available, one is executed, and none are unavailable or pending.

Use the standalone importer for a database dump from another instance:

```powershell
php db/import.php `
  --dump="C:\backups\instance.sql" `
  --database=portalcms_import
```

The target name is required, must not already exist, and cannot equal the
database configured in `config/config.development.php`. The importer:

1. creates the isolated target database
2. rewrites dump-level `CREATE DATABASE` and `USE` directives to that target
3. imports through the native MySQL client using a temporary protected option
   file, keeping credentials out of the process command
4. applies `Version20260724000002` and prints final migration status

Pass `--mysql-bin=<path>` if `mysql` is not discoverable. The importer never
replaces an existing database and retains a failed target for inspection.

Legacy instance data is retained except where a new integrity rule makes a
row meaningless: stale `mail_attachments` associations are set to `NULL`, and
`mail_recipients` without an existing parent schedule are removed. Contract
dates in ISO, `DD-MM-YYYY`, or `DD/MM/YYYY` form are normalized before
conversion to `DATE`; unknown formats abort with a clear error.

The removed SQL archive contained snapshots, unsafe environment-specific seed
users/settings, and branded template bodies. Those are not universal
migrations. An imported instance keeps its own data; a new empty installation
must seed its environment-specific settings, initial authorization data,
pages, and required system mail templates as described in
`docs/Installation.md`.

## Validation Record

Completed on 2026-07-24:

- Composer strict validation
- optimized PSR-4 autoload generation
- PHP lint across application and migration PHP files
- PHP CS Fixer validation
- ESLint and asset build
- Symfony container compilation
- FrameworkBundle console boot in development and production modes
- route loading: 130 explicit routes
- Doctrine mapping validation: 17 entities
- repository resolution: 14 custom aggregate repositories and 3 default
  owned-entity repositories
- configured database status: one migration executed and available, none
  unavailable or pending
- clean-database execution of the squashed baseline
- restore and squash of a fresh configured-database backup, with all 19
  application-table row counts preserved
- isolated import of a real October 2023 instance backup through the squashed
  baseline
- successful hydration of all 17 mapped entity types that had fixture rows in
  the imported database
- isolated import of an older partial schema through the guarded
  reconciliation behavior
- typed input mapping and validation, including numeric array normalization
- authenticated read-only route sweep across every feature with available
  fixture data
- unauthenticated redirect and invalid remember-me-cookie checks
- lazy public-session behavior, explicit session cookie attributes, one-time
  flash consumption, and invalid CSRF rejection
- invoice PDF render check with a valid `%PDF` header
- static checks for legacy namespaces, mapper classes, static repositories,
  repository access in views, and PSR-4 path/case mismatches
- resolution of 120 literal Plates template references
- authenticated rendering of 27 primary GET routes and 7 fixture-backed
  edit/detail routes after the view reorganization
- source filename audit confirming PascalCase, purpose-specific PHP filenames;
  `public/index.php` remains the conventional web front controller

No application form/write workflow was executed against the configured
database. The explicitly requested final schema migration was applied there.

## Deployment

Before deploying:

1. back up the database
2. run `vendor/bin/doctrine-migrations migrate --no-interaction`
3. verify the migration reaches `Version20260724000002` with one executed and
   one available version
4. exercise login, one representative write per feature, invoice PDF storage,
   and SMTP delivery in the target environment

For an instance transfer, export the source with `mysqldump`, run
`db/import.php` against a new target name, verify the imported site, and
only then point the deployment configuration at that database. Do not merge a
dump into the configured live database.

## Optional Follow-up Work

- DoctrineBundle and DoctrineMigrationsBundle adoption
- Symfony Form
- SecurityBundle and replacement of the application authentication model
- Twig; Plates is currently sufficient and remains the only template engine
- PSR-3 logging with Monolog
- Flysystem for attachment, logo, and invoice storage
- Symfony Mailer as a possible PHPMailer replacement
- Dotenv/Config for environment and secret loading
- BrowserKit, PHPUnit, and automated kernel/browser coverage
- repository interfaces without a second persistence implementation
- one command or handler class per action
- status enum files
- remote PDF or attachment storage
- a separate read-model class for every view
- activation behavior, which remains a rendered legacy placeholder
