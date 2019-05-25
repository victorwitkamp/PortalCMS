<?php
require $_SERVER["DOCUMENT_ROOT"]."/Init.php";
//  var_dump($_SESSION);
Session::destroy();
 var_dump($_SESSION);
$_SESSION['response'][] = array("status"=>"error", "message"=>"test");

 var_dump($_SESSION['response']);
 ?>