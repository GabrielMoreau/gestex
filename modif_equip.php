<?php
/// modif_equip.php

// Authenticate
include("session_auth.php");

if (!auth(3))
	Header("Location: login.php");

$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

//modification d'une equipe

unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST[id_equip]))
	$erreur="id non pr&eacute;cis&eacute;";
else {
	$id_equip=$_POST[id_equip];

	if (empty($_POST[nom]))
		$erreur="nom non pr&eacute;cis&eacute;";
	else {
		$nom=$_POST[nom];
		if (empty($_POST[compte]))
			$erreur="compte non pr&eacute;cis&eacute;";
		else {
			$compte=$_POST[compte];
			$chef=$_POST[chef];
			$descr =$_POST[descr];

}}}

en_tete("resultat modification ");

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_equip.php?id=".$id_equip ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM equipe WHERE id='$id_equip'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

echo $nom." ".$data['nom']."<br />";
echo $descr." ".$data['descr']."<br />";
echo $compte." ".$data['compte']."<br />";
echo $chef." ".$data['chef']."<br />";

		//modification equip
$modif=0;
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY equipe SET ";
		if ($nom!=$data['nom']){
			//modif du nom
			$modif=1;
			$querry.="nom='$nom',";
		}
		if ($descr!=$data['descr']){
			//modif de la descr
			$modif=1;
			$querry.="descr='$descr',";
		}
		if ($compte!=$data['compte']){
			//modif du compte
			$modif=1;
			$querry.="compte='$compte',";
		}
		if ($chef!=$data['chef']){
			//modif du chef
			$modif=1;
			$querry.="chef='$chef',";
		}
		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$id_equip'";
	if ($modif!=0){
		if ($user_level>= 3)
			echo "MySQL Querry : ". $querry."<br />";
		$result = mysql_query($querry);
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
			echo "<br />erreur :".$erreur;
		}
	}//end if modif
	else{
		echo "aucune modif a faire";
		echo"<br /><br /><a href=\"list_equip.php\">Suite</a><br /><br />\n";
		pied_page();
		exit();
		}//else end
	}//end if connect

////en_tete("modification &eacute;quipe Valid&eacute;e");

echo "<br />".$nom."modifi&eacute; ";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo"  valid&eacute;e !!";
echo"<br /><br /><a href=\"list_equip.php\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
