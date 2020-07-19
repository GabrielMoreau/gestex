<?php
// equipment-create.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

auth_or_login('equipment-create.php');
level_or_alert(3, 'Ajout d\'un appareil');

// validation d'un nouvel appareil

unset($erreur);

//variables ne pouvant etre nulles
if (empty($_POST['categorie']))
	$erreur = 'Cat&eacute;gorie non pr&eacute;cis&eacute;';
else
	$categorie = $_POST['categorie'];

if (empty($_POST['nom']))
	$erreur = 'Nom de famille non pr&eacute;cis&eacute;';
else
	$nom = $_POST['nom'];

if (empty($_POST['modele']))
	$erreur = 'Mod&egrave;le non pr&eacute;cis&eacute;';
else
	$modele = $_POST['modele'];

if (empty($_POST['gamme']))
	$erreur = 'Gamme non pr&eacute;cis&eacute;';
else
	$gamme = $_POST['gamme'];

if (empty($_POST['equipe']))
	$erreur = '&Eacute;quipe non pr&eacute;cis&eacute;';
else
	$equipe = $_POST['equipe'];

if (empty($_POST['fourn']))
	$erreur = 'Fournisseur non pr&eacute;cis&eacute;';
else
	$fourn = $_POST['fourn'];

if (empty($_POST['achat']))
	$erreur = 'Achat non pr&eacute;cis&eacute;';
else
	$achat = $_POST['achat'];

if (empty($_POST['tech']))
	$erreur = 'Tech non pr&eacute;cis&eacute;';
else
	$tech = $_POST['tech'];

// variables pouvant etre nulles
$reparation  = $_POST['reparation'];
$accessoires = $_POST['accessoires'];
$inventaire  = $_POST['inventaire'];

$notice = $_FILES["notice"]["name"];
$notice = str_replace(' ', '_', $notice);
$notice = str_replace('é', 'e', $notice);
$notice = str_replace('è', 'e', $notice);
$notice = str_replace('à', 'a', $notice);

en_tete('R&eacute;sultat ajout appareil');

$cat = $_GET['categorie'];
//recupere la categorie de la page ajout appareil

if (!empty($erreur) ){
	//erreur
	echo '<br />Erreur : '.$erreur;
	echo '<br /><a href="equipment-add.php">Suite</a><br />';
	pied_page();
	exit();
}

// tout est ok
if ($pdo = connect_db()) {
	$sql = 'INSERT INTO Listing (categorie, nom, modele, gamme, equipe, fournisseur, achat, responsable, reparation, accessoires, inventaire, notice) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($categorie, $nom, $modele, $gamme, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice));
	$id_app = $pdo->lastInsertId();

	$path = './data';
	if (!is_dir($path)) {
		mkdir($path, 0750);
	}
	$path = './data/notice';
	if (!is_dir($path)) {
		mkdir($path, 0750);
	}
	$path = './data/notice/'.$id_app;
	if (!is_dir($path)) {
		mkdir($path, 0750);
	}
	move_uploaded_file($_FILES['notice']['tmp_name'], $path.'/'.$notice );

	$sql = 'INSERT INTO notice (nom_notice, chemin_notice, id_appareil) VALUES (?, ?, ?);';
	$stmt = $pdo->prepare($sql);
	$path_complet = $path."/".$notice;
	$stmt->execute(array($notice,$path_complet,$id_app));

} //end if connect

echo '<br />Ajout de '.$nom.' valid&eacute;e';
echo '<br /><br /><a href="equipment-list.php?highlight='.$id_app.'#'.$id_app.'">Suite</a><br /><br />';

pied_page();
?>
