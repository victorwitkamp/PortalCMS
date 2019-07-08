<?php
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
    echo '<script src="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>';
}

function PortalCMS_CSS_tempusdominus()
{
    echo '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />';
}

function PortalCMS_JS_JQuery_Simple_validator()
{
    echo '<script src="/includes/js/jquery-simple-validator.nl.js"></script>
    <link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css">';
}

function PortalCMS_CSS_dataTables()
{
    echo '<!-- <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">';
}

function PortalCMS_JS_dataTables()
{
    echo '<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>';
}

function PortalCMS_JS_Init_dataTables()
{
    echo '<script src="/includes/js/init.datatables.js"  class="init"></script>';
}

function PortalCMS_JS_Datepicker_membership()
{
    echo '<script src="/includes/js/datepicker_membership.js"></script>';
}

function PortalCMS_JS_Datepicker_event()
{
    echo '<script src="/includes/js/datepicker_event.js"></script>';
}

function displayHeadCSS()
{
    $theme = SiteSetting::getStaticSiteSetting('site_theme');
    echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">';
    echo '<link rel="stylesheet" type="text/css" href="/node_modules/@fortawesome/fontawesome-free/css/all.min.css">';
    echo '<link rel="stylesheet" type="text/css" href="/node_modules/bootswatch/dist/'.$theme.'/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" type="text/css" href="/includes/css/style.css">';
}

function PortalCMS_CSS_floatingLabels()
{
    echo '<link rel="stylesheet" type="text/css" href="/includes/css/floating-labels.css">';
}

function PortalCMS_CSS_loadingAnimation()
{
    echo '<link rel="stylesheet" type="text/css" href="/includes/css/loadingAnimation.css">';
}

function PortalCMS_CSS_calendar()
{
    echo '<link rel="stylesheet" type="text/css" href="/node_modules/@fullcalendar/core/main.min.css"/>
    <link rel="stylesheet" type="text/css" href="/node_modules/@fullcalendar/list/main.min.css"/>
    <link rel="stylesheet" type="text/css" href="/node_modules/@fullcalendar/bootstrap/main.min.css"/>
    <link rel="stylesheet" type="text/css" href="/node_modules/@fullcalendar/daygrid/main.min.css"/>';
}

function PortalCMS_JS_headJS()
{
    echo '<!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>';
}

function PortalCMS_JS_moment()
{
    echo '<script src="/node_modules/moment/min/moment.min.js"></script>
    <script src="/node_modules/moment/locale/nl.js"></script>';
}

function PortalCMS_JS_calendar()
{
    PortalCMS_JS_moment();
    echo '<script src="/node_modules/@fullcalendar/core/main.min.js"></script>
    <script src="/node_modules/@fullcalendar/list/main.min.js"></script>
    <script src="/node_modules/@fullcalendar/bootstrap/main.min.js"></script>
    <script src="/node_modules/@fullcalendar/daygrid/main.min.js"></script>
    <script src="/node_modules/@fullcalendar/interaction/main.min.js"></script>
    <script src="/node_modules/@fullcalendar/core/locales/nl.js"></script>
    <script src="/includes/js/calendar.js"></script>';
}
