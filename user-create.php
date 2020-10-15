<?php
// user-create.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('user-create.php');
level_or_alert(3, 'Validation d\'un utilisateur');

//validation d'un nouvel utilisateur

unset($erreur);
unset($loggin);
unset($password);
unset($password2);
unset($nom);

//variables ne pouvant etre nulles
if (empty($_POST['loggin']))
	$erreur = 'Identifiant (login) non pr&eacute;cis&eacute;';

$loggin = $_POST['loggin'];
if (empty($_POST['password']))
	$erreur = 'Password non pr&eacute;cis&eacute;';

$password = $_POST['password'];
if (empty($_POST['password2']))
	$erreur = 'Confirmation de password non pr&eacute;cis&eacute;';

$password2 = $_POST['password2'];
if ($password != $password2)
	$erreur = 'Les passwords diff&egrave;rent';

$nom = $_POST['nom'];
if (empty($_POST['nom']))
	$erreur = 'Nom de famille non pr&eacute;cis&eacute;';

$level = $_POST['level'];
if (empty($_POST['level']))
	$erreur = 'Qualit&eacute; non pr&eacute;cis&eacute;';

if (empty($_POST['theme']))
	$erreur = 'Th&egrave;me non pr&eacute;cis&eacute;';
else
	$theme = $_POST['theme'];

$mail = $_POST['addr_mail'];

//variables pouvant etre nulles
$prenom = $_POST['prenom'];
$phone  = $_POST['phone'];
$equipe = $_POST['equipe'];

en_tete('R&eacute;sultat inscription');

if ($pdo = connect_db()) {
	if (check_val_in_db($pdo, 'users', 'loggin', $loggin)) {
		// nom existant deja dans db
		$erreur = 'L\'identifiant <i>'.$loggin.'</i> est d&eacute;j&agrave; utilis&eacute; dans la base de donn&eacute;es';
	}

	if (!empty($erreur)) {
		//erreur
		echo '<br /><b>Erreur de saisie : </b>'.$erreur;
		echo '<br /><center><a href="user-edit.php?'.$loggin.'">Suite</a></center><br />';
	}
	else {
		/// tout est ok
		$mot_crypte = md5($password);
		$sql = 'INSERT INTO users (nom, prenom, loggin, password, email, level, tel, equipe, valid, theme) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?);';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($nom, $prenom, $loggin, $mot_crypte, $mail, $level, $phone, $equipe, $theme));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// inscription enregistree mais pas encore validee !
		// envoi d'un courriel a l'admin
		$texte = 'Inscription de '.$prenom.' '.$nom;
		mail(GESTEX_ADMIN_MAIL, "[GestEx] ajout utilisateur - ".$nom." ".$prenom, $texte);

		echo 'Ajout de '.$prenom.' '.$nom.' valid&eacute;<br />';
		echo '<br /><center><a href="user-list.php">Suite</a></center><br /><br />';
	} // else end
} // end if connect

pied_page();
?>
