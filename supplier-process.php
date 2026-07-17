<?php
// supplier-process.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('supplier-process.php');
level_or_alert(3, 'Ajout / Modification d’un fournisseur');

$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

// création ou modification d'un fournisseur

unset($err_msg);

$supplier_id = (int)param_post('supplier_id', 0);
$flag_new = true;
if ($supplier_id > 0)
	$flag_new = false;

$name        = param_post('name');
$address     = param_post('address');
$www         = param_post('www');
$phone       = param_post('phone');
$fax         = param_post('fax');
$email       = param_post('email');
$contact     = param_post('contact');
$description = param_post('description');
// variables ne pouvant etre nulles
if (empty($name))
	$err_msg = 'Nom du fournisseur non précisé';
if (empty($address))
	$err_msg = 'Adresse non précisée';

if (!empty($err_msg)) {
	// erreur
	$title         = 'Erreur';
	$action        = 'supplier-edit.php?supplier_id='.$supplier_id;
	$message_text  =  $err_msg;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

if ($flag_new) { // new
	list($supplier_id, $err_msg) = set_supplier_new($pdo, $name, $address, $phone, $fax, $email, $www, $contact, $description);
	if ($err_msg != '') {
		$message_alert = ($logged_level > 3 ? $err_msg : '');
		include_once('include/alert-data.php');
		exit();
	}

	$title        = 'Résultat ajout fournisseur';
	$action       = 'supplier-list.php?highlight='.$supplier_id;
	$highlight    = $supplier_id;
	$message_text = 'Ajout du fournisseur '.$name.' validé';
	include_once('include/message-box.php');
	exit();
}

// modify
// Récupère les anciennes caractéristiques
$supplier_selected = get_supplier_all_by_id($pdo, $supplier_id);

$modif = false;
if (   ($name        != $supplier_selected['name'])
	|| ($address     != $supplier_selected['address'])
	|| ($phone       != $supplier_selected['phone'])
	|| ($fax         != $supplier_selected['fax'])
	|| ($email       != $supplier_selected['email'])
	|| ($www         != $supplier_selected['www'])
	|| ($contact     != $supplier_selected['contact'])
	|| ($description != $supplier_selected['description']))
	$modif = true;

if ($modif) {
	$err_msg = set_supplier_update($pdo, $supplier_id, $name, $address, $phone, $fax, $email, $www, $contact, $description);
	if ($err_msg != '') {
		$title        = 'Erreur fournisseur';
		$action       = 'supplier-list.php?highlight='.$supplier_id;
		$highlight    = $supplier_id;
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la mise à jour de la fiche fournisseur');
		include_once('include/message-box.php');
		exit();
	}

	redirect('supplier-list.php?highlight='.$supplier_id.'#item'.$supplier_id);
}

$title        = 'Modification fournisseur';
$action       = 'supplier-list.php?highlight='.$supplier_id;
$highlight    = $supplier_id;
$message_text = 'Aucune modification à faire';
include_once('include/message-box.php');
exit();
?>
