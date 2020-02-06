<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

/**
 * Layout : Right Sidebar (right-sidebar.php)
 * Details :
 */

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Core\View\Alert;
use PortalCMS\Core\View\Page;
use PortalCMS\Core\View\Text;

?>
<div class="col-sm-8">
    <?php
    Alert::renderFeedbackMessages();
    $page = Page::getPage(1);
    echo $page['content'];
    if (Authorization::hasPermission('site-settings')) {
        echo '<hr><a href="/page/edit?id=1">' . Text::get('LABEL_EDIT_PAGE') . '</a><p>' . Text::get('LABEL_LAST_MODIFIED') . ': ' . $page['ModificationDate'] . '</p>';
    }
    ?>
</div>
<div class="col-sm-4">
    <?php
    if (SiteSetting::get('WidgetComingEvents') === 'true') {
        include DIR_VIEW . 'Pages/Home/widgets/comingEvents/comingEvents.php';
    }
    ?><hr>
    <?php
    if (SiteSetting::get('WidgetDebug') === 'true') {
        include DIR_VIEW . 'Pages/Home/widgets/debug/debug.php';
    }
    ?>
</div>
