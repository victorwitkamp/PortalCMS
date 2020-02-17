<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Config\SiteSetting;

if (SiteSetting::get('site_description_type') === '1') {
    echo SiteSetting::get('site_description');
}

if (SiteSetting::get('site_description_type') === '2') {
    $request_headers = [];
    $request_headers[] = 'accept: (application/json|text/plain)';
    $url = 'https://sv443.net/jokeapi/v2/joke/Any?format=json';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_VERBOSE, true);
    $verbose = fopen('php://temp', 'wb+');
    curl_setopt($curl, CURLOPT_STDERR, $verbose);
    //    curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    $result = curl_exec($curl);
    if ($result === false) {
        printf(
            "cUrl error (#%d): %s<br>\n",
            curl_errno($curl),
            htmlspecialchars(curl_error($curl))
        );
    }
    $debug = true;
    if (!empty($result)) {
        $out = json_decode($result, true);
        if ($out['type'] === 'twopart') {
            echo $out['setup'] . '<br>' . $out['delivery'];
            $debug = false;
        } elseif (!empty($out['joke'])) {
            echo $out['joke'];
            $debug = false;
        }
    }
    if ($debug === true) {
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
    }

}
if (SiteSetting::get('site_description_type') === '3') {
    $request_headers = [];
    $request_headers[] = 'accept: (text/plain)';
    $curl = curl_init('https://api.chucknorris.io/jokes/random');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    $output = curl_exec($curl);
    echo $output;
}
