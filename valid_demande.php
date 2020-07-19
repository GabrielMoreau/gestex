<?php

require("html-functions.php");
//recuper la methode de tri

/// valid_demande.php
//validation des demandes
unset($erreur);
//variables ne pouvant etre nulles

if (empty($_POST[tache]))
	$erreur="tache non pr&eacute;cis&eacute;";
else{
	$tache =$_POST[tache];

if (empty($_POST[nomdemandeur]))
	$erreur="nomdemandeur non pr&eacute;cis&eacute;";
else{
	$nomdemandeur =$_POST[nomdemandeur];

	if (empty($_POST[details]))

			$erreur="details non pr&eacute;cis&eacute;";
else{
	$details =$_POST[details];

							//variables pouvant etre nulles

if (empty($_POST[achat]))
		$erreur="achat non pr&eacute;cis&eacute;";
	else{
		$achat=$_POST[achat];

if (empty($_POST[avancement]))
		$erreur="avancement non pr&eacute;cis&eacute;";
	else{
		$avancement=$_POST[avancement];

				$termine =$_POST[termine];

				$piecesjointes =$_POST[piecesjointes];

	}}}}}

en_tete('R&eacute;sultat ajout appareil');

$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

$cat=$_GET[categorie];
//echo "$cat";
//r&eacute;cupere la categorie de le page ajout appareil

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_demande.php\">Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit
require_once('db-functions.php');

if ( $connex = connect_db() ){
		//inscription
	$table = "demandes";

		$result = mysql_query("INSERT INTO $table ".
			"(tache,nomdemandeur,details,achat,avancement,termine,piecesjointes)".
			" VALUES ('$tache','$nomdemandeur','$details','$achat','$avancement','$termine','$piecesjointes')");
			//
if (!$result){
			//inscription !ok
			$erreur = mysql_error();
		echo "<br />erreur :".$erreur;
		}

	}//end if connect

////en_tete('inscription Valid&eacute;e');
if ( $connex = connect_db() ){
	// recupere la liste de appareils

$querry = "SELECT * FROM categorie WHERE id='$cat';" ;
	list($qh,$num) = query_db($querry);
	$last_id=0;
$datax = result_db($qh);}

echo "<br />ajout de ".$nom."<br />";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"list_demande.php\">Suite</a><br /><br />\n";
//quand on va sur suite, on retourne sur la page de la categorie choisie
pied_page();
exit();
}

?>
