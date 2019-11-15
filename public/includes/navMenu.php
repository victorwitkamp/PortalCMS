<?php

use PortalCMS\Core\View\Text;

?>
<li class="nav-item dropdown">
    <a class="nav-link" href="/home"><?= Text::get('NAV_TITLE_HOME') ?></a>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?= Text::get('NAV_TITLE_MENU') ?></a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="/events/" class="fa fa-calendar"> <?= Text::get('TITLE_EVENTS') ?></a>
        <a class="dropdown-item" href="/membership/?year=<?= date('Y') ?>" class="fa fa-user"> <?= Text::get('TITLE_MEMBERS') ?></a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?= Text::get('NAV_TITLE_RENTAL') ?></a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="/rental/overview/"><?= Text::get('TITLE_OVERVIEW') ?></a>
        <a class="dropdown-item" href="/rental/contracts/"><?= Text::get('TITLE_CONTRACTS') ?></a>
        <a class="dropdown-item" href="/rental/invoices/"><?= Text::get('TITLE_INVOICES') ?></a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?= Text::get('NAV_TITLE_EMAIL') ?></a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <h6 class="dropdown-header"><?= Text::get('LABEL_SCHEDULE') ?></h6>
        <a class="dropdown-item" href="/email/batches.php"><?= Text::get('LABEL_BATCHES') ?></a>
        <a class="dropdown-item" href="/email/messages.php"><?= Text::get('LABEL_MESSAGES') ?></a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="/email/history.php"><?= Text::get('TITLE_MAIL_HISTORY') ?></a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="/email/templates/"><?= Text::get('TITLE_MAIL_TEMPLATES') ?></a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= Text::get('NAV_TITLE_SETTINGS') ?>
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="/settings/site-settings/"><?= Text::get('TITLE_SITE_SETTINGS') ?></a>
        <a class="dropdown-item" href="/settings/user-management/"><?= Text::get('TITLE_USER_MANAGEMENT') ?></a>
        <a class="dropdown-item" href="/settings/user-management/roles.php"><?= Text::get('TITLE_ROLE_MANAGEMENT') ?></a>
        <a class="dropdown-item" href="/settings/recent-activity/"><?= Text::get('TITLE_RECENT_ACTIVITY') ?></a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="/settings/debug/"><?= Text::get('TITLE_DEBUG') ?></a>
    </div>
</li>
