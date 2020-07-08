<?php

require("html_functions.php");
/// valid_app.php
//validation d'un nouvel appareil
unset($erreur);
//variables ne pouvant etre nulles

if (empty($_POST['nom']))
	$erreur="nom non pr&eacute;cis&eacute;";
else{
	$nom =$_POST['nom'];
	if (empty($_POST['descr']))
		$erreur="Description non pr&eacute;cis&eacute;";
	else{
		$descr=$_POST['descr'];
		if (empty($_POST['equipe']))
			$erreur="&Eacute;quipe non pr&eacute;cis&eacute;";
		else{
			$equipe =$_POST['equipe'];
			if (empty($_POST['tech']))
				$erreur="tech non pr&eacute;cis&eacute;";
			else{
				$tech =$_POST['tech'];
				if (empty($_POST['fourn']))
					$erreur="fournisseur non pr&eacute;cis&eacute;";
				else{
					$fourn =$_POST['fourn'];

							//variables pouvant etre nulles
					if(empty($_POST['achat'])){
						$achat='';
					}else{
						$achat = $_POST['achat'];

						if(empty($_POST['facture'])){
							$facture = '';
						}else{
							$facture=$_POST['facture'];
						}
					}
				}
			}
		}
	}
}


en_tete('R&eacute;sultat ajout appareil');
if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_machine.php\">Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit
require("db_functions.php");

if ( $pdo = connect_db() ){
		//inscription
	// $table = "appareils";
	// 	$result = mysql_query("INSERT INTO $table ".
	// 		"(nom,descr, equipe, tech, fournisseur, achat, facture)".
	// 		" VALUES ('$nom', '$descr', '$equipe', '$tech', '$fourn', '$achat', '$facture')");
			//
	$sql = 'INSERT INTO appareils (nom,descr,equipe,tech, fournisseur, achat, facture) VALUES ( ?, ?, ?, ?, ?, ?, ?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($nom, $descr, $equipe, $tech, $fourn, $achat, $facture));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo "<br /> Votre requ&ecirc;te a bien &eacute;t&eacute; ajout&eacute;";
	}//end if connect



////en_tete('inscription Valid&eacute;e');

echo "ajout de ".$nom."valid&eacute;e ";
echo"<br /><br /><a href=\"list_machine.php\">Suite</a><br /><br />\n";
pied_page();
}

?>
