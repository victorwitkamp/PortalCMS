<?php
if (SiteSetting::getStaticSiteSetting('site_description_type') === '2') {
    $request_headers = array();
    $request_headers[] = 'accept: (application/json|text/plain)';
    $ch = curl_init('https://sv443.net/jokeapi/category/Any');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $output = curl_exec($ch);
    $out = json_decode($output);
    print $out->{'setup'};
    echo '<br>';
    print $out->{'delivery'};
}
if (SiteSetting::getStaticSiteSetting('site_description_type') === '3') {
    $request_headers = array();
    $request_headers[] = 'accept: (text/plain)';
    $ch = curl_init('https://api.chucknorris.io/jokes/random');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $output = curl_exec($ch);
    echo $output;
}
