<?php
// supplier-update.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('supplier-update.php');
level_or_alert(3, 'Modification d\'un fournisseur');

$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

//modification d'un fournisseur

unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST['id_fourn']))
	$erreur = "id non pr&eacute;cis&eacute;";
else {
	$id_supplier = $_POST['id_fourn'];

	if (empty($_POST['nom']))
		$erreur = "nom non pr&eacute;cis&eacute;";
	else {
		$nom=$_POST['nom'];
		if (empty($_POST['adresse']))
			$erreur = "adresse non pr&eacute;cis&eacute;";
		else {
			$adresse = $_POST['adresse'];
			$tel = $_POST['phone'];
			$fax = $_POST['fax'];
			$mail = $_POST['addr_mail'];
			$www = $_POST['www'];
			$contact = $_POST['contact'];
			$descr = $_POST['descr'];
		}
	}
}

en_tete('R&eacute;sultat modification');

if (!empty($erreur)) {
	//erreur
	echo '<br />Erreur : '.$erreur;
	echo '<br /><a href="supplier-list.php?highlight='.$id_supplier.'#item'.$id_supplier.'">Suite</a><br />';
	pied_page();
	exit();
}

if ($pdo = connect_db()) {

	//recupere les anciennes caracteristiques
	$supplier = get_supplier_all_by_id($pdo, $id_supplier);

	//modification fournisseur
	//on construit la demande
	$querry = "UPDATE LOW_PRIORITY fournisseurs SET ";
	if ($nom != $supplier['nom'])
		//modif du nom
		$querry.= "nom='$nom',";
	if ($adresse != $supplier['adresse'])
		//modif de l' adresse
		$querry .= "adresse='$adresse',";
	if ($tel != $supplier['tel'])
		//modif du tel
		$querry.="tel='$tel',";
	if ($fax != $supplier['fax'])
		//modif du fax
		$querry.="fax='$fax',";
	if ($mail != $supplier['mail'])
		//modif du mail
		$querry.="mail='$mail',";
	if ($www != $supplier['www'])
		//modif de l'url
		$querry .= "www='$www',";
	if ($contact != $supplier['contact'])
		//modif des contacts
		$querry .= "contact='$contact',";
	if ($descr != $supplier['descr'])
		//modif de la descr
		$querry .= "descr='$descr',";
		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry .= " WHERE id = '$id_supplier'";

	if ($logged_level >= 3)
		$stmt = $pdo->prepare($querry);
		$stmt->execute();

} // end if connect

redirect('supplier-list.php?highlight='.$id_supplier.'#item'.$id_supplier);
?>
