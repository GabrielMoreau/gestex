<?php
// equipment-process.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('equipment-list.php');
level_or_alert(3, 'Ajout / Modification d’un appareil');

$logged_level = $_SESSION['logged_level'];

// validation d'un nouvel appareil

unset($erreur);

$equipment_id = param_post('equipment_id', 0);
$flag_new = true;
if ($equipment_id > 0)
	$flag_new = false;

$category_id      = param_post('category_id');
$name             = param_post('name');
$model            = param_post('model');
$feature          = param_post('feature');
$team_id          = param_post('team_id');
$supplier_id      = param_post('supplier_id');
$date_of_purchase = param_post('date_of_purchase');
$manager_user_id  = param_post('manager_user_id');
$repair_comment   = param_post('repair_comment');
$accessories      = param_post('accessories');
$inventory_number = empty_to_null(param_post('inventory_number'));
$barcode          = param_post('barcode', 0); // force int
$max_loan_days    = param_post('max_loan_days', 0);
$is_loanable      = param_post('is_loanable', 0);
//variables ne pouvant etre nulles
if (empty($category_id))
	$erreur = 'Catégorie non précisé';
if (empty($name))
	$erreur = 'Nom de l’appareil non précisé';
if (empty($model))
	$erreur = 'Modèle non précisé';
if (empty($team_id))
	$erreur = 'Équipe non précisé';
if (empty($manager_user_id))
	$erreur = 'Tech non précisé';
if (empty($supplier_id))
	$erreur = 'Fournisseur non précisé';
if (empty($date_of_purchase))
	$erreur = 'Achat non précisé';
if (empty($feature))
	$erreur = 'Caractéristique non précisé';

$notice = '';
if (isset($_FILES["notice"])) {
	$notice = $_FILES['notice']['name'];
	$notice = str_replace(' ', '_', $notice);
	$notice = str_replace('é', 'e', $notice);
	$notice = str_replace('è', 'e', $notice);
	$notice = str_replace('à', 'a', $notice);
}

if (!empty($erreur)) {
	//erreur
	$title         = 'Erreur';
	$action        = 'equipment-edit.php?id='.$equipment_id;
	$message_text  =  $erreur;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

if ($flag_new) { // new
	list($equipment_id, $err_msg) = set_equipment_new($pdo, $category_id, $name, $model, $feature, $team_id, $supplier_id, $date_of_purchase, $manager_user_id, $repair_comment, $accessories, $inventory_number, $notice, $barcode, $is_loanable, $max_loan_days);
	if ($err_msg != '') {
		$message_alert = ($logged_level > 3 ? $err_msg : '');
		include_once('include/alert-data.php');
		exit();
	}
	if ($notice != '') {
		$id_datasheet = set_datasheet_new($pdo, $equipment_id, 'notice');
		if (!$id_datasheet) {
			$title        = 'Erreur appareil';
			$action       = 'equipment-view.php?id='.$equipment_id;
			$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans l’ajout d’une notice à appareil (pas au format PDF ?)');
			include_once('include/message-box.php');
			exit();
		}
	}

	$title        = 'Résultat ajout d’un appareil';
	$action       = 'equipment-view.php?id='.$equipment_id;
	$message_text = 'Ajout d’un appareil '.$name.' validé';
	include_once('include/message-box.php');
	exit();
}

// modify
// récupère les anciennes caracteristiques
$equipment_selected = get_equipment_all_by_id($pdo, $equipment_id);

//modification app
$modif = false;
if (   ($category_id      != $equipment_selected['category_id'])
	|| ($name             != $equipment_selected['name'])
	|| ($model            != $equipment_selected['model'])
	|| ($feature          != $equipment_selected['feature'])
	|| ($manager_user_id  != $equipment_selected['manager_user_id'])
	|| ($team_id          != $equipment_selected['team_id'])
	|| ($supplier_id      != $equipment_selected['supplier_id'])
	|| ($date_of_purchase != $equipment_selected['date_of_purchase'])
	|| ($repair_comment   != $equipment_selected['repair_comment'])
	|| ($accessories      != $equipment_selected['accessories'])
	|| ($inventory_number != $equipment_selected['inventory_number'])
	|| ($notice           != $equipment_selected['notice'])
	|| ($barcode          != $equipment_selected['barcode'])
	|| ($is_loanable      != $equipment_selected['is_loanable'])
	|| ($max_loan_days    != $equipment_selected['max_loan_days']))
	$modif = true;

if ($modif) {
	if ($barcode == '')
		$barcode = 0;
	$err_msg = set_equipment_update($pdo, $equipment_id, $category_id, $name, $model, $feature, $team_id, $supplier_id, $date_of_purchase, $manager_user_id, $repair_comment, $accessories, $inventory_number, $notice, $barcode, $is_loanable, $max_loan_days);
	if ($err_msg != '') {
		$title        = 'Erreur appareil';
		$action       = 'equipment-view.php?id='.$equipment_id;
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la mise à jour de la fiche appareil');
		include_once('include/message-box.php');
		exit();
	}
	if ($notice != '') {
		$id_datasheet = set_datasheet_new($pdo, $equipment_id, 'notice');
		if (!$id_datasheet) {
			$title        = 'Erreur appareil';
			$action       = 'equipment-view.php?id='.$equipment_id;
			$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans l’ajout d’une notice à appareil (pas au format PDF ?)');
			include_once('include/message-box.php');
			exit();
		}
	}

	redirect('equipment-view.php?id='.$equipment_id);
}

$title        = 'Modification appareil';
$action       = 'equipment-view.php?id='.$equipment_id;
$message_text = 'Aucune modification à faire';
include_once('include/message-box.php');
exit();
?>
