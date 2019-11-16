<?php
/**
 * General settings
 */

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\View\Text;

?>
<h3><?= Text::get('LABEL_SETTINGS_GENERAL') ?></h3>

<div class="form-group row">

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_NAME') ?></label>
    <div class="col-8">
        <input type="text" name="site_name" value="<?= SiteSetting::getStaticSiteSetting('site_name') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_DESC') ?></label>
    <div class="col-8">
        <input type="text" name="site_description" value="<?= SiteSetting::getStaticSiteSetting('site_description') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_DESC_TYPE') ?></label>
    <div class="col-8">
        <div class="input-group">
            <select name="site_description_type" class="form-control">
                <option value="1" <?php if (SiteSetting::getStaticSiteSetting('site_description_type') === '1') { echo 'selected'; } ?>>
                    <label class="form-check-label">1. Site description text</label>
                </option>
                <option value="2" <?php if (SiteSetting::getStaticSiteSetting('site_description_type') === '2') { echo 'selected'; } ?>>
                    <label class="form-check-label">2. Random joke</label>
                </option>
                <option value="3" <?php if (SiteSetting::getStaticSiteSetting('site_description_type') === '3') { echo 'selected'; } ?>>
                    <label class="form-check-label">3. Random Chuck Norris joke</label>
                </option>
            </select>
        </div>
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_THEME') ?></label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="site_theme">
                <?php $SiteTheme = SiteSetting::getStaticSiteSetting('site_theme'); ?>
                <option value="darkly" <?php if ($SiteTheme === 'darkly') { echo 'selected'; } ?>>darkly</option>
                <option value="solar" <?php if ($SiteTheme === 'solar') { echo 'selected'; } ?>>solar</option>
                <option value="superhero" <?php if ($SiteTheme === 'superhero') { echo 'selected'; } ?>>superhero</option>
                <option value="cyborg" <?php if ($SiteTheme === 'cyborg') { echo 'selected'; } ?>>cyborg</option>
                <option value="flatly" <?php if ($SiteTheme === 'flatly') { echo 'selected'; } ?>>flatly</option>
                <option value="slate" <?php if ($SiteTheme === 'slate') { echo 'selected'; } ?>>slate</option>
            </select>
        </div>
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_LAYOUT') ?></label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="site_layout">
                <?php $SiteLayout = SiteSetting::getStaticSiteSetting('site_layout'); ?>
                <option value="right-sidebar" <?php if ($SiteLayout === 'right-sidebar') { echo 'selected'; } ?>>right-sidebar</option>
                <option value="left-sidebar" <?php if ($SiteLayout === 'left-sidebar') { echo 'selected'; } ?>>left-sidebar</option>
            </select>
        </div>
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_URL') ?></label>
    <div class="col-8">
        <input type="text" name="site_url" value="<?= SiteSetting::getStaticSiteSetting('site_url') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_LOGO') ?></label>
    <div class="col-8">
        <input type="text" name="site_logo" value="<?= SiteSetting::getStaticSiteSetting('site_logo') ?>" class="form-control">
    </div>

</div>

<?php
/**
 * Widget settings
 */
?>
<h3><?= Text::get('LABEL_SITE_WIDGETS') ?></h3>
<div class="form-group row">
    <label class="col-4 col-form-label"><?= Text::get('TITLE_WIDGET_COMING_EVENTS') ?></label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="WidgetComingEvents">
                <?php $WidgetComingEvents = SiteSetting::getStaticSiteSetting('WidgetComingEvents'); ?>
                <option value="1" <?php if ($WidgetComingEvents === '1') { echo 'selected'; } ?>>Enabled</option>
                <option value="0" <?php if ($WidgetComingEvents === '0') { echo 'selected'; } ?>>Disabled</option>
            </select>
        </div>
    </div>
    <label class="col-4 col-form-label"><?= Text::get('TITLE_WIDGET_DEBUG') ?></label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="WidgetDebug">
                <?php $WidgetDebug = SiteSetting::getStaticSiteSetting('WidgetDebug'); ?>
                <option value="1" <?php if ($WidgetDebug === '1') { echo 'selected'; } ?>>Enabled</option>
                <option value="0" <?php if ($WidgetDebug === '0') { echo 'selected'; } ?>>Disabled</option>
            </select>
        </div>
    </div>
