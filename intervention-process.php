<?php
// equipment-process.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('equipment-list.php');
level_or_alert(3, 'Ajout / Modification d\'une intervention');

$logged_level = $_SESSION['logged_level'];

unset($erreur);

$desciption = param_post('description');
$company    = param_post('company');
$notice     = param_post('notice');

?>