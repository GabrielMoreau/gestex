<?php
// valid_fourn.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

auth_or_login('valid_fourn.php');
level_or_alert(3, 'Ajout d\'un fournisseur');

//validation d'un nouveau fournisseur

unset($erreur);
unset($loggin);
unset($password);
unset($password2);
unset($nom);

//variables ne pouvant etre nulles

if (empty($_POST['nom']))
	$erreur = 'Nom du fournisseur non pr&eacute;cis&eacute;';
else {
	$nom = $_POST['nom'];
	if (empty($_POST['adresse']))
		$erreur = 'Adresse non pr&eacute;cis&eacute;';
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
	echo '<br />Erreur :'.$erreur;
	echo '<br /><a href="add_fourn.php">Suite</a><br />';
	pied_page();
	exit();
}

if ($pdo = connect_db()) {
	//inscription
	$sql = 'INSERT INTO fournisseurs (nom, adresse, mail, www, tel, fax, contact, descr) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($nom, $adresse, $mail, $www, $phone, $fax, $contact, $descr));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$id_fourn = $pdo->lastInsertId();
	if (!$result) {
		//inscription !ok
		// $erreur = mysql_error();
		echo '<br />Erreur ';
	}

////en_tete('inscription Valid&eacute;e');

	echo 'Ajout de '.$nom.' validé<br />';
	echo '<br /><br /><a href="list_fourn.php?highlight='.$id_fourn.'#'.$id_fourn.'">Suite</a><br /><br />';
	} //end if connect

pied_page();
?>
