<?php
//modif_user.php

// Authenticate
include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

require("html_functions.php");

/// modif_user.php
//modification d'un utilisateur

unset($erreur); unset($nom);unset($user_id );
//variables ne pouvant etre nulles
	if (empty($_POST['user2ch_id']))
		$erreur="identifiant utilisateur non pr&eacute;cis&eacute;";
	else{
		$user2ch_id =$_POST['user2ch_id'];

				if (empty($_POST['nom']))
					$erreur="nom non pr&eacute;cis&eacute;";
				else{
					$nom =$_POST['nom'];

						if (empty($_POST['addr_mail']))
							$erreur="adresse de courriel non pr&eacute;cis&eacute;";
						else{
							$mail=$_POST['addr_mail'];
							//variables pouvant etre nulles
							$prenom =$_POST['prenom'];
							$phone =$_POST['phone'];
							$equipe =$_POST['equipe'];
							$level =$_POST['level'];
							$theme = $_POST['theme'];
}}}

en_tete('R&eacute;sultat inscription');

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"list_user.php\">Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on modifie

if ( $pdo = connect_db() ){
		//relit les anciennes coordonnees
	$sql = 'SELECT * FROM users WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($user2ch_id));
	$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//modif inscription
	//on construit la demande
	$querry = "UPDATE LOW_PRIORITY users SET ";
		if ($nom!=$user[0]['nom'])
			//modif du nom
			$querry.="nom='$nom',";
		if ($prenom!=$user[0]['prenom'])
			//modif du prenom
			$querry.=" prenom='$prenom',";
	if ($user_level==3){
		if ($level!=$user[0]['level'])
			//modif du level
			$querry.=" level='$level',";
		if ($user[0]['valid'] ==0){
			//validation du user
			$querry.=" valid=1,";
			$valid=1;
			}
		}
		if ($phone!=$user[0]['tel'])
			//modif du telephone
			$querry.=" tel='$phone',";
		if ($mail!=$user[0]['mail'])
			//modif du mail
			$querry.=" email='$mail',";
		if ($equipe!=$user[0]['equipe'])
			//modif du club
			$querry.=" equipe='$equipe',";
		$querry.=" theme='$theme',";
		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$user2ch_id'";
		if ($user_level>= 3)
			$stmt = $pdo->prepare($querry);
			$stmt->execute();
			
		if ($user_level == 3 && $valid==1 ){
			//validation d'un user acceptee
			//envoi d'un mail a cet user
		$texte = $prenom." ".$nom." votre inscription au systeme GestEx a &eacute;t&eacute; accept&eacute;e !";
			mail($mail, "[GestEx] inscription accept&eacute;e - ".$nom." ".$prenom, $texte);
		}
	}//end if connect

	Header("Location: list_user.php");

pied_page();
exit();
}

?>
