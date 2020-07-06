<?php

require("html_functions.php");
//recuper la methode de tri

/// valid_labview.php
//validation des manip labview
unset($erreur);

if (empty($_POST[manipch]))
	$erreur="manipch non pr&eacute;cis&eacute;";
else{
	$manipch =$_POST[manipch];

if (empty($_POST[technicien]))
	$erreur="technicien non pr&eacute;cis&eacute;";
else{
	$technicien =$_POST[technicien];

	$localisation =$_POST[localisation];

if (empty($_POST[matos]))
		$erreur="mat&eacute;riel non pr&eacute;cis&eacute;";
	else{
		$matos=$_POST[matos];

if (empty($_POST[code]))
		$erreur="descriptif du code non pr&eacute;cis&eacute;";
	else{
		$code=$_POST[code];

				$driver =$_POST[driver];

		$module=$_POST[module];

				$ecran =$_POST[ecran];

				$pdf =$_POST[pdf];

	}}}}

en_tete('R&eacute;esultat ajout appareil');

$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

$cat=$_GET[categorie];
//echo "$cat";
//recupere la categorie de la page ajout appareil

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_labview.php\">Suite</a><br />\n";

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
	$table = "labview";

		$result = mysql_query("INSERT INTO $table ".
			"(manipch,technicien,localisation,matos,code,driver,module,ecran,pdf)".
			" VALUES ('$manipch','$technicien','$localisation','$matos','$code','$driver','$module', '$ecran', '$pdf')");
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
echo"<br /><br /><a href=\"labview.php\">Suite</a><br /><br />\n";
//quand on va sur suite, on retourne sur la page de la categorie choisie
pied_page();
exit();
}

?>
