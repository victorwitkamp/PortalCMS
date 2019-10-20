<?php

/**
 * Layout : Left Sidebar (left-sidebar.php)
 * Details :
 */

use PortalCMS\Authentication\Authentication;
use PortalCMS\Core\Alert;
use PortalCMS\Core\Text;
use PortalCMS\Models\Page;
use PortalCMS\Models\SiteSetting;

?>
<div class="col-sm-4">
    <?php
    if (SiteSetting::getStaticSiteSetting('WidgetComingEvents') == '1') {
        include 'widgets/comingEvents/comingEvents.php';
    }
    ?><hr><?php
if (SiteSetting::getStaticSiteSetting('WidgetDebug') == '1') {
        include 'widgets/debug/debug.php';
    }
?>
</div>
<div class="col-sm-8">
    <?php
    Alert::renderFeedbackMessages();

    $page = Page::getPage('1');
    echo $page["content"];

    if (Authentication::checkPrivilege("site-settings")) {
        echo '<hr><a href="/page/edit.php?id=1">'.Text::get('LABEL_EDIT_PAGE').'</a><p>'.Text::get('LABEL_LAST_MODIFIED').': '.$page["ModificationDate"].'</p>';
    }
    ?>
</div>
