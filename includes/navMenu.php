<li class="nav-item dropdown">
    <a class="nav-link" href="/home/index.php"><?php echo Text::get('NAV_TITLE_HOME'); ?></a>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo Text::get('NAV_TITLE_MENU'); ?></a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="/events/" class="fa fa-calendar"> <?php echo Text::get('TITLE_EVENTS'); ?></a>
        <a class="dropdown-item" href="/membership/?year=<?php echo date("Y"); ?>" class="fa fa-user"> <?php echo Text::get('TITLE_MEMBERS'); ?></a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo Text::get('NAV_TITLE_RENTAL'); ?></a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="/rental/overview/"><?php echo Text::get('TITLE_OVERVIEW'); ?></a>
        <a class="dropdown-item" href="/rental/contracts/"><?php echo Text::get('TITLE_CONTRACTS'); ?></a>
        <a class="dropdown-item" href="/rental/invoices/"><?php echo Text::get('TITLE_INVOICES'); ?></a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo Text::get('NAV_TITLE_EMAIL'); ?></a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="/mail/"><?php echo Text::get('TITLE_MAIL_SCHEDULER'); ?></a>
        <a class="dropdown-item" href="/mail/history.php"><?php echo Text::get('TITLE_MAIL_HISTORY'); ?></a>
        <a class="dropdown-item" href="/mail/templates.php"><?php echo Text::get('TITLE_MAIL_TEMPLATES'); ?></a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php echo Text::get('NAV_TITLE_SETTINGS'); ?>
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="/settings/site-settings/"><?php echo Text::get('TITLE_SITE_SETTINGS'); ?></a>
        <a class="dropdown-item" href="/settings/user-management/"><?php echo Text::get('TITLE_USER_MANAGEMENT'); ?></a>
        <a class="dropdown-item" href="/settings/user-management/roles.php"><?php echo Text::get('TITLE_ROLE_MANAGEMENT'); ?></a>
        <a class="dropdown-item" href="/settings/recent-activity/"><?php echo Text::get('TITLE_RECENT_ACTIVITY'); ?></a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="/settings/debug/"><?php echo Text::get('TITLE_DEBUG'); ?></a>
    </div>
</li>