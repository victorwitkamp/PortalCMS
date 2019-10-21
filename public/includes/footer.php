<footer class="footer bg-light">
    <div class="container">
        <p>© <?php use PortalCMS\Core\Config\SiteSetting;

echo date("Y"); ?> <?php echo SiteSetting::getStaticSiteSetting('site_name'); ?></p>
    </div>
</footer>
