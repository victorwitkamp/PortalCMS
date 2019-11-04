<?php

use PortalCMS\Core\View\Text;

?>
<h4><?= Text::get('TITLE_WIDGET_DEBUG') ?></h4>
<p><?php var_dump($_SESSION); ?></p>
