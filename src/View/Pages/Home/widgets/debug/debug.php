<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\View\Text;

?>
<h4><?= Text::get('TITLE_WIDGET_DEBUG') ?></h4>
<pre><?php print_r($_SESSION); ?></pre>
