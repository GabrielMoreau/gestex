<?php
// supplier-create.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('supplier-create.php');
level_or_alert(3, 'Ajout / Modificationd\'un fournisseur');

$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

// creation ou modification d'un fournisseur

unset($erreur);

$supplier_id = param_post('id_fourn', 0);
$flag_new = true;
if ($supplier_id > 0)
	$flag_new = false;

$nom     = param_post('nom');
$adresse = param_post('adresse');
$www     = param_post('www');
$phone   = param_post('phone');
$fax     = param_post('fax');
$email   = param_post('addr_mail');
$contact = param_post('contact');
$descr   = param_post('descr');
//variables ne pouvant etre nulles
if (empty($nom))
	$erreur = 'Nom du fournisseur non pr&eacute;cis&eacute;';
if (empty($adresse))
	$erreur = 'Adresse non pr&eacute;cis&eacute;e';

if (!empty($erreur)) {
	//erreur
	$title         = 'Erreur';
	$action        = 'supplier-edit.php';
	$message_text  =  $erreur;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

if ($flag_new) { // new
	list($supplier_id, $err_msg) = set_supplier_new($pdo, $nom, $adresse, $phone, $fax, $email, $www, $contact, $descr);
	if ($err_msg != '') {
		$message_alert = ($logged_level > 3 ? $err_msg : '');
		include_once('include/alert-data.php');
		exit();
	}

	$title        = 'R&eacute;sultat ajout fournisseur';
	$action       = 'supplier-list.php?highlight='.$supplier_id;
	$highlight    = $supplier_id;
	$message_text = 'Ajout du fournisseur '.$nom.' valid&eacute;';
	include_once('include/message-box.php');
	exit();
}

// modify
// recupere les anciennes caracteristiques
$supplier_selected = get_supplier_all_by_id($pdo, $supplier_id);

$modif = false;
if (   ($nom     != $supplier_selected['nom'])
	|| ($adresse != $supplier_selected['adresse'])
	|| ($phone   != $supplier_selected['tel'])
	|| ($fax     != $supplier_selected['fax'])
	|| ($email   != $supplier_selected['mail'])
	|| ($www     != $supplier_selected['www'])
	|| ($contact != $supplier_selected['contact'])
	|| ($descr   != $supplier_selected['descr']))
	$modif = true;

if ($modif) {
	$err_msg = set_supplier_update($pdo, $supplier_id, $nom, $adresse, $phone, $fax, $email, $www, $contact, $descr);
	if ($err_msg != '') {
		$title        = 'Erreur fournisseur';
		$action       = 'supplier-list.php?highlight='.$supplier_id;
		$highlight    = $supplier_id;
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la mise &agrave; jour de la fiche fournisseur');
		include_once('include/message-box.php');
		exit();
	}

	redirect('supplier-list.php?highlight='.$supplier_id.'#item'.$supplier_id);
}

$title        = 'Modification fournisseur';
$action       = 'supplier-list.php?highlight='.$supplier_id;
$highlight    = $supplier_id;
$message_text = 'Aucune modification &agrave; faire';
include_once('include/message-box.php');
exit();
?>
