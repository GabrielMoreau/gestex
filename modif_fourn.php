<?php
/// modif_fourn.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

auth_or_login('modif_fourn.php');
level_or_alert(3, 'Modification d\'un fournisseur');

$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

//modification d'un fournisseur

unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST['id_fourn']))
	$erreur = "id non pr&eacute;cis&eacute;";
else {
	$id_fourn = $_POST['id_fourn'];

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
	echo '<br /><a href="list_fourn.php?highlight='.$id_fourn.'#'.$id_fourn.'">Suite</a><br />';
	pied_page();
	exit();
}

if ($pdo = connect_db()) {

	//recupere les anciennes caracteristiques

	$sql = 'SELECT * FROM fournisseurs WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_fourn));
	$fournisseur = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//modification fournisseur
	//on construit la demande
	$querry = "UPDATE LOW_PRIORITY fournisseurs SET ";
	if ($nom!=$fournisseur[0]['nom'])
		//modif du nom
		$querry.="nom='$nom',";
	if ($adresse!=$fournisseur[0]['adresse'])
		//modif de l' adresse
		$querry.="adresse='$adresse',";
	if ($tel!=$fournisseur[0]['tel'])
		//modif du tel
		$querry.="tel='$tel',";
	if ($fax!=$fournisseur[0]['fax'])
		//modif du fax
		$querry.="fax='$fax',";
	if ($mail!=$fournisseur[0]['mail'])
		//modif du mail
		$querry.="mail='$mail',";
	if ($www!=$fournisseur[0]['www'])
		//modif de l'url
		$querry.="www='$www',";
	if ($contact!=$fournisseur[0]['contact'])
		//modif des contacts
		$querry.="contact='$contact',";
	if ($descr!=$fournisseur[0]['descr'])
		//modif de la descr
		$querry.="descr='$descr',";
		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id = '$id_fourn'";

	if ($user_level >= 3)
		$stmt = $pdo->prepare($querry);
		$stmt->execute();

} //end if connect

////en_tete('modification fournisseur Valid&eacute;e');

// echo "<br />".$nom." modifi&eacute; ";
// echo " <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
// echo "  valid&eacute;e !!";
// echo "<br /><br /><a href=\"list_fourn.php\">Suite</a><br /><br />\n";

Header('Location: list_equip.php?highlight='.$id_fourn.'#'.$id_fourn);
exit();
?>
