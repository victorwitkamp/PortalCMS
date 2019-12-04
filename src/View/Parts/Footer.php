<?php

use PortalCMS\Core\Config\SiteSetting;

?>
<footer class="footer bg-light">
    <div class="container">
        <span class="text-muted">© <?= date('Y') ?> <?= SiteSetting::getStaticSiteSetting('site_name') ?></span>
    </div>
</footer>
