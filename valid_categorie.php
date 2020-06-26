<?php

require("mise_en_page.php");
require("db_functions.php");

/// valid_categorie.php
//validation d'un nouvel appareil
unset($erreur);
//variables ne pouvant etre nulles

if (empty($_POST['categorie']))
	$erreur="categorie non pr&eacute;cis&eacute;";	
else{
	$categorie =$_POST['categorie'];
	if($pdo = connect_db()){
		$sql = 'SELECT * FROM categorie WHERE nom = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($categorie));
		$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($categories)){
			$erreur = "le categorie existe deja";
		}
	}	
	

	}				


en_tete("resultat ajout appareil ");



if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_categorie.php\">Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit


		//inscription
if(	$pdo = connect_db()){
	$sql ='INSERT INTO categorie (nom) VALUE (?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($categorie));
		// $result = mysql_query("INSERT INTO $table ".
		// 	"(nom)".
		// 	" VALUES ('$categorie')");
			//	
}else 
// catch  (PDOException $exception){
	echo 'Request error: ';
		
//end if connect

////en_tete("inscription Valid&eacute;e");

echo "<br />ajout de ".$nom."<br />";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"instru.php\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
