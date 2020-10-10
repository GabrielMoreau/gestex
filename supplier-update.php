<?php
// supplier-update.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('supplier-update.php');
level_or_alert(3, 'Modification d\'un fournisseur');

$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

// modification d'un fournisseur

unset($erreur);

// variables ne pouvant pas etre nulles
$id_supplier = param_post('id_fourn');
if (empty($id_supplier))
	$erreur = 'Id non pr&eacute;cis&eacute;';

$nom = param_post('nom');
if (empty($nom))
	$erreur = 'Nom non pr&eacute;cis&eacute;';

$adresse = param_post('adresse');
if (empty($adresse))
	$erreur = 'Adresse non pr&eacute;cis&eacute;';

// variables pouvant etre nulles
$tel     = param_post('phone');
$fax     = param_post('fax');
$mail    = param_post('addr_mail');
$www     = param_post('www');
$contact = param_post('contact');
$descr   = param_post('descr');

if (!empty($erreur)) {
	//erreur
	$title        = 'Erreur fournisseur';
	$action       = 'supplier-list.php?highlight='.$id_supplier;
	$highlight    = $id_supplier;
	$message_text = $erreur;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

// recupere les anciennes caracteristiques
$supplier_registered = get_supplier_all_by_id($pdo, $id_supplier);

$modif = 0;
if (   ($nom     != $supplier_registered['nom'])
	|| ($adresse != $supplier_registered['adresse'])
	|| ($tel     != $supplier_registered['tel'])
	|| ($fax     != $supplier_registered['fax'])
	|| ($mail    != $supplier_registered['mail'])
	|| ($www     != $supplier_registered['www'])
	|| ($contact != $supplier_registered['contact'])
	|| ($descr   != $supplier_registered['descr']))
	$modif = 1;

if ($modif != 0) {
	$err_msg = set_supplier_update($pdo, $id_supplier, $nom, $adresse, $tel, $fax, $mail, $www, $contact, $descr);
	if ($err_msg != '') {
		$title        = 'Erreur fournisseur';
		$action       = 'supplier-list.php?highlight='.$id_supplier;
		$highlight    = $id_supplier;
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la mise &agrave; jour de la fiche fournisseur');
		include_once('include/message-box.php');
		exit();
	}

	redirect('supplier-list.php?highlight='.$id_supplier.'#item'.$id_supplier);
}

$title        = 'Modification fournisseur';
$action       = 'supplier-list.php?highlight='.$id_supplier;
$highlight    = $id_supplier;
$message_text = 'Aucune modification &agrave; faire';
include_once('include/message-box.php');
exit();

?>
