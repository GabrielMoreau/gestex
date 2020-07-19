<?php
/// modif_intapp.php

// Authenticate
include("auth-functions.php");

if (!auth(3))
	Header("Location: login.php");

$logged_in_user = strtolower($_SESSION['logged_in_user']);

require("html-functions.php");

//modification d'une intervention sur  appareil
unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST[id_app]))
	$erreur="id appareil non pr&eacute;cis&eacute;";
else{
	$id_app =$_POST[id_app];
if (empty($_POST[id_int]))
	$erreur="id intervention non pr&eacute;cis&eacute;";
else{
	$id_int =$_POST[id_int];

	if (empty($_POST[descr]))
		$erreur="Description non pr&eacute;cis&eacute;";
	else{
		$descr=$_POST[descr];

			if (empty($_POST[tech]))
				$erreur="tech non pr&eacute;cis&eacute;";
			else{
				$tech =$_POST[tech];
				if (empty($_POST[fourn]))
					$erreur="fournisseur non pr&eacute;cis&eacute;";
				else{
					$fourn =$_POST[fourn];

							//variables pouvant etre nulles
					$date = $_POST[date];
					$facture=$_POST[facture];

}}}}}

en_tete('R&eacute;sultat modification intervention '.$id_int.' sur l\'appareil '.$id_app);

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_intapp.php?id=".$id_int."&app=".$id_app ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM intervention WHERE id='$id_int'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

/*
echo $nom." ".$data['nom']."<br />";
echo $descr." ".$data['descr']."<br />";
echo $compte." ".$data['compte']."<br />";
echo $chef." ".$data['chef']."<br />";*/

		//modification intervention app
$modif=0;
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY intervention SET ";

		if ($descr!=$data['descr']){
			//modif de la descr
			$modif=1;
			$querry.="descr='$descr',";
		}
		if ($tech!=$data['tech']){
			//modif du tech
			$modif=1;
			$querry.="teche='$tech',";
		}

		if ($fourn!=$data['fourn']){
			//modif du fourn
			$modif=1;
			$querry.="fournisseur='$fourn',";
		}
		if ($date!=$data['date']){
			//modif de la date
			$modif=1;
			$querry.="date='$date',";
		}
		if ($facture!=$data['facture']){
			//modif de facture
			$modif=1;
			$querry.="facture='$facture',";
		}

		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$id_int'";
	if ($modif!=0){
			//echo $querry;
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
		echo"<br /><br /><a href=\"list_intapp.php\">Suite</a><br /><br />\n";
		pied_page();
		exit();
		}//else end
	}//end if connect

echo "<br />Intervention ".$id_int." modifi&eacute; ";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo"  valid&eacute;e !!";
echo"<br /><br /><a href=\"list_intapp.php?id=".$id_app."\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
