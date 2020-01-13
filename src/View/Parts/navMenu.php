<?php

use PortalCMS\Core\View\Text;

?>
<li class="nav-item dropdown">
    <a class="nav-link" href="/Home"><?= Text::get('NAV_TITLE_HOME') ?></a>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
       aria-expanded="false"> <?= Text::get('NAV_TITLE_MENU') ?></a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="/events/" class="fa fa-calendar"> <?= Text::get('TITLE_EVENTS') ?></a>
        <a class="dropdown-item" href="/membership/" class="fa fa-user"> <?= Text::get('TITLE_MEMBERS') ?></a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
       aria-expanded="false"> <?= Text::get('NAV_TITLE_RENTAL') ?></a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="/Contracts/"><?= Text::get('TITLE_CONTRACTS') ?></a>
        <a class="dropdown-item" href="/Invoices/"><?= Text::get('TITLE_INVOICES') ?></a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
       aria-expanded="false"> <?= Text::get('NAV_TITLE_EMAIL') ?></a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <h6 class="dropdown-header"><?= Text::get('LABEL_SCHEDULE') ?></h6>
        <a class="dropdown-item" href="/email/Batches"><?= Text::get('LABEL_BATCHES') ?></a>
        <a class="dropdown-item" href="/email/Messages"><?= Text::get('LABEL_MESSAGES') ?></a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="/email/History"><?= Text::get('TITLE_MAIL_HISTORY') ?></a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="/email/ViewTemplates"><?= Text::get('TITLE_MAIL_TEMPLATES') ?></a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
       aria-expanded="false">
        <?= Text::get('NAV_TITLE_SETTINGS') ?>
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <h6 class="dropdown-header"><?= Text::get('LABEL_GENERAL_SETTINGS') ?></h6>
        <a class="dropdown-item" href="/Settings/SiteSettings/"><?= Text::get('TITLE_SITE_SETTINGS') ?></a>
        <div class="dropdown-divider"></div>
        <h6 class="dropdown-header"><?= Text::get('LABEL_ACCESS_SETTINGS') ?></h6>
        <a class="dropdown-item" href="/UserManagement/Users"><?= Text::get('TITLE_USER_MANAGEMENT') ?></a>
        <a class="dropdown-item" href="/UserManagement/Roles"><?= Text::get('TITLE_ROLE_MANAGEMENT') ?></a>
        <div class="dropdown-divider"></div>
        <h6 class="dropdown-header"><?= Text::get('LABEL_OTHER') ?></h6>
        <a class="dropdown-item" href="/Settings/Activity/"><?= Text::get('TITLE_RECENT_ACTIVITY') ?></a>
        <a class="dropdown-item" href="/Settings/Debug/"><?= Text::get('TITLE_DEBUG') ?></a>
    </div>
</li>
