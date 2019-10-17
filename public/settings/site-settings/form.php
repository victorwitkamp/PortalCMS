    <h3><?php echo Text::get('LABEL_SETTINGS_GENERAL'); ?></h3>

    <div class="form-group row">

        <label class="col-4 col-form-label"><?php echo Text::get('LABEL_SITE_NAME'); ?></label>
        <div class="col-8">
            <input type="text" name="site_name" value="<?php echo SiteSetting::getStaticSiteSetting('site_name'); ?>" class="form-control">
        </div>

        <label class="col-4 col-form-label"><?php echo Text::get('LABEL_SITE_DESC'); ?></label>
        <div class="col-8">
            <input type="text" name="site_description" value="<?php echo SiteSetting::getStaticSiteSetting('site_description'); ?>" class="form-control">
        </div>

        <label class="col-4 col-form-label"><?php echo Text::get('LABEL_SITE_DESC_TYPE'); ?></label>
        <div class="col-8">
            <div class="input-group">
                <select name="site_description_type" class="form-control">
                    <option value="1" <?php if (SiteSetting::getStaticSiteSetting('site_description_type') === '1') { echo 'selected';
                                        } ?>>
                        <label class="form-check-label">
                            1. Site description text
                        </label>
                    </option>
                    <option value="2" <?php if (SiteSetting::getStaticSiteSetting('site_description_type') === '2') { echo 'selected';
                                        } ?>>
                        <label class="form-check-label">
                            2. Random joke
                        </label>
                    </option>
                    <option value="3" <?php if (SiteSetting::getStaticSiteSetting('site_description_type') === '3') { echo 'selected';
                                        } ?>>
                        <label class="form-check-label">
                                3. Random Chuck Norris joke
                        </label>
                    </option>
                </select>
            </div>
        </div>

        <label class="col-4 col-form-label"><?php echo Text::get('LABEL_SITE_THEME'); ?></label>
        <div class="col-8">
            <div class="input-group">
                <select class="form-control" name="site_theme">
                    <?php $SiteTheme = SiteSetting::getStaticSiteSetting('site_theme'); ?>
                    <option value="darkly" <?php if ($SiteTheme == 'darkly') { echo 'selected';
                                            } ?>>darkly</option>
                    <option value="solar" <?php if ($SiteTheme == 'solar') { echo 'selected';
                                            } ?>>solar</option>
                    <option value="superhero" <?php if ($SiteTheme == 'superhero') { echo 'selected';
                                                } ?>>superhero</option>
                    <option value="cyborg" <?php if ($SiteTheme == 'cyborg') { echo 'selected';
                                            } ?>>cyborg</option>
                    <option value="flatly" <?php if ($SiteTheme == 'flatly') { echo 'selected';
                                            } ?>>flatly</option>
                    <option value="slate" <?php if ($SiteTheme == 'slate') { echo 'selected';
                                            } ?>>slate</option>
                </select>
            </div>
        </div>

        <label class="col-4 col-form-label"><?php echo Text::get('LABEL_SITE_LAYOUT'); ?></label>
        <div class="col-8">
            <div class="input-group">
                <select class="form-control" name="site_layout">
                    <?php $SiteLayout = SiteSetting::getStaticSiteSetting('site_layout'); ?>
                    <option value="right-sidebar" <?php if ($SiteLayout == 'right-sidebar') { echo 'selected';
                                                    } ?>>right-sidebar</option>
                    <option value="left-sidebar" <?php if ($SiteLayout == 'left-sidebar') { echo 'selected';
                                                    } ?>>left-sidebar</option>
                </select>
            </div>
        </div>

        <label class="col-4 col-form-label"><?php echo Text::get('LABEL_SITE_URL'); ?></label>
        <div class="col-8">
            <input type="text" name="site_url" value="<?php echo SiteSetting::getStaticSiteSetting('site_url'); ?>" class="form-control">
        </div>

        <label class="col-4 col-form-label"><?php echo Text::get('LABEL_SITE_LOGO'); ?></label>
        <div class="col-8">
            <input type="text" name="site_logo" value="<?php echo SiteSetting::getStaticSiteSetting('site_logo'); ?>" class="form-control">

        </div>



    </div>

    <hr>

    <h3><?php echo Text::get('LABEL_SITE_WIDGETS'); ?></h3>

    <div class="form-group row">

        <label class="col-4 col-form-label"><?php echo Text::get('TITLE_WIDGET_COMING_EVENTS'); ?></label>
        <div class="col-8">
            <div class="input-group">
                <select class="form-control" name="WidgetComingEvents">
                    <?php $WidgetComingEvents = SiteSetting::getStaticSiteSetting('WidgetComingEvents'); ?>
                    <option value="1" <?php if ($WidgetComingEvents == '1') { echo 'selected';
                                        } ?>>Enabled</option>
                    <option value="0" <?php if ($WidgetComingEvents == '0') { echo 'selected';
                                        } ?>>Disabled</option>
                </select>
            </div>
        </div>

        <label class="col-4 col-form-label"><?php echo Text::get('TITLE_WIDGET_DEBUG'); ?></label>
        <div class="col-8">
            <div class="input-group">
                <select class="form-control" name="WidgetDebug">
                    <?php $WidgetDebug = SiteSetting::getStaticSiteSetting('WidgetDebug'); ?>
                    <option value="1" <?php if ($WidgetDebug == '1') { echo 'selected';
                                        } ?>>Enabled</option>
                    <option value="0" <?php if ($WidgetDebug == '0') { echo 'selected';
                                        } ?>>Disabled</option>
                </select>
            </div>
        </div>

    </div>

    <hr>

    <?php require 'inc\mailserver.php'; ?>
