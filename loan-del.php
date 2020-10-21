<?php
// loan-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('loan-list.php');
level_or_alert(3, 'Suppression d\'un pr&ecirc;t');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$loan_id = param_post_or_get('id', 0);
$valid   = param_post('ok', 'no');

if ($loan_id == 0 || $valid == 'cancel')
	redirect('loan-list.php');

if ($valid == 'edit')
	redirect('loan-edit.php?id='.$loan_id);

$pdo = connect_db_or_alert();

if ($valid == 'yes') {
	$flag = del_loan_by_id($pdo, $loan_id);
	if ($flag) // ca a marche
		redirect('loan-list.php');
	$message_alert = 'Erreur dans la suppression du pr&ecirc;t : '.$loan_id;
	include_once('include/alert-data.php');
	exit();
	}

$loan_selected  = get_loan_all_by_id($pdo, $loan_id);
$equipment_name = get_equipment_by_id($pdo, $loan_selected['nom'])['nom'];

// $loan_id
// $equipment_name
include_once('include/loan-del.php');
exit();
?>
