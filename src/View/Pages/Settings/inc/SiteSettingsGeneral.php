<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

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
        <input type="text" name="site_name" value="<?= SiteSetting::get('site_name') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_DESC') ?></label>
    <div class="col-8">
        <input type="text" name="site_description" value="<?= SiteSetting::get('site_description') ?>"
               class="form-control">
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_DESC_TYPE') ?></label>
    <div class="col-8">
        <div class="input-group">
            <select name="site_description_type" class="form-control">
                <option value="1" <?= (SiteSetting::get('site_description_type') === '1') ? 'selected' : '' ?>>
                    <label class="form-check-label">1. Site description text</label>
                </option>
                <option value="2" <?= (SiteSetting::get('site_description_type') === '2') ? 'selected' : '' ?>>
                    <label class="form-check-label">2. Random joke</label>
                </option>
                <option value="3" <?= (SiteSetting::get('site_description_type') === '3') ? 'selected' : '' ?>>
                    <label class="form-check-label">3. Random Chuck Norris joke</label>
                </option>
            </select>
        </div>
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_THEME') ?></label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="site_theme">
                <?php $SiteTheme = SiteSetting::get('site_theme'); ?>
                <option value="darkly" <?= ($SiteTheme === 'darkly') ? 'selected' : '' ?>>darkly</option>
                <option value="solar" <?= ($SiteTheme === 'solar') ? 'selected' : '' ?>>solar</option>
                <option value="superhero" <?= ($SiteTheme === 'superhero') ? 'selected' : '' ?>>superhero</option>
                <option value="cyborg" <?= ($SiteTheme === 'cyborg') ? 'selected' : '' ?>>cyborg</option>
                <option value="flatly" <?= ($SiteTheme === 'flatly') ? 'selected' : '' ?>>flatly</option>
                <option value="slate" <?= ($SiteTheme === 'slate') ? 'selected' : '' ?>>slate</option>
            </select>
        </div>
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_LAYOUT') ?></label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="site_layout">
                <?php $SiteLayout = SiteSetting::get('site_layout'); ?>
                <option value="right-sidebar" <?= ($SiteLayout === 'right-sidebar') ? 'selected' : '' ?>>right-sidebar
                </option>
                <option value="left-sidebar" <?= ($SiteLayout === 'left-sidebar') ? 'selected' : '' ?>>left-sidebar
                </option>
            </select>
        </div>
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_URL') ?></label>
    <div class="col-8">
        <input type="text" name="site_url" value="<?= SiteSetting::get('site_url') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label"><?= Text::get('LABEL_SITE_LOGO_URL') ?></label>
    <div class="col-8">
        <input type="text" name="site_logo" value="<?= SiteSetting::get('site_logo') ?>" class="form-control">
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
                <?php $WidgetComingEvents = SiteSetting::get('WidgetComingEvents'); ?>
                <option value="true" <?= ($WidgetComingEvents === 'true') ? 'selected' : '' ?>>Enabled</option>
                <option value="false" <?= ($WidgetComingEvents === 'false') ? 'selected' : '' ?>>Disabled</option>
            </select>
        </div>
    </div>
    <label class="col-4 col-form-label"><?= Text::get('TITLE_WIDGET_DEBUG') ?></label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="WidgetDebug">
                <?php $WidgetDebug = SiteSetting::get('WidgetDebug'); ?>
                <option value="true" <?= ($WidgetDebug === 'true') ? 'selected' : '' ?>>Enabled</option>
                <option value="false" <?= ($WidgetDebug === 'false') ? 'selected' : '' ?>>Disabled</option>
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
        <input type="text" name="MailServer" value="<?= SiteSetting::get('MailServer') ?>" class="form-control">
    </div>
    <label class="col-4 col-form-label">SMTP server port</label>
    <div class="col-8">
        <input type="text" name="MailServerPort" value="<?= SiteSetting::get('MailServerPort') ?>" class="form-control">
    </div>
    <label class="col-4 col-form-label">SMTP encryption</label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="MailServerSecure">
                <?php $MailServerSecure = SiteSetting::get('MailServerSecure'); ?>
                <option value="tls" <?= ($MailServerSecure === 'tls') ? 'selected' : '' ?>>tls</option>
                <option value="ssl" <?= ($MailServerSecure === 'ssl') ? 'selected' : '' ?>>ssl</option>
                <option value="off" <?= ($MailServerSecure === 'off') ? 'selected' : '' ?>>Disabled</option>
            </select>
        </div>
    </div>
    <label class="col-4 col-form-label">SMTP authentication</label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="MailServerAuth">
                <?php $MailServerAuth = SiteSetting::get('MailServerAuth'); ?>
                <option value="true" <?= ($MailServerAuth === 'true') ? 'selected' : '' ?>>Enabled</option>
                <option value="false" <?= ($MailServerAuth === 'false') ? 'selected' : '' ?>>Disabled</option>
            </select>
        </div>
    </div>
    <label class="col-4 col-form-label">SMTP username</label>
    <div class="col-8">
        <input type="text" name="MailServerUsername" value="<?= SiteSetting::get('MailServerUsername') ?>"
               class="form-control">
    </div>
    <label class="col-4 col-form-label">SMTP password</label>
    <div class="col-8">
        <input type="password" name="MailServerPassword" value="<?= SiteSetting::get('MailServerPassword') ?>"
               class="form-control">
    </div>
    <label class="col-4 col-form-label">SMTP debug</label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="MailServerDebug">
                <?php $MailServerDebug = SiteSetting::get('MailServerDebug'); ?>
                <option value="0" <?= ($MailServerDebug === '0') ? 'selected' : '' ?>>0. SMTP::DEBUG_OFF</option>
                <option value="1" <?= ($MailServerDebug === '1') ? 'selected' : '' ?>>1. SMTP::DEBUG_CLIENT</option>
                <option value="2" <?= ($MailServerDebug === '2') ? 'selected' : '' ?>>2. SMTP::DEBUG_SERVER</option>
                <option value="3" <?= ($MailServerDebug === '3') ? 'selected' : '' ?>>3. SMTP::DEBUG_CONNECTION</option>
                <option value="4" <?= ($MailServerDebug === '4') ? 'selected' : '' ?>>4. SMTP::DEBUG_LOWLEVEL</option>
            </select>
        </div>
    </div>

    <label class="col-4 col-form-label">MailFromName</label>
    <div class="col-8">
        <input type="text" name="MailFromName" value="<?= SiteSetting::get('MailFromName') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label">MailFromEmail</label>
    <div class="col-8">
        <input type="text" name="MailFromEmail" value="<?= SiteSetting::get('MailFromEmail') ?>" class="form-control">
    </div>

    <label class="col-4 col-form-label">MailIsHTML</label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="MailIsHTML">
                <?php $MailIsHTML = SiteSetting::get('MailIsHTML'); ?>
                <option value="true" <?= ($MailIsHTML === 'true') ? 'selected' : '' ?>>Enabled</option>
                <option value="false" <?= ($MailIsHTML === 'false') ? 'selected' : '' ?>>Disabled</option>
            </select>
        </div>
    </div>

</div>
