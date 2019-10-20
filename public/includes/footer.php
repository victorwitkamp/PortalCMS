<footer class="footer bg-light">
    <div class="container">
        <p>© <?php use PortalCMS\Models\SiteSetting;

            echo date("Y"); ?> <?php echo SiteSetting::getStaticSiteSetting('site_name'); ?></p>
    </div>
</footer>
