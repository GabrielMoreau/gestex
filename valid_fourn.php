<?php

require("html_functions.php");

/// valid_fourn.php
//validation d'un nouveau fournisseur

unset($erreur);
unset($loggin);
unset($password);
unset($password2);
unset($nom);

//variables ne pouvant etre nulles

if (empty($_POST['nom']))
	$erreur = "nom non pr&eacute;cis&eacute;";
else {
	$nom = $_POST['nom'];
	if (empty($_POST['adresse']))
		$erreur = "Adresse non pr&eacute;cis&eacute;";
	else {
		$adresse = $_POST['adresse'];
		$mail = $_POST['addr_mail'];
		//variables pouvant etre nulles
		$www = $_POST['www'];
		$phone = $_POST['phone'];
		$fax = $_POST['fax'];
		$contact = $_POST['contact'];
		$descr = $_POST['descr'];
	}
}

en_tete('R&eacute;sultat inscription');

if (!empty($erreur)) {
	//erreur
	echo "<br />erreur :".$erreur;
	echo "<br /><a href=\"add_fourn.php\">Suite</a><br />\n";
	pied_page();
	exit();
}
else {
///tout est ok
//pas d'erreur
///on inscrit
require("db_functions.php");

if ($pdo = connect_db()) {
	//inscription
	// $table = "fournisseurs";
	$sql = "INSERT INTO fournisseurs (nom,adresse, mail, www, tel, fax, contact,descr)".
		" VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($nom, $adresse, $mail, $www, $phone, $fax, $contact, $descr));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (!$result) {
		//inscription !ok
		// $erreur = mysql_error();
		echo "<br />erreur ";
	}
} // end if connect

////en_tete('inscription Valid&eacute;e');

echo "inscription de ".$nom."<br />";
echo " <img src=\"images/pool_project.jpg\" nosave=\"\" height=\"100\" align=\"middle\" alt=\"\">";
echo " est valid&eacute;e ";
echo "<br /><br /><a href=\"list_fourn.php\">Suite</a><br /><br />\n";

pied_page();
exit();
}
?>
