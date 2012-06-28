<?php

require("mise_en_page.php");

/// valid_categorie.php
//validation d'un nouvel appareil
unset($erreur);
//variables ne pouvant etre nulles

if (empty($_POST[categorie]))
	$erreur="categorie non pr&eacute;cis&eacute;";	
else{
	$categorie =$_POST[categorie];


	}				
							


en_tete("resultat ajout appareil ");



if (!empty($erreur) ){

	//erreur

	echo "<br>erreur :".$erreur;
	echo"<br><a href=\"add_categorie.php\">Suite</a><br>\n";

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
	$table = "categorie";

		$result = mysql_query("INSERT INTO $table ".
			"(nom)".
			" VALUES ('$categorie')");
			//	
if (!$result){
			//inscription !ok
			$erreur = mysql_error();
		echo "<br>erreur :".$erreur;
		}
		
	}//end if connect

////en_tete("inscription Valid&eacute;e");

echo "<br>ajout de ".$nom."<br>";
echo" est valid&eacute;e ";
echo"<br><br><a href=\"instru.php\">Suite</a><br><br>\n";
pied_page();
exit();
}

?>
