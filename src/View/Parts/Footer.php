<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Config\SiteSetting;

?>
<footer class="footer bg-light">
    <div class="container">
        <!--        <span class="text-muted">-->
        <span>© <?= date('Y') ?> <?= SiteSetting::get('site_name') ?></span>
    </div>
</footer>
