<?php
// equipment-process.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('equipment-list.php');
level_or_alert(3, 'Ajout / Modification d\'un appareil');

$logged_level = $_SESSION['logged_level'];

// validation d'un nouvel appareil

unset($erreur);

$equipment_id = param_post('id_equipment', 0);
$flag_new = true;
if ($equipment_id > 0)
	$flag_new = false;

$categorie   = param_post('categorie');
$nom         = param_post('nom');
$modele      = param_post('modele');
$feature     = param_post('gamme');
$equipe      = param_post('equipe');
$fourn       = param_post('fourn');
$achat       = param_post('achat');
$tech        = param_post('tech');
$reparation  = param_post('reparation');
$accessoires = param_post('accessoires');
$inventaire  = param_post('inventaire');
$barcode     = param_post('barcode');
$max_day     = param_post('max_day', 0);
$loanable    = param_post('loanable');
//variables ne pouvant etre nulles
if (empty($categorie))
	$erreur = 'Cat&eacute;gorie non pr&eacute;cis&eacute;';
if (empty($nom))
	$erreur = 'Nom de l\'appareil non pr&eacute;cis&eacute;';
if (empty($modele))
	$erreur = 'Mod&egrave;le non pr&eacute;cis&eacute;';
if (empty($equipe))
	$erreur = '&Eacute;quipe non pr&eacute;cis&eacute;';
if (empty($tech))
	$erreur = 'Tech non pr&eacute;cis&eacute;';
if (empty($fourn))
	$erreur = 'Fournisseur non pr&eacute;cis&eacute;';
if (empty($achat))
	$erreur = 'Achat non pr&eacute;cis&eacute;';
if (empty($feature))
	$erreur = 'Caract&eacute;ristique non pr&eacute;cis&eacute;';

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
	list($equipment_id, $err_msg) = set_equipment_new($pdo, $categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable, $max_day);
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
			$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans l\'ajout d\'une notice &agrave; appareil (pas au format PDF ?)');
			include_once('include/message-box.php');
			exit();
		}
	}

	$title        = 'R&eacute;sultat ajout d\'un appareil';
	$action       = 'equipment-view.php?id='.$equipment_id;
	$message_text = 'Ajout d\'un appareil '.$nom.' valid&eacute;';
	include_once('include/message-box.php');
	exit();
}

// modify
// recupere les anciennes caracteristiques
$equipment_selected = get_equipment_all_by_id($pdo, $equipment_id);

//modification app
$modif = false;
if (   ($categorie   != $equipment_selected['categorie'])
	|| ($nom         != $equipment_selected['nom'])
	|| ($modele      != $equipment_selected['modele'])
	|| ($feature     != $equipment_selected['gamme'])
	|| ($tech        != $equipment_selected['responsable'])
	|| ($equipe      != $equipment_selected['equipe'])
	|| ($fourn       != $equipment_selected['fournisseur'])
	|| ($achat       != $equipment_selected['achat'])
	|| ($reparation  != $equipment_selected['reparation'])
	|| ($accessoires != $equipment_selected['accessoires'])
	|| ($inventaire  != $equipment_selected['inventaire'])
	|| ($notice      != $equipment_selected['notice'])
	|| ($barcode     != $equipment_selected['barcode'])
	|| ($loanable    != $equipment_selected['loanable'])
	|| ($max_day     != $equipment_selected['max_day']))
	$modif = true;

if ($modif) {
	if ($barcode == '')
		$barcode = 0;
	$err_msg = set_equipment_update($pdo, $equipment_id, $categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable, $max_day);
	if ($err_msg != '') {
		$title        = 'Erreur appareil';
		$action       = 'equipment-view.php?id='.$equipment_id;
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la mise &agrave; jour de la fiche appareil');
		include_once('include/message-box.php');
		exit();
	}
	if ($notice != '') {
		$id_datasheet = set_datasheet_new($pdo, $equipment_id, 'notice');
		if (!$id_datasheet) {
			$title        = 'Erreur appareil';
			$action       = 'equipment-view.php?id='.$equipment_id;
			$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans l\'ajout d\'une notice &agrave; appareil (pas au format PDF ?)');
			include_once('include/message-box.php');
			exit();
		}
	}

	redirect('equipment-view.php?id='.$equipment_id);
}

$title        = 'Modification appareil';
$action       = 'equipment-view.php?id='.$equipment_id;
$message_text = 'Aucune modification &agrave; faire';
include_once('include/message-box.php');
exit();
?>
