<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Security\Authorization\UserRoleMapper;
use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserMapper;
use PortalCMS\Core\View\Text;

$user = UserMapper::getProfileById((int)Session::get('user_id'));
?>
<h3><?= Text::get('LABEL_ACCOUNT_DETAILS') ?></h3>
<table class="table table-striped table-condensed">
    <tr>
        <th><?= Text::get('LABEL_USER_ID') ?></th>
        <td><?= $user->user_id ?></td>
    </tr>
    <tr>
        <th><?= Text::get('LABEL_USER_NAME') ?></th>
        <td><?= $user->user_name ?></td>
    </tr>
    <tr>
        <th><?= Text::get('LABEL_USER_EMAIL') ?></th>
        <td><?= $user->user_email ?></td>
    </tr>
    <tr>
        <th><?= Text::get('LABEL_USER_LAST_LOGIN_TIMESTAMP') ?></th>
        <td><?= $user->user_last_login_timestamp ?></td>
    </tr>
    <tr>
        <th><?= Text::get('LABEL_USER_LAST_FAILED_LOGIN') ?></th>
        <td><?= $user->user_last_failed_login ?></td>
    </tr>
    <tr>
        <th><?= Text::get('LABEL_USER_FBID') ?></th>
        <td><?php
            if (!empty($user->user_fbid)) {
                echo $user->user_fbid . ' '; ?>
                <form method="post">
                <input type="submit" name="clearUserFbid" class="btn btn-outline-success user_registered-login"
                       value="<?= Text::get('LABEL_USER_CLEAR_FBID') ?>"/>
                </form><?php
            } else {
                ?><a href="<?= $loginUrl ?>"><?= Text::get('LABEL_USER_CONNECT_FB') ?></a><?php
            }
            ?></td>
    </tr>
    <tr>
        <th>Rollen</th>
        <td>
            <?php
            $Roles = UserRoleMapper::getByUserId($user->user_id);
            if (!empty($Roles)) {
                foreach ($Roles as $Role) { ?>
                    <?= $Role->role_name ?> (<?= $Role->role_id ?>)
                <?php }
            } ?>
        </td>
    </tr>
</table>
<hr>