</div>
<?php
/**
 * Mailserver settings
 */
?>
<h3>Mailserver</h3>

<div class="form-group row">

    <label class="col-4 col-form-label">SMTP server IP/hostname</label>
    <div class="col-8">
        <input type="text" name="MailServer" value="<?= SiteSetting::getStaticSiteSetting('MailServer') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label">SMTP server port</label>
    <div class="col-8">
        <input type="text" name="MailServerPort" value="<?= SiteSetting::getStaticSiteSetting('MailServerPort') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label">SMTP encryption</label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="MailServerSecure">
                <?php $MailServerSecure = SiteSetting::getStaticSiteSetting('MailServerSecure'); ?>
                <option value="tls" <?php if ($MailServerSecure === 'tls') { echo 'selected'; } ?>>tls</option>
                <option value="ssl" <?php if ($MailServerSecure === 'ssl') { echo 'selected'; } ?>>ssl</option>
                <option value="0" <?php if ($MailServerSecure === '0') { echo 'selected'; } ?>>Disabled</option>
            </select>
        </div>

    </div>

    <label class="col-4 col-form-label">SMTP authentication</label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="MailServerAuth">
                <?php $MailServerAuth = SiteSetting::getStaticSiteSetting('MailServerAuth'); ?>
                <option value="1" <?php if ($MailServerAuth === '1') { echo 'selected'; } ?>>Enabled</option>
                <option value="0" <?php if ($MailServerAuth === '0') { echo 'selected'; } ?>>Disabled</option>
            </select>
        </div>
    </div>

    <label class="col-4 col-form-label">SMTP username</label>
    <div class="col-8">
        <input type="text" name="MailServerUsername" value="<?= SiteSetting::getStaticSiteSetting('MailServerUsername') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label">SMTP password</label>
    <div class="col-8">
        <input type="password" name="MailServerPassword" value="<?= SiteSetting::getStaticSiteSetting('MailServerPassword') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label">SMTP debug</label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="MailServerDebug">
                <?php $MailServerDebug = SiteSetting::getStaticSiteSetting('MailServerDebug'); ?>
                <option value="0" <?php if ($MailServerDebug === '0') { echo 'selected'; } ?>>0. SMTP::DEBUG_OFF</option>
                <option value="1" <?php if ($MailServerDebug === '1') { echo 'selected'; } ?>>1. SMTP::DEBUG_CLIENT</option>
                <option value="2" <?php if ($MailServerDebug === '2') { echo 'selected'; } ?>>2. SMTP::DEBUG_SERVER</option>
                <option value="3" <?php if ($MailServerDebug === '3') { echo 'selected'; } ?>>3. SMTP::DEBUG_CONNECTION</option>
                <option value="4" <?php if ($MailServerDebug === '4') { echo 'selected'; } ?>>4. SMTP::DEBUG_LOWLEVEL</option>
            </select>
        </div>
    </div>

    <label class="col-4 col-form-label">MailFromName</label>
    <div class="col-8">
        <input type="text" name="MailFromName" value="<?= SiteSetting::getStaticSiteSetting('MailFromName') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label">MailFromEmail</label>
    <div class="col-8">
        <input type="text" name="MailFromEmail" value="<?= SiteSetting::getStaticSiteSetting('MailFromEmail') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label">MailIsHTML</label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="MailIsHTML">
                <?php $MailIsHTML = SiteSetting::getStaticSiteSetting('MailIsHTML'); ?>
                <option value="1" <?php if ($MailIsHTML === '1') { echo 'selected'; } ?>>Enabled</option>
                <option value="0" <?php if ($MailIsHTML === '0') { echo 'selected'; } ?>>Disabled</option>
            </select>
        </div>
    </div>

</div>
