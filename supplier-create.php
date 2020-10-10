<?php
// supplier-create.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

// Authenticate
auth_or_login('supplier-create.php');
level_or_alert(3, 'Ajout d\'un fournisseur');

// creation d'un fournisseur

unset($erreur);

//variables ne pouvant etre nulles
$nom = param_post('nom');
if (empty($nom))
	$erreur = 'Nom du fournisseur non pr&eacute;cis&eacute;';

$adresse = param_post('adresse');
if (empty($adresse))
	$erreur = 'Adresse non pr&eacute;cis&eacute;e';

//variables pouvant etre nulles
$mail    = param_post('addr_mail');
$www     = param_post('www');
$phone   = param_post('phone');
$fax     = param_post('fax');
$contact = param_post('contact');
$descr   = param_post('descr');

if (!empty($erreur)) {
	//erreur
	$title         = 'Erreur';
	$action        = 'supplier-add.php';
	$message_text  =  $erreur;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

//inscription
list($id_supplier, $err_msg) = set_supplier_new($pdo, $nom, $adresse, $tel, $fax, $mail, $www, $contact, $descr);
if ($err_msg != '') {
	$message_alert = ($logged_level > 3 ? $err_msg : '');
	include_once('include/alert-data.php');
	exit();
}

$title        = 'R&eacute;sultat ajout fournisseur';
$action       = 'supplier-list.php';
$highlight    = $id_supplier;
$message_text = 'Ajout du fournisseur '.$nom.' valid&eacute;';
include_once('include/message-box.php');
exit();
?>
