<?php
use PortalCMS\Core\Config\SiteSetting;
?>
<footer class="footer bg-light">
    <div class="container">
        <p>© <?php echo date('Y'); ?> <?= SiteSetting::getStaticSiteSetting('site_name') ?></p>
    </div>
</footer>
