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

$supplier_id = param_post('id_fourn');
$nom         = param_post('nom');
$adresse     = param_post('adresse');
$tel         = param_post('phone');
$fax         = param_post('fax');
$mail        = param_post('addr_mail');
$www         = param_post('www');
$contact     = param_post('contact');
$descr       = param_post('descr');
// variables ne pouvant pas etre nulles
if (empty($supplier_id))
	$erreur = 'Id non pr&eacute;cis&eacute;';
if (empty($nom))
	$erreur = 'Nom non pr&eacute;cis&eacute;';
if (empty($adresse))
	$erreur = 'Adresse non pr&eacute;cis&eacute;e';

if (!empty($erreur)) {
	//erreur
	$title         = 'Erreur fournisseur';
	$action        = 'supplier-add.php?id='.$supplier_id;
	$highlight     = $supplier_id;
	$message_text  = $erreur;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

// recupere les anciennes caracteristiques
$supplier_registered = get_supplier_all_by_id($pdo, $supplier_id);

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
	$err_msg = set_supplier_update($pdo, $supplier_id, $nom, $adresse, $tel, $fax, $mail, $www, $contact, $descr);
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
