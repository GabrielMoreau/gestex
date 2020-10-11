<?php
// team-process.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('team-list.php');
level_or_alert(3, 'Modification d\'une &eacute;quipe');

$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

// modification d'une equipe

unset($erreur);

$team_id = param_post('id_equip', 0); // -> modify
$flag_new = true;
if ($team_id > 0)
	$flag_new = false;

$nom     = param_post('nom');
$compte  = param_post('compte');
$chef    = param_post('chef');
$descr   = param_post('descr');
// variables ne pouvant etre nulles
if (empty($nom))
	$erreur = 'Nom d\'&eacute;quipe non pr&eacute;cis&eacute;';
if (empty($compte))
	$erreur = 'Compte non pr&eacute;cis&eacute;';

if (!empty($erreur)) {
	//erreur
	$title         = 'Erreur &eacute;quipe';
	$action        = 'team-edit.php?id='.$team_id;
	$highlight     = $team_id;
	$message_text  = $erreur;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

if ($flag_new) { // new
	list($team_id, $err_msg) = set_team_new($pdo, $nom, $descr, $compte, $chef);
	if ($err_msg != '') {
		$title        = 'Erreur &eacute;quipe';
		$action       = 'team-list.php';
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la cr&eacute;ation de l\'&eacute;quipe');
		include_once('include/message-box.php');
		exit();
	}

	$title        = 'Ajout &eacute;quipe';
	$action       = 'team-list.php?highlight='.$team_id;
	$highlight    = $team_id;
	$message_text = 'Ajout de l\'&eacute;quipe '.$nom.' valid&eacute;e';
	include_once('include/message-box.php');
	exit();
}

// modify
// recupere les anciennes caracteristiques
$team_selected = get_team_all_by_id($pdo, $team_id);

$modif = false;
if (   ($nom    != $team_selected['nom'])
	|| ($descr  != $team_selected['descr'])
	|| ($compte != $team_selected['compte'])
	|| ($chef   != $team_selected['chef']))
	$modif = true;

if ($modif) {
	$err_msg = set_team_update($pdo, $team_id, $nom, $descr, $compte, $chef);
	if ($err_msg != '') {
		$title        = 'Erreur &eacute;quipe';
		$action       = 'team-list.php?highlight='.$team_id;
		$highlight    = $team_id;
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la mise &agrave; jour de l\'&eacute;quipe');
		include_once('include/message-box.php');
		exit();
	}

	redirect('team-list.php?highlight='.$team_id.'#item'.$team_id);
}

$title        = 'Modification &eacute;quipe';
$action       = 'team-list.php?highlight='.$team_id;
$highlight    = $team_id;
$message_text = 'Aucune modification &agrave; faire';
include_once('include/message-box.php');
exit();
?>
