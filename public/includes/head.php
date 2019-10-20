<?php
/**
 * The header file
 */

use PortalCMS\Models\SiteSetting;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php
    echo $pageName;
    ?> - <?php
    echo SiteSetting::getStaticSiteSetting('site_name');
?></title>