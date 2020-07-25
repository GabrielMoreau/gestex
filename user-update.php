<?php
// user-update.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

auth_or_login('user-list.php');
level_or_alert(1, 'Mise &agrave; jour d\'un utilisateur');

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

//modification d'un utilisateur
unset($erreur);

//variables ne pouvant etre nulles
$user2ch_id = param_post('user2ch_id');
if (empty($user2ch_id))
	$erreur = 'Identifiant utilisateur non pr&eacute;cis&eacute;';

$nom = param_post('nom');
if (empty($nom))
	$erreur = 'Nom de famille non pr&eacute;cis&eacute;';

$mail = param_post('addr_mail');
if (empty($mail))
	$erreur = 'Adresse de courriel non pr&eacute;cis&eacute;';

//variables pouvant etre nulles
$prenom = param_post('prenom');
$phone  = param_post('phone');
$equipe = param_post('equipe');
$level  = param_post('level');
$theme  = param_post('theme');

if (!empty($erreur)) {
	//erreur
	$title        = 'Erreur';
	$action       = 'user-list.php';
	$message_text = $erreur;
	include_once('include/warning-box.php');
	exit();
}

if ($pdo = connect_db()) {
	//relit les anciennes coordonnees
	$sql = 'SELECT * FROM users WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($user2ch_id));
	$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//modif inscription
	//on construit la demande
	$querry = "UPDATE LOW_PRIORITY users SET ";
	if ($nom != $user[0]['nom'])
		$querry .= "nom='$nom',";
	if ($prenom != $user[0]['prenom'])
		$querry .= " prenom='$prenom',";
	if ($logged_level >= 3) {
		if ($level != $user[0]['level'])
			$querry .= " level='$level',";
		if ($user[0]['valid'] == 0) {
			//validation du user
			$querry .= " valid=1,";
			$valid = 1;
		}
	}
	if ($phone != $user[0]['tel'])
		$querry.=" tel='$phone',";
	if ($mail != $user[0]['mail'])
		$querry.=" email='$mail',";
	if ($equipe != $user[0]['equipe'])
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
} //end if connect

redirect('user-list.php?highlight='.$user2ch_id.'#item'.$user2ch_id);
?>
