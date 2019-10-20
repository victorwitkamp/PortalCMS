<?php
/**
 * Widget settings
 */

use PortalCMS\Core\Text;
use PortalCMS\Models\SiteSetting;

?>
<h3><?php echo Text::get('LABEL_SITE_WIDGETS'); ?></h3>

<div class="form-group row">

    <label class="col-4 col-form-label"><?php echo Text::get('TITLE_WIDGET_COMING_EVENTS'); ?></label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="WidgetComingEvents">
                <?php $WidgetComingEvents = SiteSetting::getStaticSiteSetting('WidgetComingEvents'); ?>
                <option value="1" <?php if ($WidgetComingEvents == '1') { echo 'selected'; } ?>>Enabled</option>
                <option value="0" <?php if ($WidgetComingEvents == '0') { echo 'selected'; } ?>>Disabled</option>
            </select>
        </div>
    </div>

    <label class="col-4 col-form-label"><?php echo Text::get('TITLE_WIDGET_DEBUG'); ?></label>
    <div class="col-8">
        <div class="input-group">
            <select class="form-control" name="WidgetDebug">
                <?php $WidgetDebug = SiteSetting::getStaticSiteSetting('WidgetDebug'); ?>
                <option value="1" <?php if ($WidgetDebug == '1') { echo 'selected'; } ?>>Enabled</option>
                <option value="0" <?php if ($WidgetDebug == '0') { echo 'selected'; } ?>>Disabled</option>
            </select>
        </div>
    </div>

</div>