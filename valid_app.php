<?php

require("html_functions.php");

/// valid_app.php
//validation d'un nouvel appareil
unset($erreur);
//variables ne pouvant etre nulles

if (empty($_POST[nom]))
	$erreur="nom non pr&eacute;cis&eacute;";	
else{
	$nom =$_POST[nom];
	if (empty($_POST[descr]))
		$erreur="Description non pr&eacute;cis&eacute;";	
	else{
		$descr=$_POST[descr];
		if (empty($_POST[equipe]))
			$erreur="equipe non pr&eacute;cis&eacute;";	
		else{
			$equipe =$_POST[equipe];
			if (empty($_POST[tech]))
				$erreur="tech non pr&eacute;cis&eacute;";	
			else{
				$tech =$_POST[tech];
				if (empty($_POST[fourn]))
					$erreur="fournisseur non pr&eacute;cis&eacute;";	
				else{
					$fourn =$_POST[fourn];

							//variables pouvant etre nulles
					$achat = $_POST[achat];
					$facture=$_POST[facture];
							
}}}}}

en_tete("resultat ajout appareil ");



if (!empty($erreur) ){

	//erreur

	echo "<br>erreur :".$erreur;
	echo"<br><a href=\"add_app.php\">Suite</a><br>\n";

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
	$table = "appareils";
		$result = mysql_query("INSERT INTO $table ".
			"(nom,descr, equipe, tech, fournisseur, achat, facture)".
			" VALUES ('$nom', '$descr', '$equipe', '$tech', '$fourn', '$achat', '$facture')");
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
		echo "<br>erreur :".$erreur;
		}
		
	}//end if connect

////en_tete("inscription Valid&eacute;e");

echo "ajout de ".$nom."<br>";
echo" <img src=\"images/pool_project.jpg\" nosave=\"\" height=\"100\" align=\"middle\" alt=\"\">";
echo" est valid&eacute;e ";
echo"<br><br><a href=\"list_app.php\">Suite</a><br><br>\n";
pied_page();
exit();
}

?>
