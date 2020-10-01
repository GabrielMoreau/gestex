<?php
/// modif_manip.php

// Authenticate
require_once('module/auth-functions.php');

if (!auth(2))
	Header("Location: login.php");

$logged_user = strtolower($_SESSION['logged_user']);
$logged_level= $_SESSION['logged_level'];

require_once('module/html-functions.php');

//modification d'une tache de manip

unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST[id_manip]))
	$erreur="manip non pr&eacute;cis&eacute;";
else{
	$manip_id =$_POST[id_manip];

if (empty($_POST[id_proj]))
	$erreur="projet non pr&eacute;cis&eacute;";
else{
	$proj_id =$_POST[id_proj];

if (empty($_POST[id_task]))
	$erreur="id non pr&eacute;cis&eacute;";
else {
	$id_task=$_POST[id_task];

if (empty($_POST['nom']))
	$erreur="nom non pr&eacute;cis&eacute;";
else {
	$nom=$_POST['nom'];

				$descr =$_POST['descr'];

							$date =$_POST['date'];

							$fourn = array_values($_POST['fourn'] );

					}}}}///}
en_tete('R&eacute;sultat modification');

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_task.php?idm=".$manip_id."&idp=".$proj_id."&idt=".$id_task ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM tache WHERE id='$id_task'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

		//modification tache
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY tache SET ";

		if ($nom!=$data['nom'])
			//modif du nom
			$querry.="nom='$nom',";
		if ($descr!=$data['descr'])
			//modif de la descr
			$querry.="descr='$descr',";

		//liste fournisseurs tableau->chaine
		$liste_fourn = implode( "," , $fourn);
		if ($liste_fourn!=$data['fourniss'])
			//modif de fournisseur
			$querry.="fourniss='$liste_fourn',";

		if ($date!=$data['date'])
			//modif de la date
			$querry.="date='$date',";
		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$id_task'";
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

echo "<br /><b>".$nom."</b>: modification ";
echo" <img src=\"images/pool_project.jpg\" width=\"50\" nosave=\"\" align=\"middle\" alt=\"\">";
echo"  valid&eacute;e !!";
echo"<br /><br /><a href=\"manip_maint.php?id=".$manip_id."\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
