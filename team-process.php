<?php
// team-process.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('team-list.php');
level_or_alert(3, 'Modification d’une équipe');

$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

// modification d'une equipe

unset($err_msg);

$team_id = param_post('team_id', 0); // -> modify
$flag_new = true;
if ($team_id > 0)
	$flag_new = false;

$name            = param_post('name');
$accounting      = param_post('accounting');
$manager_user_id = param_post('manager_user_id');
$description     = param_post('description');
// variables ne pouvant etre nulles
if (empty($name))
	$err_msg = 'Nom d’équipe non précisé';
if (empty($accounting))
	$err_msg = 'Compte non précisé';

if (!empty($err_msg)) {
	//erreur
	$title         = 'Erreur équipe';
	$action        = 'team-edit.php?id='.$team_id;
	$highlight     = $team_id;
	$message_text  = $err_msg;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

if ($flag_new) { // new
	list($team_id, $err_msg) = set_team_new($pdo, $name, $description, $accounting, $manager_user_id);
	if ($err_msg != '') {
		$title        = 'Erreur équipe';
		$action       = 'team-list.php';
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la création de l’équipe');
		include_once('include/message-box.php');
		exit();
	}

	$title        = 'Ajout équipe';
	$action       = 'team-list.php?highlight='.$team_id;
	$highlight    = $team_id;
	$message_text = 'Ajout de l’équipe '.$name.' validée';
	include_once('include/message-box.php');
	exit();
}

// modify
// Récupère les anciennes caractéristiques
$team_selected = get_team_all_by_id($pdo, $team_id);

$modif = false;
if (   ($name            != $team_selected['name'])
	|| ($description     != $team_selected['description'])
	|| ($accounting      != $team_selected['accounting'])
	|| ($manager_user_id != $team_selected['manager_user_id']))
	$modif = true;

if ($modif) {
	$err_msg = set_team_update($pdo, $team_id, $name, $description, $accounting, $manager_user_id);
	if ($err_msg != '') {
		$title        = 'Erreur équipe';
		$action       = 'team-list.php?highlight='.$team_id;
		$highlight    = $team_id;
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la mise à jour de l’équipe');
		include_once('include/message-box.php');
		exit();
	}

	redirect('team-list.php?highlight='.$team_id.'#item'.$team_id);
}

$title        = 'Modification équipe';
$action       = 'team-list.php?highlight='.$team_id;
$highlight    = $team_id;
$message_text = 'Aucune modification à faire';
include_once('include/message-box.php');
exit();
?>
