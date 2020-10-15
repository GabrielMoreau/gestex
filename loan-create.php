<?php
// loan-create.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('loan-list.php');
level_or_alert(3, 'Ajout ou modification d\'un pr&ecirc;t');

// validation d'un pret

unset($erreur);

$loan_id      = param_post('id_loan', 0); // modify
$equipment_id = param_post('id_equipment');
$team_id      = param_post('equipe');
$date_emprunt = param_post('emprunt');
$date_retour  = param_post('retour');
$commentaire  = param_post('commentaire');
//variables ne pouvant etre nulles
if (empty($equipment_id))
	$erreur = 'Nom de l\'appareil non pr&eacute;cis&eacute;';
if (empty($team_id))
	$erreur = '&Eacute;quipe non pr&eacute;cis&eacute;';
if (empty($date_emprunt))
	$erreur = 'Date d\'emprunt non pr&eacute;cis&eacute;';

$flag_new = true;
if ($loan_id > 0)
	$flag_new = false;

if (!empty($erreur)) {
	//erreur
	$title         = 'Erreur sur l\'emprunt';
	$action        = 'loan-edit.php?id='.$loan_id;
	if ($flag_new == true)
		$action    = 'loan-edit.php?equipment='.$equipment_id;
	$message_text  = $erreur;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

if ($flag_new == true) {
	$loan = get_loan_short_by_id_equipment($pdo, $equipment_id);
	if (!empty($loan)) {
		$title        = 'Erreur concernant un nouvel emprunt';
		$action       = 'equipment-view.php?id='.$equipment_id;
		$message_text = 'L\'appareil est d&eacute;j&agrave; emprunt&eacute;';
		include_once('include/warning-box.php');
		exit();
	}

	// inscription
	$loan_id = set_loan_new($pdo, $equipment_id, $team_id, $date_emprunt, $date_retour, $commentaire);
	$message_text = 'Ajout du pr&ecirc;t sur l\'appareil '.$equipment_id.' valid&eacute;<br />';
}
else {
	set_loan_update($pdo, $loan_id, $equipment_id, $team_id, $date_emprunt, $date_retour, $commentaire);
	$message_text = 'Mise &agrave; jour du pr&ecirc;t sur l\'appareil '.$equipment_id.' valid&eacute;<br />';
}

$title     = 'R&eacute;sultat demande d\'emprunt';
$action    = 'equipment-view.php?id='.$equipment_id;
$highlight = '';
include_once('include/message-box.php');
exit();
?>
