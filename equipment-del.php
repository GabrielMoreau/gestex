<?php
// equipment-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('equipment-list.php');
level_or_alert(3, 'Suppression d\'un appareil et de ses notices associ&eacute;es');

$equipment_id = param_post_or_get('id', 0);
$valid        = param_post('ok', 'no');

if ($equipment_id == 0 || $valid == 'cancel')
	redirect('equipment-list.php');

$pdo = connect_db_or_alert();
$equipment_name = get_equipment_by_id($pdo, $equipment_id)['nom'];

if ($valid == 'yes') {
	$flag = del_equipment_by_id($pdo, $equipment_id);
	if ($flag) // ca a marche
		redirect('equipment-list.php');
	$message_alert = 'Erreur dans la suppression d\'un appareil et des notices associ&eacute;es : '.$equipment_name.' (#'.$equipment_id.')';
	include_once('include/alert-data.php');
	exit();
}

// $equipment_id
// $equipment_name
include_once('include/equipment-del.php');
exit();
?>
