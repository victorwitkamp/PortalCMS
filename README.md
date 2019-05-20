# PortalCMS

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/victorwitkamp/portal/badges/quality-score.png?b=master&s=6b94a234857427ce86a5e8aa8e1b15202260758f)](https://scrutinizer-ci.com/g/victorwitkamp/portal/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/victorwitkamp/portal/badges/build.png?b=master&s=b2598efca3c8ece2d5b6ed1356cb43bdfdffbc1f)](https://scrutinizer-ci.com/g/victorwitkamp/portal/build-status/master)

## About

A CMS-like PHP application/portal that I'm creation for a small non-profit music venue by local volunteers. The venue also has two practice rooms for bands to rent. Regular visitors can influence this community/organisation by becoming a member which requires paying a small yearly membership fee. Status: development started a few months ago. I'm revising the code every now and then whenever I learn something new. Ideas/suggestions are more then welcome. I'll try to add a roadmap and more details about the features soon.

## Usage/features

- User accounts
  - My Account
    - Change password
    - Change username
    - Change e-mailaddress (planned)
    - Assign Facebook account to current user
  - Registration
    - Registration form
    - Activate account by clicking URL that was received by e-mail
    - Activate account manually by specifying the code received by e-mail
  - Reset password
    - Request password reset form
  - Login
    - Login with username or e-mailaddress + password
    - Login with Facebook

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

## Installation

## Requirements

I'm currently developing this on an IIS webserver with PHP 7.2.12 and a local MySQL server.

## Credits

Used a lot from <https://github.com/panique/huge> but changed it a bit. It isn't fully OOP anymore a I changed the way it handled views (isn't very practical when developing on IIS). May change this again in the future.
