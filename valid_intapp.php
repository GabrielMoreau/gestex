<?php

require("html_functions.php");

/// valid_intapp.php
//validation d'une nouvelle intervention appareil
unset($erreur);
//variables ne pouvant etre nulles

if (empty($_POST[id_app]))
		$erreur="id appareil non pr&eacute;cis&eacute;";	
else{
		$id_app=$_POST[id_app];
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
							
}}}}

en_tete("resultat ajout intervention");



if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_intapp.php\">Suite</a><br />\n";

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
	$table = "intervention";
		$result = mysql_query("INSERT INTO $table ".
			"(descr, appareil, tech, fournisseur, date, facture)".
			" VALUES ( '$descr', '$id_app', '$tech', '$fourn', '$date', '$facture')");
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
		echo "<br />erreur :".$erreur;
		}
		
	}//end if connect

////en_tete("inscription Valid&eacute;e");

echo "ajout d'une intervention pour l'appareil ".$id_app."<br />";
echo" <img src=\"images/pool_project.jpg\" nosave=\"\" height=\"100\" align=\"middle\" alt=\"\">";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"list_intapp.php?id=".$id_app."\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
