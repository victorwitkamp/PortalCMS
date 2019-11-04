<?php
/**
 * The header file
 */

use PortalCMS\Core\Config\SiteSetting;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=
        $pageName
    ?> - <?=
        SiteSetting::getStaticSiteSetting('site_name')
?></title>