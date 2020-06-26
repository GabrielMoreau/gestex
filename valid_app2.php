<?php

require("html_functions.php");
//recuper la methode de tri

/// valid_app2.php
//validation d'un nouvel appareil
unset($erreur);
//variables ne pouvant etre nulles

if (empty($_POST[categorie]))
	$erreur="categorie non pr&eacute;cis&eacute;";	
else{
	$categorie =$_POST[categorie];


if (empty($_POST[nom]))
	$erreur="nom non pr&eacute;cis&eacute;";	
else{
	$nom =$_POST[nom];

	if (empty($_POST[modele]))
		$erreur="Modele non pr&eacute;cis&eacute;";	
	else{
		$modele=$_POST[modele];


if (empty($_POST[gamme]))
		$erreur="gamme non pr&eacute;cis&eacute;";	
	else{
		
		$gamme=$_POST[gamme];


		if (empty($_POST[equipe]))
			$erreur="equipe non pr&eacute;cis&eacute;";	
		else{
			$equipe =$_POST[equipe];

			if (empty($_POST[fourn]))
				$erreur="fourn non pr&eacute;cis&eacute;";	
			else{
				$fourn =$_POST[fourn];
			

							//variables pouvant etre nulles

if (empty($_POST[achat]))
		$erreur="achat non pr&eacute;cis&eacute;";	
	else{
		$achat=$_POST[achat];



if (empty($_POST[tech]))
				$erreur="tech non pr&eacute;cis&eacute;";	
			else{
				$tech =$_POST[tech];

				$reparation =$_POST[reparation];

				$accessoires =$_POST[accessoires];

$inventaire =$_POST[inventaire];

$notice =$_POST[notice];


	}}}}}}}}		
							


en_tete("resultat ajout appareil ");

$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

$cat=$_GET[categorie];
//echo "$cat";
//récupère la catégorie de le page ajout appareil 

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_app2.php\">Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit
require("db_functions.php");

if ( $connex = connect_db() ){
		//inscription
	$table = "Listing";

		$result = mysql_query("INSERT INTO $table ".
			"(categorie,nom,modele , gamme, equipe, fournisseur, achat, responsable, reparation,accessoires,inventaire,notice)".
			" VALUES ('$categorie','$nom', '$modele','$gamme', '$equipe', '$fourn','$achat','$tech', '$reparation','$accessoires','$inventaire','$notice')");
			//	
if (!$result){
			//inscription !ok
			$erreur = mysql_error();
		echo "<br />erreur :".$erreur;
		}
		
	}//end if connect

////en_tete("inscription Valid&eacute;e");
if ( $connex = connect_db() ){
	// recupere la liste de appareils


$querry = "SELECT * FROM categorie where id='$cat'" ;
	list($qh,$num) = query_db($querry);
	$last_id=0;
$datax = result_db($qh);}


echo "<br />ajout de ".$nom."<br />";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"instru.php?categorie=".$datax[id]."\">Suite</a><br /><br />\n";
//quand on va sur suite, on retourne sur la page de la categorie choisie
pied_page();
exit();
}

?>
