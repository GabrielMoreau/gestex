<?php
/// modif_proj.php

// Authenticate
include("auth-functions.php");

if (!auth(2))
	Header("Location: login.php");

$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require_once('html-functions.php');

//modification d'une manip

unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST[id_manip]))
	$erreur="id manip non pr&eacute;cis&eacute;";
else {
	$id_manip=$_POST[id_manip];
	if (empty($_POST[id_proj]))
		$erreur="id projet non pr&eacute;cis&eacute;";
	else {
		$id_proj=$_POST[id_proj];

		if (empty($_POST[nom]))
			$erreur="nom non pr&eacute;cis&eacute;";
		else {
			$nom=$_POST[nom];
							$descr =$_POST[descr];
							$date =$_POST[date];

}}}

en_tete('R&eacute;sultat modification');

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_proj.php?idm=".$id_manip ."&idp=".$id_proj."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM projet WHERE id='$id_proj'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

		//modification projet
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY projet SET ";
		if ($nom!=$data['nom'])
			//modif du nom
			$querry.="nom='$nom',";
		if ($descr!=$data['descr'])
			//modif de la descr
			$querry.="descr='$descr',";
		if ($date!=$data['date'])
			//modif de la date
			$querry.="date='$date',";
		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$id_proj'";
		if ($user_level>= 3)
			echo "MySQL Querry : ". $querry."<br />";
		$result = mysql_query($querry);
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
			echo "<br />erreur :".$erreur;
		}

	}//end if connect

////en_tete('modification projet Valid&eacute;e');

echo "<br />".$nom." modification ";
echo" <img src=\"images/pool_project.jpg\" height=\"100\"  nosave=\"\" align=\"middle\" alt=\"\">";
echo"  valid&eacute;e !!";
echo"<br /><br /><a href=\"manip_maint.php?id=".$id_manip."\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
