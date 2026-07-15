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

$username   = param_post('username');    // * new only
$password   = param_post_password('password');  // * new only
$password2  = param_post_password('password2'); // * new only
$familyname = param_post('familyname');       // *
$level      = param_post('level');     // *
$theme      = param_post('theme', 'clair');     // *
$mail       = param_post('email');     // *
$firstname  = param_post('firstname');
$phone      = param_post('phone', 'Na');
$team_id    = param_post('team_id');

if (empty($familyname))
	$err_msg = 'Nom de famille non précisé';
if (empty($mail))
	$err_msg = 'Adresse de courriel non précisée';
if (empty($level))
	$err_msg = 'Qualité non précisée';
if (empty($theme))
	$err_msg = 'Thème non précisé';
if ($flag_new) {
	if (empty($username))
		$err_msg = 'Identifiant (login) non précisé';
	if (empty($password))
		$err_msg = 'Password non précisé';
	if (empty($password2))
		$err_msg = 'Confirmation de password non précisé';
	if ($password != $password2)
		$err_msg = 'Les passwords diffèrent';
}

$pdo = connect_db_or_alert();

if ($flag_new and check_val_in_db($pdo, 'users', 'username', $username)) {
	// nom existant deja dans db
	$err_msg = 'L’identifiant <i>'.$username.'</i> est déjà utilisé dans la base de données';
}

if (!empty($err_msg)) {
	// erreur
	$title         = 'Erreur utilisateur';
	$action        = 'user-edit.php?id='.$user_id;
	$highlight     = $user_id;
	$message_text  = $err_msg;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

if ($flag_new) { // new
	level_or_alert(3, 'Validation d’un nouvel utilisateur');

	$new_pwhash = password_hash($password, PASSWORD_DEFAULT);
	list($user_id, $err_msg) = set_user_new($pdo, $familyname, $firstname, $username, $new_pwhash, $mail, $level, $phone, $team_id, $theme);

	if ($err_msg != '') {
		$title        = 'Erreur utilisateur';
		$action       = 'user-list.php';
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la création de l’utilisateur');
		include_once('include/message-box.php');
		exit();
	}

	// inscription enregistree mais pas encore validee !
	// envoi d'un courriel a l'admin
	// $texte = 'Inscription de '.$firstname.' '.$familyname;
	// mail(GESTEX_ADMIN_MAIL, '[GestEx] ajout utilisateur - '.$familyname.'  '.$firstname, $texte);

	$title        = 'Ajout utilisateur';
	$action       = 'user-list.php?highlight='.$user_id;
	$highlight    = $user_id;
	$message_text = 'Ajout de l’utilisateur '.$familyname.' '.$firstname.' validée';
	include_once('include/message-box.php');
	exit();
}

// modify
// Récupère les anciennes caractéristiques
$user_selected = get_user_all_by_id($pdo, $user_id);

if ($level != $user_selected['level'] && $logged_level < 3)
	$level = $user_selected['level'];

$modif = false;
if (   ($familyname != $user_selected['familyname'])
	|| ($firstname  != $user_selected['firstname'])
	|| ($mail       != $user_selected['email'])
	|| ($level      != $user_selected['level'])
	|| ($phone      != $user_selected['phone'])
	|| ($team_id    != $user_selected['team_id'])
	|| ($theme      != $user_selected['theme']))
	$modif = true;

if ($modif) {
	$err_msg = set_user_update($pdo, $user_id, $familyname, $firstname, $mail, $level, $phone, $team_id, $theme, $logged_level, $username);
	if ($err_msg != '') {
		$title        = 'Erreur utilisateur';
		$action       = 'user-list.php?highlight='.$user_id;
		$highlight    = $user_id;
		$message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans la mise à jour de l’utilisateur');
		include_once('include/message-box.php');
		exit();
	}

	redirect('user-list.php?highlight='.$user_id.'#item'.$user_id);
}


if ($user_id == $logged_id)
	$_SESSION['logged_theme'] = theme($theme);

if ($logged_level >= 3 && $valid == 1) {
	// Validation d'un utilisateur acceptée
	// envoi d'un mail a cet user
	// $texte = $firstname.' '.$familyname.' votre inscription au systeme GestEx à été acceptée !';
	// mail($mail, "[GestEx] inscription acceptée - ".$familyname." ".$firstname, $texte);
}

redirect('user-list.php?highlight='.$user_id.'#item'.$user_id);
?>
