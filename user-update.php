<?php
// user-update.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('user-list.php');
level_or_alert(1, 'Modification des utilisateurs');

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

// modification d'un utilisateur

$user_id  = param_post('id', 0); // -> modify
$flag_new = true;
if ($user_id > 0)
	$flag_new = false;

$nom     = param_post('nom');        // *
$level   = param_post('level');      // *
$theme   = param_post('theme');      // *
$mail    = param_post('addr_mail');  // *
$prenom  = param_post('prenom');
$phone   = param_post('phone');
$equipe  = param_post('equipe');

if (empty($user_id))
	$erreur = 'Identifiant utilisateur non pr&eacute;cis&eacute;';
if (empty($nom))
	$erreur = 'Nom de famille non pr&eacute;cis&eacute;';
if (empty($mail))
	$erreur = 'Adresse de courriel non pr&eacute;cis&eacute;';
if (empty($level))
	$erreur = 'Qualit&eacute; non pr&eacute;cis&eacute;';
if (empty($theme))
	$erreur = 'Th&egrave;me non pr&eacute;cis&eacute;';

if (!empty($erreur)) {
	// erreur
	$title         = 'Erreur utilisateur';
	$action        = 'user-edit.php?id='.$user_id;
	$highlight     = $user_id;
	$message_text  = $erreur;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

$user_selected = get_user_all_by_id($pdo, $user_id);

if ($level != $user_selected['level'] && $logged_level < 3)
	$level = $user_selected['level'];

//modif inscription
//on construit la demande
$modif = false;
if (   ($nom    != $user_selected['nom'])
	|| ($prenom != $user_selected['prenom'])
	|| ($mail   != $user_selected['mail'])
	|| ($level  != $user_selected['level'])
	|| ($phone  != $user_selected['tel'])
	|| ($equipe != $user_selected['equipe'])
	|| ($theme  != $user_selected['theme']))
	$modif = true;

if ($modif) {
	$err_msg = set_user_update($pdo, $user_id, $nom, $prenom, $mail, $level, $phone, $equipe, $theme) {
	if ($err_msg != '') {
		$title        = 'Erreur utilisateur';
		$action       = 'user-list.php?highlight='.$user_id;
		$highlight    = $user_id;
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la mise &agrave; jour de l\'utilisateur');
		include_once('include/message-box.php');
		exit();
	}

	redirect('user-list.php?highlight='.$user_id.'#item'.$user_id);
}


if ($user_id == $logged_id)
	$_SESSION['logged_theme'] = theme($theme);

if ($logged_level >= 3 && $valid == 1) {
	//validation d'un user acceptee
	//envoi d'un mail a cet user
	//$texte = $prenom.' '.$nom.' votre inscription au systeme GestEx &agrave; &eacute;t&eacute; accept&eacute;e !';
	// mail($mail, "[GestEx] inscription accept&eacute;e - ".$nom." ".$prenom, $texte);
}

redirect('user-list.php?highlight='.$user_id.'#item'.$user_id);
?>
