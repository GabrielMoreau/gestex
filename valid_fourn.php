<?php

require("html_functions.php");

/// valid_fourn.php
//validation d'un nouveau fournisseur

unset($erreur);unset($loggin);unset($password);unset($password2); unset($nom);
//variables ne pouvant etre nulles

				if (empty($_POST[nom]))
					$erreur="nom non pr&eacute;cis&eacute;";	
				else{
					$nom =$_POST[nom];
					if (empty($_POST[adresse]))
						$erreur="Adresse non pr&eacute;cis&eacute;";	
					else{
						$adresse = $_POST[adresse];
						
							$mail=$_POST[addr_mail];
							//variables pouvant etre nulles
							$www =$_POST[www];
							$phone =$_POST[phone];
							$fax =$_POST[fax];
							$contact =$_POST[contact];
							$descr =$_POST[descr];
}}

en_tete("resultat inscription ");



if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_fourn.php\">Suite</a><br />\n";

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
	$table = "fournisseurs";
		$result = mysql_query("INSERT INTO $table ".
			"(nom,adresse, mail, www, tel, fax, contact,descr)".
			" VALUES ('$nom', '$adresse', '$mail', '$www', '$phone', '$fax', '$contact', '$descr')");
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
		echo "<br />erreur :".$erreur;
		}
		
	}//end if connect

////en_tete("inscription Valid&eacute;e");

echo "inscription de ".$nom."<br />";
echo" <img src=\"images/pool_project.jpg\" nosave=\"\" height=\"100\" align=\"middle\" alt=\"\">";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"list_fourn.php\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
