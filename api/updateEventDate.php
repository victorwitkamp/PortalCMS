<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
if (Event::updateDate($_POST['id'], $_POST['title'], $_POST['start'], $_POST['end'])) {
    return TRUE;
}
?>