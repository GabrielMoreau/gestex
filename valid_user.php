<?php

require("html_functions.php");

/// valid_user.php
// validation d'un nouveau utilisateur

unset($erreur);
unset($loggin);
unset($password);
unset($password2);
unset($nom);

//variables ne pouvant etre nulles
if (empty($_POST['loggin']))
	$erreur = 'Identifiant (login) non pr&eacute;cis&eacute;';
else {
	$loggin = $_POST['loggin'];
	if (empty($_POST['password']))
		$erreur = 'Password non pr&eacute;cis&eacute;';
	else {
		$password = $_POST['password'];
		if (empty($_POST['password2']))
			$erreur = 'Confirmation de password non pr&eacute;cis&eacute;';
		else {
			$password2 = $_POST['password2'];
			if ($password != $password2)
				$erreur = 'Les passwords diff&egrave;rent';
			else {
				if (empty($_POST['nom']))
				$erreur = 'Nom de famille non pr&eacute;cis&eacute;';
				else {
					$nom = $_POST['nom'];
					if (!isset($_POST['level']))
						$erreur = 'Qualit&eacute; non pr&eacute;cis&eacute;';
					else
						$level = $_POST['level'];

					$mail = $_POST['addr_mail'];
					//variables pouvant etre nulles
					$prenom = $_POST['prenom'];
					$phone = $_POST['phone'];
					$equipe = $_POST['equipe'];
				}
			}
		}
	}
}

en_tete('R&eacute;sultat inscription');

require("db_functions.php");

if ($pdo = connect_db()) {

	if (!empty(check_val_in_db($pdo, 'users', 'loggin', $loggin))) {
		//nom existant deja dans db
		$erreur = 'L\'identifiant <i>'.$loggin.'</i> est d&eacute;j&agrave; utilis&eacute; dans la base de donn&eacute;es';
	}

	if (!empty($erreur)) {
		//erreur
		echo '<br /><b>Erreur de saisie : </b>'.$erreur;
		echo '<br /><center><a href="add_user.php?'.$loggin.'">Suite</a></center><br />';
	}
	else {
		/// tout est ok
		$mot_crypte = md5($password);
		$sql = 'INSERT INTO users (nom, prenom, loggin, password, email, level, tel, equipe, valid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0);';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($nom, $prenom, $loggin, $mot_crypte, $mail, $level, $phone, $equipe));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	  
		//if (!$result) {
			// inscription !ok
			// $erreur = mysql_error();
		//	echo '<br /><b>Erreur dans l\'acc&egrave;s &agrave; la base de donn&eacute;es</b>';
		//}
		//else {
			// inscription enregistree mais pas encore validee!
			// envoi d'un courriel a l'admin
			$texte = 'Inscription de '.$prenom.' '.$nom;
			mail(GESTEX_ADMIN_MAIL, "[GestEx] ajout utilisateur - ".$nom." ".$prenom, $texte);

			echo 'Inscription de '.$prenom.' '.$nom.'<br />';
			echo ' <img src="images/pool_project.jpg" height="100" nosave="" align="middle" alt="" />"';
			echo ' est propos&eacute;e avec l\'identifiant (login) : '.$loggin;
			echo '<br />Vous serez pr&eacute;venu de sa validation par courriel...';
		//} // end else
		echo '<br /><center><a href="list_user.php">Suite</a></center><br /><br />';
	} // else end
} //end if connect

pied_page();
?>
