# PortalCMS

[![Build](https://github.com/victorwitkamp/PortalCMS/actions/workflows/build.yml/badge.svg)](https://github.com/victorwitkamp/PortalCMS/actions/workflows/build.yml)

## About

A CMS-like PHP application/portal that I'm creation for a small non-profit music venue by local volunteers. The venue also has two practice rooms for bands to rent. Regular visitors can influence this community/organisation by becoming a member which requires paying a small yearly membership fee. Status: development started a few months ago. I'm revising the code every now and then whenever I learn something new. Ideas/suggestions are more then welcome. I'll try to add a roadmap and more details about the features soon.

## Usage/features

- User accounts
  - My Account
    - Change password
    - Change username
    - Change e-mailaddress (planned)
    - Assign Facebook account to current user
  - Registration (disabled. need to verify/implement this again. planned.)
    - Registration form
    - Activate account by clicking the URL that was received by e-mail
    - Activate account manually by specifying the code received by e-mail
  - Reset password
    - A form where the user can request a reset of the password by entering a known e-mailaddress.
    - User clicks on the URL in the e-mail that was sent to the known e-mailaddress.
    - User will be redirected to a form where they can provide a new password.
  - Login
    - Login with username or e-mailaddress + password
    - Login with Facebook
  - User management
    - User roles/permissions
      - Roles can be assigned/unassigned to a user. Multiple roles allowed.
      - Roles can be created/deleted from the Role Management page.
      - Permissions can be assigned/unassigned to a role. The names of the permissions are currently static and cannot be changed.

- Membership: manage the yearly membership.
  - Add new members or edit existing members by filling in a form.
  - Delete members
- Band contracts
  - Manage the contracts for the practice rooms. (broken/todo)
  - Creating PDF invoices (in development)
- Event calendar
  - Create/edit/delete events
- Site settings
  - Settings to modify the name of the site (which changes the name for the whole site incl. the e-mails that are sent) and to modify the look/feel by changing the theme and enabling widgets.
- Mail scheduler:
  - Send new or template based emails to members or site-users.

## Requirements

This may work on other configurations as well.

- IIS webserver
- PHP 8.2+
- MySQL 8.0+
- Node.js / npm
- Composer

## Installation

See [docs/Installation.md](docs/Installation.md) for the full deployment guide
(IIS setup, required file permissions, database seeding). Quick version:

```
composer install
npm install
npm run build
vendor/bin/doctrine-migrations migrate --no-interaction
```

The current code organization and class API are documented in
[docs/Architecture.md](docs/Architecture.md).

## Credits

Used a lot from <https://github.com/panique/huge> but changed it a bit. It isn't fully OOP anymore a I changed the way it handled views (isn't very practical when developing on IIS). May change this again in the future.
