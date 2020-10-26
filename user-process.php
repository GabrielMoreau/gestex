<?php
// user-process.php
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

// validation et modification d'un nouvel utilisateur

$user_id  = param_post('id', 0); // -> modify
$flag_new = true;
if ($user_id > 0)
	$flag_new = false;

$loggin    = param_post('loggin');    // * new only
$password  = param_post('password');  // * new only
$password2 = param_post('password2'); // * new only
$nom       = param_post('nom');       // *
$level     = param_post('level');     // *
$theme     = param_post('theme');     // *
$mail      = param_post('addr_mail'); // *
$prenom    = param_post('prenom');
$phone     = param_post('phone');
$equipe    = param_post('equipe');

if (empty($nom))
	$erreur = 'Nom de famille non pr&eacute;cis&eacute;';
if (empty($mail))
	$erreur = 'Adresse de courriel non pr&eacute;cis&eacute;';
if (empty($level))
	$erreur = 'Qualit&eacute; non pr&eacute;cis&eacute;';
if (empty($theme))
	$erreur = 'Th&egrave;me non pr&eacute;cis&eacute;';
if ($flag_new) {
	if (empty($loggin))
		$erreur = 'Identifiant (login) non pr&eacute;cis&eacute;';
	if (empty($password))
		$erreur = 'Password non pr&eacute;cis&eacute;';
	if (empty($password2))
		$erreur = 'Confirmation de password non pr&eacute;cis&eacute;';
	if ($password != $password2)
		$erreur = 'Les passwords diff&egrave;rent';
}

$pdo = connect_db_or_alert();

if ($flag_new and check_val_in_db($pdo, 'users', 'loggin', $loggin)) {
	// nom existant deja dans db
	$erreur = 'L\'identifiant <i>'.$loggin.'</i> est d&eacute;j&agrave; utilis&eacute; dans la base de donn&eacute;es';
}

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

if ($flag_new) { // new
	level_or_alert(3, 'Validation d\'un nouvel utilisateur');

	$mot_crypte = md5($password);
	list($user_id, $err_msg) = set_user_new($pdo, $nom, $prenom, $loggin, $mot_crypte, $mail, $level, $phone, $equipe, $theme);

	if ($err_msg != '') {
		$title        = 'Erreur utilisateur';
		$action       = 'user-list.php';
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la cr&eacute;ation de l\'utilisateur');
		include_once('include/message-box.php');
		exit();
	}

	// inscription enregistree mais pas encore validee !
	// envoi d'un courriel a l'admin
	// $texte = 'Inscription de '.$prenom.' '.$nom;
	// mail(GESTEX_ADMIN_MAIL, '[GestEx] ajout utilisateur - '.$nom.'  '.$prenom, $texte);

	$title        = 'Ajout utilisateur';
	$action       = 'user-list.php?highlight='.$user_id;
	$highlight    = $user_id;
	$message_text = 'Ajout de l\'utilisateur '.$nom.' '.$prenom' valid&eacute;e';
	include_once('include/message-box.php');
	exit();
}

// modify
// recupere les anciennes caracteristiques
$user_selected = get_user_all_by_id($pdo, $user_id);

if ($level != $user_selected['level'] && $logged_level < 3)
	$level = $user_selected['level'];

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
