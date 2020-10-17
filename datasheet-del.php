<?php
// datasheet-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('equipment-list.php');
level_or_alert(3, 'Suppression d\'une notice');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$datasheet_id = param_post_or_get('id', 0);
$valid        = param_post('ok', 'no');

$equipment_id = 0;
if ($datasheet_id > 0) {
	$pdo = connect_db_or_alert();

	$datasheet_selected = get_datasheet_all_by_id($pdo, $datasheet_id);
	$equipment_id       = $datasheet_selected['id_equipment'];
}

if ($datasheet_id == 0 || $equipment_id == 0 || $valid == 'cancel') {
	if ($equipment_id > 0)
		redirect('equipment-view.php?id='.$equipment_id);
	redirect('equipment-list.php');
}

if ($valid == 'yes') {
	$flag = del_datasheet($pdo, $datasheet_id);
	if ($flag) // ca a marche
		redirect('equipment-view.php?id='.$equipment_id);
	$message_alert = 'Erreur dans la suppression de la notice : '.$datasheet_id;
	include_once('include/alert-data.php');
	exit();
	}

$datasheet_pathname = $datasheet_selected['pathname'];
$equipment_name     = get_equipment_by_id($pdo, $equipment_id )['nom'];

// $datasheet_id
// $datasheet_pathname
// $equipment_id
// $equipment_name
include_once('include/datasheet-del.php');
exit();
?>
