<?php

require("html_functions.php");

/// valid_equip.php
//validation d'une nouvelle equipe

unset($erreur);
unset($loggin);
unset($password);
unset($password2);
unset($nom);

//variables ne pouvant etre nulles

if (empty($_POST['nom']))
	$erreur = 'Nom d\'&eacute;quipe non pr&eacute;cis&eacute;';
else {
	$nom = $_POST['nom'];
	if (empty($_POST['compte']))
		$erreur = 'Compte non pr&eacute;cis&eacute;';
	else {
		$compte = $_POST['compte'];
		$descr = $_POST['descr'];
		//variables pouvant etre nulles
		$chef = $_POST['chef'];
	}
}

en_tete('R&eacute;sultat inscription');

if (!empty($erreur)) {
	//erreur
	echo '<br />Erreur : '.$erreur;
	echo '<br /><a href="add_aquip.php">Suite</a><br />'.PHP_EOL;
	pied_page();
	exit();
}

///tout est ok
require("db_functions.php");

if ($pdo = connect_db()) {
	//inscription
	// $table = "equipe";
	$sql = 'INSERT INTO equipe (nom, descr, compte, chef) VALUES (?,  ?, ?, ?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($nom, $descr, $compte, $chef));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} //end if connect

echo "Inscription de ".$nom."<br />";
echo  <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo " est valid&eacute;e ";
echo "<br /><br /><a href=\"list_equip.php\">Suite</a><br /><br />\n";
pied_page();
?>
