<?php
/// modif_manip.php

// Authenticate
require_once('module/auth-functions.php');

if (!auth(2))
	Header("Location: login.php");

$logged_user = strtolower($_SESSION['logged_user']);
$logged_level= $_SESSION['logged_level'];

require_once('module/html-functions.php');

//modification d'une manip

unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST[id_manip]))
	$erreur="id non pr&eacute;cis&eacute;";
else {
	$id_manip=$_POST[id_manip];

if (empty($_POST[nom]))
	$erreur="nom non pr&eacute;cis&eacute;";
else {
	$nom=$_POST[nom];
	if (empty($_POST[local]))
		$erreur="local non pr&eacute;cis&eacute;";
	else {
		$local=$_POST[local];
							$chercheur=$_POST[cherch];
							$chercheur_bis=$_POST[cherch_bis];
							$equipe=$_POST[equipe];
							$descr =$_POST[descr];
							$date =$_POST[date];

}}}

en_tete('R&eacute;sultat modification');

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_manip.php?id=".$id_manip ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM manip WHERE id='$id_manip'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

		//modification fournisseur
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY manip SET ";
		if ($nom!=$data['nom'])
			//modif du nom
			$querry.="nom='$nom',";
		if ($descr!=$data['descr'])
			//modif de la descr
			$querry.="descr='$descr',";
		if ($local!=$data['local'])
			//modif du local
			$querry.="local='$local',";
		if ($equipe!=$data['equipe'])
			//modif de l'equipe
			$querry.="equipe='$equipe',";
		if ($chercheur!=$data['chercheur'])
			//modif du chercheur
			$querry.="chercheur='$chercheur',";
		if ($chercheur_bis!=$data['chercheur_bis'])
			//modif du 2eme chercheur
			$querry.="chercheur_bis='$chercheur_bis',";
		if ($date!=$data['date'])
			//modif de la date
			$querry.="date='$date',";
		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$id_manip'";
		if ($logged_level>= 3)
			echo "MySQL Querry : ".$querry."<br />";
		$result = mysql_query($querry);
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
			echo "<br />erreur :".$erreur;
		}

	}//end if connect

////en_tete('modification manip Valid&eacute;e');

echo "<br />".$nom." modification ";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo"  valid&eacute;e !!";
echo"<br /><br /><a href=\"list_manip.php\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
