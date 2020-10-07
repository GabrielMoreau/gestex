<?php
// logout.php
$web_page = true;

require_once('module/auth-functions.php');
require_once('module/html-functions.php');

logout();

redirect('./');
?>
