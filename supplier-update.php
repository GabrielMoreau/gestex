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
	$supplier_registered = get_supplier_all_by_id($pdo, $id_supplier);

	//modification fournisseur
	$modif = 0;
	if (($nom != $supplier_registered['nom'])
		|| ($adresse != $supplier_registered['adresse'])
		|| ($tel != $supplier_registered['tel'])
		|| ($fax != $supplier_registered['fax'])
		|| ($mail != $supplier_registered['mail'])
		|| ($www != $supplier_registered['www'])
		|| ($contact != $supplier_registered['contact'])
		|| ($descr != $supplier_registered['descr']))
		$modif = 1;

	if ($modif != 0) {
		$err_msg = set_supplier_update($pdo, $id_supplier, $nom, $adresse, $tel, $fax, $mail, $www, $contact, $descr)
		if ($err_msg != '' && $logged_level > 3) {
			echo 'Erreur : '. $err_msg.'<br>';
			echo '<br><br><a href="supplier-list.php?highlight='.$id_supplier.'#item'.$id_supplier.'">Suite</a><br><br>\n';
			pied_page();
			exit();
		}
	} // end if modif
	else {
		echo 'Aucune modification &agrave; faire';
		echo '<br><br><a href="supplier-list.php?highlight='.$id_supplier.'#item'.$id_supplier.'">Suite</a><br><br>\n';
		pied_page();
		exit();
	}
} // end if connect

redirect('supplier-list.php?highlight='.$id_supplier.'#item'.$id_supplier);
?>
