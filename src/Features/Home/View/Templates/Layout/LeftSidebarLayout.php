<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

/**
 * Layout : Left Sidebar (left-sidebar.php)
 * Details :
 */

use PortalCMS\Core\View\Text;

?>
<div class="col-sm-4">
    <?php
    if (($settings['WidgetComingEvents'] ?? null) === 'true') {
        echo $this->insert('Home::Widget/UpcomingEventsWidget', [ 'events' => $events ]);
    }
    ?>
    <hr>
    <?php
    if (($settings['WidgetDebug'] ?? null) === 'true') {
        echo $this->insert('Home::Widget/DebugWidget');
    }
    ?>
</div>
<div class="col-sm-8">
    <?php
    echo $this->insert('View::Partials/FlashMessages', compact('flashMessages'));

    echo $page?->content ?? '';

    if ($canEdit && $page !== null) {
        echo '<hr><a href="/page/edit?id=' . $this->e($page->id) . '">'
            . Text::get('LABEL_EDIT_PAGE')
            . '</a><p>'
            . Text::get('LABEL_LAST_MODIFIED')
            . ': '
            . $this->e($page->ModificationDate->format('Y-m-d H:i:s'))
            . '</p>';
    }
    ?>
</div>
