<?php

require("html_functions.php");

/// valid_equip.php
//validation d'une nouvelle equipe

unset($erreur);unset($loggin);unset($password);unset($password2); unset($nom);
//variables ne pouvant etre nulles

				if (empty($_POST[nom]))
					$erreur="nom non pr&eacute;cis&eacute;";
				else{
					$nom =$_POST[nom];
					if (empty($_POST[compte]))
						$erreur="Compte non pr&eacute;cis&eacute;";
					else{
						$compte = $_POST[compte];

							$descr=$_POST[descr];
							//variables pouvant etre nulles
							$chef =$_POST[chef];

}}

en_tete('R&eacute;sultat inscription');

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_aquip.php\">Suite</a><br />\n";

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
	$table = "equipe";
		$result = mysql_query("INSERT INTO $table ".
			"(nom,descr,compte,chef)".
			" VALUES ('$nom',  '$descr', '$compte', '$chef')");
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
		echo "<br />erreur :".$erreur;
		}

	}//end if connect

////en_tete('inscription Valid&eacute;e');

echo "inscription de ".$nom."<br />";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"list_equip.php\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
