<?php
use PortalCMS\Core\Text;
?>
<h4><?php echo Text::get('TITLE_WIDGET_DEBUG'); ?></h4>
<p><?php var_dump($_SESSION); ?></p>
