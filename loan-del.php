<?php
// loan-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('loan-list.php');
level_or_alert(3, 'Suppression d\'un pr&ecirc;t');

$loan_id = param_post_or_get('id', 0);
$valid   = param_post('ok', 'no');

if ($loan_id == 0 || $valid == 'cancel')
	redirect('loan-list.php');

if ($valid == 'edit')
	redirect('loan-edit.php?id='.$loan_id);

$pdo = connect_db_or_alert();

$loan_selected  = get_loan_all_by_id($pdo, $loan_id);
$equipment = get_equipment_listshort_by_id($pdo, $loan_selected['nom']);
$equipment_name = $equipment["nom"];
$equipment_id = $equipment["id"];


if ($valid == 'yes') {
	/* $iostat = del_loan_by_id($pdo, $loan_id); */
	$str_type = "du pret";
	if (get_loan_all_by_id($pdo, $loan_id)["status"] == STATUS_LOAN_RESERVED) {
		$iostat = del_loan_by_id($pdo, $loan_id);
		$str_type = "de la reservation";
	} else {
		$iostat = set_loan_to_returned($pdo, $loan_id);
	}
	if ($iostat) // ca a marche
		redirect('equipment-view.php?id='.$equipment_id);
	$message_alert = 'Erreur dans la suppression '.$type.' : '.$loan_id;
	include_once('include/alert-data.php');
	exit();
	}

// $loan_id
// $equipment_name
include_once('include/loan-del.php');
exit();
?>
