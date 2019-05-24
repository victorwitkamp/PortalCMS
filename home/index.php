<?php
/**
 * The homepage.
 *
 * Description of the homepage
 */
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
$pageName = Text::get('TITLE_HOME');
Auth::checkAuthentication();
require_once DIR_INCLUDES.'functions.php';
require_once DIR_INCLUDES.'head.php';
displayHeadCSS();
PortalCMS_JS_headJS();
?>
</head>

<body>
<?php require DIR_INCLUDES.'nav.php'; ?>
<main>
    <div class="content">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <img src='<?php echo SiteSettings::getStaticSiteSetting('site_logo'); ?>' alt='logo' width='120px' height='120px' />
                    </div>
                    <div class="col-sm-9">
                        <h1><?php echo SiteSettings::getStaticSiteSetting('site_name'); ?></h1>
                        <p class="lead">
                        <?php

                        if (SiteSettings::getStaticSiteSetting('site_description_type') === '1') {
                            echo SiteSettings::getStaticSiteSetting('site_description');
                        }
                        // if (SiteSettings::getStaticSiteSetting('site_description_type') === '2') {
                        //     $request_headers = array();
                        //     $request_headers[] = 'accept: (application/json|text/plain)';
                        //     $ch = curl_init('https://sv443.net/jokeapi/category/Any');
                        //     curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        //     curl_setopt($ch, CURLOPT_FAILONERROR, true);
                        //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        //     $output = curl_exec($ch);
                        //     $out = json_decode($output);
                        //     print $out->{'setup'};
                        //     echo '<br>';
                        //     print $out->{'delivery'};
                        // }
                        // if (SiteSettings::getStaticSiteSetting('site_description_type') === '3') {
                            // $request_headers = array();
                            // $request_headers[] = 'accept: (text/plain)';
                            // $ch = curl_init('https://api.chucknorris.io/jokes/random');
                            // curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            // curl_setopt($ch, CURLOPT_FAILONERROR, true);
                            // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            // $output = curl_exec($ch);
                            // echo $output;
                        // }
                        ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php
                $layout = SiteSettings::getStaticSiteSetting('site_layout');
                require 'layouts/'.$layout.'.php';
                ?>
            </div>
        </div>
    </div>
</main>
<?php
require DIR_INCLUDES.'footer.php';
?>
</body>

</html>
