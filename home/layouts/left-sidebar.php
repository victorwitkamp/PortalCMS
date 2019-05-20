<?php

/**
 * Layout : Left Sidebar (left-sidebar.php)
 * Details : 
 */
?>
<div class="col-sm-4">
    <?php
    if (SiteSettings::getStaticSiteSetting('WidgetComingEvents') == '1') { 
        include 'widgets/comingEvents/comingEvents.php'; 
    } 
    ?>
</div>
<div class="col-sm-8">
    <?php
    $page = Page::getPage('1');
    Util::DisplayMessage();

    $permissionName = 'edit-page';
    echo $page["content"]; 

    if (Session::get("user_account_type") == 7) {
        echo '<hr><a href="/page/edit.php?id=1">'.Text::get('LABEL_EDIT_PAGE').'</a><p>'.Text::get('LABEL_LAST_MODIFIED').': '.$page["ModificationDate"].'</p>';
    }
    ?>
</div>