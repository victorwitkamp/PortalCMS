<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
if (LoginModel::isUserLoggedIn()) {
    LoginController::logout();
}
