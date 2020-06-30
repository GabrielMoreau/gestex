<?php
//modif_inscr.php

// Authenticate
include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

require("html_functions.php");

/// modif_inscr.php
//modification d'une inscription

unset($erreur); unset($nom);unset($user_id );
//variables ne pouvant etre nulles
	if (empty($_POST[user2ch_id]))
		$erreur="identifiant utilisateur non pr&eacute;cis&eacute;";
	else{
		$user2ch_id =$_POST[user2ch_id];

				if (empty($_POST[nom]))
					$erreur="nom non pr&eacute;cis&eacute;";
				else{
					$nom =$_POST[nom];

						if (empty($_POST[addr_mail]))
							$erreur="adresse de courriel non pr&eacute;cis&eacute;";
						else{
							$mail=$_POST[addr_mail];
							//variables pouvant etre nulles
							$prenom =$_POST[prenom];
							$phone =$_POST[phone];
							$equipe =$_POST[equipe];
							$level =$_POST[level];
}}}

en_tete("resultat inscription ");

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

if ( $connex = connect_db() ){
		//relit les anciennes coordonnées
	$querry="SELECT * FROM users WHERE id='$user2ch_id'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

	//modif inscription
	//on construit la demande
	$querry = "UPDATE LOW_PRIORITY users SET ";
		if ($nom!=$data['nom'])
			//modif du nom
			$querry.="nom='$nom',";
		if ($prenom!=$data['prenom'])
			//modif du prenom
			$querry.=" prenom='$prenom',";
	if ($user_level==3){
		if ($level!=$data['level'])
			//modif du level
			$querry.=" level='$level',";
		if ($data['valid'] ==0){
			//validation du user
			$querry.=" valid=1,";
			$valid=1;
			}
		}
		if ($phone!=$data['tel'])
			//modif du telephone
			$querry.=" tel='$phone',";
		if ($mail!=$data['mail'])
			//modif du mail
			$querry.=" email='$mail',";
		if ($equipe!=$data['equipe'])
			//modif du club
			$querry.=" equipe='$equipe',";
		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$user2ch_id'";

		$result = mysql_query($querry);
		if ($user_level>= 3)
			echo "MySQL Querry : ". $querry."<br />";
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
		echo "<br />erreur :".$erreur;
		}
		if ($user_level == 3 && $valid==1 ){
			//validation d'un user acceptée
			//envoi d'un mail a cet user
		$texte = $prenom." ".$nom." votre inscription au systeme GestEx a été acceptée !";
			mail($mail, "[GestEx] inscription acceptée - ".$nom." ".$prenom, $texte);
		}
	}//end if connect

echo "inscription de ".$prenom." ".$nom." ".$mail."<br />";
echo" <img src=\"images/pool_project.jpg\"  height=\"100\" nosave=\"\" align=\"middle\" alt=\"\" />";
echo" est modifi&eacute;e  !";
echo"<br /><br /><a href=\"list_users.php\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
