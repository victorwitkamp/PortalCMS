<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
Auth::checkAuthentication();
echo UserActivity::loadRecentUserActivity();
?>