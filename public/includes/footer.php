<?php

use PortalCMS\Core\Config\SiteSetting;

?>
<footer class="footer bg-light">
    <div class="container">
        <p>© <?= date('Y') ?> <?= SiteSetting::getStaticSiteSetting('site_name') ?></p>
    </div>
</footer>
