<?php
// user-update.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('user-list.php');
level_or_alert(1, 'Mise &agrave; jour d\'un utilisateur');

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

//modification d'un utilisateur
unset($erreur);

//variables ne pouvant etre nulles
$user2ch_id = param_post('user2ch_id'); // *
$nom        = param_post('nom');        // *
$mail       = param_post('addr_mail');  // *
//variables pouvant etre nulles
$prenom = param_post('prenom');
$phone  = param_post('phone');
$equipe = param_post('equipe');
$level  = param_post('level');
$theme  = param_post('theme');

if (empty($user2ch_id))
	$erreur = 'Identifiant utilisateur non pr&eacute;cis&eacute;';

if (empty($nom))
	$erreur = 'Nom de famille non pr&eacute;cis&eacute;';

if (empty($mail))
	$erreur = 'Adresse de courriel non pr&eacute;cis&eacute;';

if (!empty($erreur)) {
	//erreur
	$title        = 'Erreur';
	$action       = 'user-list.php';
	$message_text = $erreur;
	include_once('include/warning-box.php');
	exit();
}

$pdo = connect_db_or_alert();

$user_selected = get_user_all_by_id($pdo, $user2ch_id);

//modif inscription
//on construit la demande
$querry = "UPDATE LOW_PRIORITY users SET ";
if ($nom != $user_selected['nom'])
	$querry .= "nom='$nom',";
if ($prenom != $user_selected['prenom'])
	$querry .= " prenom='$prenom',";
if ($logged_level >= 3) {
	if ($level != $user_selected['level'])
		$querry .= " level='$level',";
	if ($user_selected['valid'] == 0) {
		//validation du user
		$querry .= " valid=1,";
		$valid = 1;
	}
}
if ($phone != $user_selected['tel'])
	$querry.=" tel='$phone',";
if ($mail != $user_selected['mail'])
	$querry.=" email='$mail',";
if ($equipe != $user_selected['equipe'])
	$querry .=" equipe='$equipe',";
$querry .= " theme='$theme',";
// supprime la derniere virgule
$querry[strlen($querry)-1] = ' ';
//ajoute la clause
$querry.= " WHERE id='$user2ch_id'";
if ($logged_level >= 3)
	echo $querry;
$stmt = $pdo->prepare($querry);
$stmt->execute();

if ($user2ch_id == $logged_id)
	$_SESSION['logged_theme'] = theme($theme);

if ($logged_level >= 3 && $valid == 1) {
	//validation d'un user acceptee
	//envoi d'un mail a cet user
	$texte = $prenom.' '.$nom.' votre inscription au systeme GestEx &agrave; &eacute;t&eacute; accept&eacute;e !';
	// mail($mail, "[GestEx] inscription accept&eacute;e - ".$nom." ".$prenom, $texte);
}

redirect('user-list.php?highlight='.$user2ch_id.'#item'.$user2ch_id);
?>
