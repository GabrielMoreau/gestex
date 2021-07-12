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

$description    = param_post('description');
$equipment_id   = param_post('equipment');
$supplier_id    = param_post('company');
$notice         = param_post('notice');
$date           = param_post('date');

$pdo = connect_db_or_alert();

var_dump($description);
var_dump($equipment_id);
var_dump($supplier_id);
var_dump($date);
$res = set_new_intervention($pdo, $description, $supplier_id, $equipment_id, $date);
?>