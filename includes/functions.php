<?php
/**
 * PortalCMS Framework: Functions
 *
 * Functions used in the PortalCMS Framework. Should be included on all pages.
 *
 * @package PortalCMS
 * @link    https://PortalCMS.victorwitkamp.nl/
 */

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_tempusdominus()
{
    PortalCMS_JS_moment();
    echo '
    <script src="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_CSS_tempusdominus()
{
    echo '
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_JQuery_Simple_validator()
{
    echo '
    <script src="/includes/js/jquery-simple-validator.nl.js"></script>
    <link rel="stylesheet" type="text/css" href="/includes/css/jquery-simple-validator.css">
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_CSS_dataTables()
{
    echo '
    <!-- <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_dataTables()
{
    echo '
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    ';
}
/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_Init_dataTables()
{
    echo '
    <script src="/includes/js/init.datatables.js"  class="init"></script>
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_Datepicker_membership()
{
    echo '
    <script src="/includes/js/datepicker_membership.js"></script>
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_Datepicker_event()
{
    echo '
    <script src="/includes/js/datepicker_event.js"></script>
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function displayHeadCSS()
{
    $theme = SiteSetting::getStaticSiteSetting('site_theme');
    echo '
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="//bootswatch.com/4/'.$theme.'/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" type="text/css" href="/includes/css/style.css">
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_CSS_floatingLabels()
{
    echo '
    <link rel="stylesheet" type="text/css" href="/includes/css/floating-labels.css">
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_CSS_loadingAnimation()
{
    echo '
    <link rel="stylesheet" type="text/css" href="/includes/css/loadingAnimation.css">
    ';
}

function PortalCMS_CSS_loadingAnimation2()
{
    echo '
    <link rel="stylesheet" type="text/css" href="/includes/css/loadingAnimation2.css">
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_CSS_calendar()
{
    // echo '
    // <link rel="stylesheet" href="//unpkg.com/@fullcalendar/core/main.min.css"/>
    // <link rel="stylesheet" href="//unpkg.com/@fullcalendar/list/main.min.css"/>
    // <link rel="stylesheet" href="//unpkg.com/@fullcalendar/bootstrap/main.min.css"/>
    // <link rel="stylesheet" href="//unpkg.com/@fullcalendar/daygrid/main.min.css"/>
    // ';
    echo '
    <link rel="stylesheet" href="/node_modules/@fullcalendar/core/main.min.css"/>
    <link rel="stylesheet" href="/node_modules/@fullcalendar/list/main.min.css"/>
    <link rel="stylesheet" href="/node_modules/@fullcalendar/bootstrap/main.min.css"/>
    <link rel="stylesheet" href="/node_modules/@fullcalendar/daygrid/main.min.css"/>
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_headJS()
{
    PortalCMS_JS_basic();
    PortalCMS_JS_cookieConsent();
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_basic()
{
    PortalCMS_JS_IE9();
    PortalCMS_JS_jQuery();
    PortalCMS_JS_bootstrap();
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_IE9()
{
    echo '
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_jQuery()
{
    echo '
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_bootstrap()
{
    echo '
    <script src="//bootswatch.com/_vendor/popper.js/dist/umd/popper.min.js"></script>
    <script src="//bootswatch.com/_vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_cookieConsent()
{
    echo '
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_moment()
{
    // echo '
    // <script src="//unpkg.com/moment@2.24.0/min/moment.min.js"></script>
    // <script src="//unpkg.com/moment@2.24.0/locale/nl.js"></script>
    // ';
    echo '
    <script src="/node_modules/moment/min/moment.min.js"></script>
    <script src="/node_modules/moment/locale/nl.js"></script>
    ';
}

/**
 * Undocumented function
 *
 * @return void
 */
function PortalCMS_JS_calendar()
{
    PortalCMS_JS_moment();
    // echo '
    // <script src="//unpkg.com/@fullcalendar/core/main.min.js"></script>
    // <script src="//unpkg.com/@fullcalendar/list/main.min.js"></script>
    // <script src="//unpkg.com/@fullcalendar/bootstrap/main.min.js"></script>
    // <script src="//unpkg.com/@fullcalendar/daygrid/main.min.js"></script>
    // <script src="//unpkg.com/@fullcalendar/interaction/main.min.js"></script>
    // <script src="//unpkg.com/@fullcalendar/core/locales/nl.js"></script>
    // <script src="/includes/js/calendar.js"></script>
    // ';
    echo '
    <script src="/node_modules/@fullcalendar/core/main.min.js"></script>
    <script src="/node_modules/@fullcalendar/list/main.min.js"></script>
    <script src="/node_modules/@fullcalendar/bootstrap/main.min.js"></script>
    <script src="/node_modules/@fullcalendar/daygrid/main.min.js"></script>
    <script src="/node_modules/@fullcalendar/interaction/main.min.js"></script>
    <script src="/node_modules/@fullcalendar/core/locales/nl.js"></script>
    <script src="/includes/js/calendar.js"></script>
    ';
}
