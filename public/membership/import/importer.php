<?php

use PortalCMS\Core\Authentication\Authentication;
use PortalCMS\Core\Database\DB;

require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Authentication::checkAuthentication();
require_once DIR_INCLUDES.'functions.php';
$jaarlidmaatschap = $_POST["jaarlidmaatschap"];
if (isset($_POST["submit_file"])) {
    if ($_FILES['photoname']['error'] > 0) {
        echo $_FILES['photoname']['error'];
        die;
    }
    $file = $_FILES["file"]["tmp_name"];
    $file_open = fopen($file, "r");
    while (($csv = fgetcsv($file_open, 1000, ",")) !== false) {
        $sql = "INSERT INTO members
        (jaarlidmaatschap, voornaam, achternaam, emailadres,
        iban, machtigingskenmerk, incasso_gelukt, opmerking)
        VALUES (?,?,?,?,?,?,?,?)";
        $stmt = DB::conn()->prepare($sql);
        $voornaam = $csv[0];
        $achternaam = $csv[1];
        $emailadres = $csv[2];
        $iban = $csv[3];
        $machtigingskenmerk = $csv[4];
        $incasso_gelukt = $csv[5];
        $opmerking = $csv[6];
        $stmt->execute([$jaarlidmaatschap, $voornaam, $achternaam, $emailadres, $iban, $machtigingskenmerk, $incasso_gelukt, $opmerking]);

    }
}
