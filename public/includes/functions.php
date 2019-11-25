<?php

use PortalCMS\Core\Config\SiteSetting;

/**
 * PortalCMS Framework: Functions
 *
 * Functions used in the PortalCMS Framework. Should be included on all pages.
 *
 * @package PortalCMS
 * @link    https://PortalCMS.victorwitkamp.nl/
 */

function PortalCMS_JS_tempusdominus()
{
    PortalCMS_JS_moment();
    echo '<script src="/dist/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js" async></script>';
}

function PortalCMS_CSS_tempusdominus()
{
    echo '<link rel="stylesheet" type="text/css" href="/dist/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">';
}

function PortalCMS_JS_JQuery_Simple_validator()
{
    // echo '<script src="/includes/js/jquery-simple-validator.nl.js"></script>
    // <link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css">';
}

function PortalCMS_CSS_dataTables()
{
    echo '<link rel="stylesheet" type="text/css" href="/dist/datatables.net-bs4/css/dataTables.bootstrap4.min.css">';
}

function PortalCMS_JS_dataTables()
{
    echo '<script src="/dist/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/dist/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>';
}

function PortalCMS_JS_Init_dataTables()
{
    echo '<script src="/includes/js/init.datatables.js" class="init"></script>';
}

function PortalCMS_JS_Datepicker_membership()
{
    echo '<script src="/includes/js/datepicker_membership.js"></script>';
}

function PortalCMS_JS_Datepicker_event()
{
    echo '<script src="/includes/js/datepicker_event.js"></script>';
}

// function displayHeadCSS()
// {
//     $theme = SiteSetting::getStaticSiteSetting('site_theme');
//     echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
//     <link rel="stylesheet" type="text/css" href="/dist/@fortawesome/fontawesome-free/css/all.min.css">
//     <link rel="stylesheet" type="text/css" href="/dist/bootswatch/dist/' . $theme . '/bootstrap.min.css">
//     <link rel="stylesheet" type="text/css" href="/dist/cookieconsent/build/cookieconsent.min.css" />
//     <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Raleway">
//     <link rel="stylesheet" type="text/css" href="/includes/css/style.css">';
// }

function PortalCMS_CSS_floatingLabels()
{
    echo '<link rel="stylesheet" type="text/css" href="/includes/css/floating-labels.css">';
}

function PortalCMS_CSS_loadingAnimation()
{
    echo '<link rel="stylesheet" type="text/css" href="/includes/css/loadingAnimation.css">';
}

// function PortalCMS_CSS_calendar()
// {
//     echo '<link rel="stylesheet" type="text/css" href="/dist/merged/@fullcalendar/fullcalendar.min.css"/>';
// }

// function PortalCMS_JS_headJS()
// {
//     echo '<!--[if lt IE 9]>
//     <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
//     <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
//     <![endif]-->
//     <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
//     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
//     <script src="/includes/js/avantui.js"></script>
//     <script src="/dist/cookieconsent/build/cookieconsent.min.js" async></script>
//     <script src="/includes/js/cookieconsent.init.js" async></script>';
// }

// function PortalCMS_JS_moment()
// {
//     echo '<script src="/dist/moment/min/moment.min.js"></script>
//     <script src="/dist/moment/locale/nl.js"></script>';
// }

// function PortalCMS_JS_calendar()
// {
//     PortalCMS_JS_moment();
//     echo '<script src="/dist/merged/@fullcalendar/fullcalendar.min.js"></script>
//     <script src="/includes/js/calendar.js"></script>';
// }
