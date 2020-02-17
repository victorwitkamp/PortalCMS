<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;
use PortalCMS\Modules\Calendar\EventMapper;

?>
<h4><?= Text::get('TITLE_WIDGET_COMING_EVENTS') ?></h4>
<div class="card" id="show-events">
    <?php
    $events = EventMapper::getEventsAfter(date('Y-m-d H:i:s'));
    if (!empty($events)) {
        ?>
        <ul class="list-group">
            <?php
            foreach ($events as $event) {
                ?><li class="list-group-item"><i class="far fa-calendar"></i> <a href="/Events/Details?id=<?= $event->id ?>"><?= $event->title ?></a>
                <br>Start: <?= $event->start_event ?></li>
                <?php
            } ?>
        </ul>
        <?php
    } else {
        ?>
        <p>Geen evenementen gevonden.</p>
        <?php
    }
    ?>
</div>