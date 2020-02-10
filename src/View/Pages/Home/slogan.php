<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

use PortalCMS\Core\Config\SiteSetting;

if (SiteSetting::get('site_description_type') === '2') {
    $request_headers = [];
    $request_headers[] = 'accept: (application/json|text/plain)';
    $ch = curl_init('https://sv443.net/jokeapi/category/Any');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $output = curl_exec($ch);
    $out = json_decode($output, true);
    if (!empty($out->{'setup'}) && !empty($out->{'delivery'})) {
        print $out->{'setup'};
        echo '<br>';
        print $out->{'delivery'};
    }
}
if (SiteSetting::get('site_description_type') === '3') {
    $request_headers = [];
    $request_headers[] = 'accept: (text/plain)';
    $ch = curl_init('https://api.chucknorris.io/jokes/random');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $output = curl_exec($ch);
    echo $output;
}
