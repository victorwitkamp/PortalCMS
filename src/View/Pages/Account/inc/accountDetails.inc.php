<?php

use PortalCMS\Core\Session\Session;
use PortalCMS\Core\User\UserPDOReader;
use PortalCMS\Core\View\Text;

$user = UserPDOReader::getProfileById(Session::get('user_id'));
?>
<h3><?= Text::get('LABEL_ACCOUNT_DETAILS') ?></h3>
<table class="table table-striped table-condensed">
    <tr>
        <th><?= Text::get('LABEL_USER_ID') ?></th>
        <td><?= Session::get('user_id') ?></td>
    </tr>
    <tr>
        <th><?= Text::get('LABEL_USER_NAME') ?></th>
        <td><?= Session::get('user_name') ?></td>
    </tr>
    <tr>
        <th><?= Text::get('LABEL_USER_EMAIL') ?></th>
        <td><?= Session::get('user_email') ?></td>
    </tr>
    <tr>
        <th><?= Text::get('LABEL_USER_LAST_LOGIN_TIMESTAMP') ?></th>
        <td><?= $user->user_last_login_timestamp ?></td>
    </tr>
    <tr>
        <th><?= Text::get('LABEL_USER_PROVIDER_TYPE') ?></th>
        <td><?= Session::get('user_provider_type') ?></td>
    </tr>
    <tr>
        <th><?= Text::get('LABEL_USER_FBID') ?></th>
        <td><?php
        if (!empty(Session::get('user_fbid'))) {
            echo Session::get('user_fbid') . ' '; ?>
            <form method="post">
            <input type="submit" name="clearUserFbid" class="btn btn-outline-success user_registered-login" value="<?= Text::get('LABEL_USER_CLEAR_FBID') ?>"/>
            </form><?php
        } else {
            ?><a href="<?= $loginUrl ?>">Connect with Facebook!</a><?php
        }
        ?></td>
    </tr>
    <!-- <tr>
        <th>Rol</th><td> -->
        <?php
        //$role_id = $u->getRoleIDByUserID($userData['id']);
        //$role_name = $u->getRoleNameByRoleID($role_id);
        //echo $role_name;
        ?>
        <!-- </td>
    </tr> -->
    <!-- <tr>
        <th>Geregistreerd op</th>
        <td> -->
        <?php //echo $userData['CreationDate'];?>
        <!-- </td>
    </tr> -->
    <!-- <tr>
        <th>Laatst gewijzigd</th><td> -->
        <?php //echo $userData['ModificationDate'];?>
        <!-- </td>
    </tr> -->
</table>