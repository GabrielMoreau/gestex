<?php
/// modif_fourn.php

// Authenticate
include("session_auth.php");

if (!auth(2))
	Header("Location: login.php");

$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");



//modification d'un fournisseur

unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST[id_fourn]))
	$erreur="id non pr&eacute;cis&eacute;";
else {
	$id_fourn=$_POST[id_fourn];

if (empty($_POST[nom]))
	$erreur="nom non pr&eacute;cis&eacute;";
else {
	$nom=$_POST[nom];
	if (empty($_POST[adresse]))
		$erreur="adresse non pr&eacute;cis&eacute;";
	else {
		$adresse=$_POST[adresse];
				
							$tel=$_POST[phone];
							$fax=$_POST[fax];
							$mail =$_POST[addr_mail];
							$www=$_POST[www];
							$contact =$_POST[contact];
							$descr =$_POST[descr];
							
}}}

en_tete("resultat modification ");

if (!empty($erreur) ){

	//erreur

	echo "<br>erreur :".$erreur;
	echo"<br><a href=\"add_fourn.php?id=".$id_fourn ."\" >Suite</a><br>\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM fournisseurs WHERE id='$id_fourn'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

		//modification fournisseur
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY fournisseurs SET ";
		if ($nom!=$data['nom'])
			//modif du nom
			$querry.="nom='$nom',";
		if ($adresse!=$data['adresse'])
			//modif de l' adresse
			$querry.="adresse='$adresse',";
		if ($tel!=$data['tel'])
			//modif du tel
			$querry.="tel='$tel',";
		if ($fax!=$data['fax'])
			//modif du fax
			$querry.="fax='$fax',";
		if ($mail!=$data['mail'])
			//modif du mail
			$querry.="mail='$mail',";
		if ($www!=$data['www'])
			//modif de l'url
			$querry.="www='$www',";
		if ($contact!=$data['contact'])
			//modif des contacts
			$querry.="contact='$contact',";
		if ($descr!=$data['descr'])
			//modif de la descr
			$querry.="descr='$descr',";
		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$id_fourn'";

if ($user_level>= 3)
		echo "MySQL Querry : ".$querry."<br>";

		$result = mysql_query($querry);
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
			echo "<br>erreur :".$erreur;
		}
		
	}//end if connect

////en_tete("modification fournisseur Valid&eacute;e");

echo "<br>".$nom." modifi&eacute; ";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo"  valid&eacute;e !!";
echo"<br><br><a href=\"list_fourn.php\">Suite</a><br><br>\n";
pied_page();
exit();
}

?>
