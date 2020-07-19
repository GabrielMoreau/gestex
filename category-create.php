<?php
// category-create.php

require_once('auth-functions.php');
require_once('html-functions.php');

auth_or_login('category-create.php');
level_or_alert(3, 'Ajout d\'une cat&eacute;gorie');

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

en_tete('R&eacute;sultat ajout appareil');

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"category-add.php\">Suite</a><br />\n";

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

////en_tete('inscription Valid&eacute;e');

echo "<br />ajout de ".$categorie." valid&eacute;e ";
echo"<br /><br /><a href=\"equipment-list.php\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
