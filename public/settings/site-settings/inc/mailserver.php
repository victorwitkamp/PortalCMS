<h3>Mailserver</h3>

    <div class="form-group row">

        <label class="col-4 col-form-label">Mailserver</label>
        <div class="col-8">
            <input type="text" name="MailServer" value="<?php echo SiteSetting::getStaticSiteSetting('MailServer'); ?>" class="form-control">
        </div>

        <label class="col-4 col-form-label">Port</label>
        <div class="col-8">
            <input type="text" name="MailServerPort" value="<?php echo SiteSetting::getStaticSiteSetting('MailServerPort'); ?>" class="form-control">
        </div>

        <label class="col-4 col-form-label">Security</label>
        <div class="col-8">
            <div class="input-group">
                <select class="form-control" name="MailServerSecure">
                    <?php $MailServerSecure = SiteSetting::getStaticSiteSetting('MailServerSecure'); ?>
                    <option value="tls" <?php if ($MailServerSecure == 'tls') { echo 'selected';
                                        } ?>>tls</option>
                    <option value="ssl" <?php if ($MailServerSecure == 'ssl') { echo 'selected';
                                        } ?>>ssl</option>
                    <option value="0" <?php if ($MailServerSecure == '0') { echo 'selected';
                                        } ?>>Disabled</option>
                </select>
            </div>

        </div>

        <label class="col-4 col-form-label">Enable SMTP authentication</label>
        <div class="col-8">
            <div class="input-group">
                <select class="form-control" name="MailServerAuth">
                    <?php $MailServerAuth = SiteSetting::getStaticSiteSetting('MailServerAuth'); ?>
                    <option value="1" <?php if ($MailServerAuth == '1') { echo 'selected';
                                        } ?>>Enabled</option>
                    <option value="0" <?php if ($MailServerAuth == '0') { echo 'selected';
                                        } ?>>Disabled</option>
                </select>
            </div>
        </div>

        <label class="col-4 col-form-label">SMTP username</label>
        <div class="col-8">
            <input type="text" name="MailServerUsername" value="<?php echo SiteSetting::getStaticSiteSetting('MailServerUsername'); ?>" class="form-control">
        </div>

        <label class="col-4 col-form-label">SMTP password</label>
        <div class="col-8">
            <input type="password" name="MailServerPassword" value="<?php echo SiteSetting::getStaticSiteSetting('MailServerPassword'); ?>" class="form-control">
        </div>

        <label class="col-4 col-form-label">SMTP Debug</label>
        <div class="col-8">
            <div class="input-group">
                <select class="form-control" name="MailServerDebug">
                    <?php $MailServerDebug = SiteSetting::getStaticSiteSetting('MailServerDebug'); ?>
                    <option value="1" <?php if ($MailServerDebug == '1') { echo 'selected';
                                        } ?>>Enabled</option>
                    <option value="0" <?php if ($MailServerDebug == '0') { echo 'selected';
                                        } ?>>Disabled</option>
                </select>
            </div>
        </div>

        <label class="col-4 col-form-label">MailFromName</label>
        <div class="col-8">
            <input type="text" name="MailFromName" value="<?php echo SiteSetting::getStaticSiteSetting('MailFromName'); ?>" class="form-control">
        </div>

        <label class="col-4 col-form-label">MailFromEmail</label>
        <div class="col-8">
            <input type="text" name="MailFromEmail" value="<?php echo SiteSetting::getStaticSiteSetting('MailFromEmail'); ?>" class="form-control">
        </div>

    </div>