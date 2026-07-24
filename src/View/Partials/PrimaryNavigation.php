<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */
declare(strict_types=1);

use PortalCMS\Core\View\Text;
?>
    <div class="navbar navbar-light bg-light p-0">
        <div class="container">
            <a class="navbar-brand me-auto mr-lg-0" href="/Home"><?= $this->e($siteName) ?></a>
            <span class="navbar-text p-0">
            <?= Text::get('LABEL_SIGNED_IN_AS') ?><strong> <?= $this->e((string) ($currentUserName ?? '')) ?></strong><br>
            <a href="/Account"><?= Text::get('TITLE_MY_ACCOUNT') ?></a> | <a href="/Logout"><i class="fa fa-sign-out-alt"></i><?= Text::get('LABEL_SIGN_OUT') ?></a>
        </span>
        </div>
    </div>
    <nav class="navbar navbar-expand-sm navbar-dark bg-secondary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
                    aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mt-lg-0">
                    <?= $this->insert('View::Partials/PrimaryNavigationMenu') ?>
                </ul>
            </div>
        </div>
    </nav>
<?php
