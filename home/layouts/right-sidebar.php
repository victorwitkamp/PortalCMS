<?php
/**
 * Layout : Right Sidebar (right-sidebar.php)
 * Details :
 */
?>
<div class="col-sm-8">
    <?php
    Alert::renderFeedbackMessages();

    $page = Page::getPage('1');
    echo $page["content"];
    if (Auth::checkPrivilege("site-settings")) {
        echo '<hr><a href="/page/edit.php?id=1">'.Text::get('LABEL_EDIT_PAGE').'</a><p>'.Text::get('LABEL_LAST_MODIFIED').': '.$page["ModificationDate"].'</p>';
    }
    ?>
</div>
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