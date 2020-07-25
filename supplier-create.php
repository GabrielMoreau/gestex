<?php
// supplier-create.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

auth_or_login('supplier-create.php');
level_or_alert(3, 'Ajout d\'un fournisseur');

//validation d'un nouveau fournisseur

unset($erreur);
unset($nom);

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
	$title        = 'Erreur';
	$action       = 'supplier-add.php';
	$message_text =  $erreur;
	include_once('include/warning-box.php');
	exit();
}

if ($pdo = connect_db()) {
	//inscription
	$sql = 'INSERT INTO fournisseurs (nom, adresse, mail, www, tel, fax, contact, descr) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($nom, $adresse, $mail, $www, $phone, $fax, $contact, $descr));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$id_supplier = $pdo->lastInsertId();
	if (!$result) {
		// sql request !ok
		include_once('include/alert-data.php');
		exit();
	}

	$title        = 'R&eacute;sultat ajout fournisseur';
	$action       = 'supplier-list.php';
	$highlight    = $id_supplier;
	$message_text = 'Ajout du fournisseur '.$nom.' valid&eacute;';
	include_once('include/message-box.php');
	exit();
	} //end if connect
?>
