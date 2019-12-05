<?php

use PortalCMS\Core\View\Text;

?>
<h4><?= Text::get('TITLE_WIDGET_DEBUG') ?></h4>
<pre><?php print_r($_SESSION); ?></pre>
