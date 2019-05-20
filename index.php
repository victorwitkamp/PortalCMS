<?php
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/Init.php")) {
    require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
    LoginController::index();
} else {
    echo 'Configure Init.php first!';
}
