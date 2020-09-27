<?php
// equipment-create.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('equipment-create.php');
level_or_alert(3, 'Ajout d\'un appareil');

// validation d'un nouvel appareil

unset($erreur);

//variables ne pouvant etre nulles
$categorie = param_post('categorie');
if (empty($categorie))
	$erreur = 'Cat&eacute;gorie non pr&eacute;cis&eacute;';

$nom = param_post('nom');
if (empty($nom))
	$erreur = 'Nom de famille non pr&eacute;cis&eacute;';

$modele = param_post('modele'];
if (empty($modele))
	$erreur = 'Mod&egrave;le non pr&eacute;cis&eacute;';

$gamme = param_post('gamme');
if (empty($gamme))
	$erreur = 'Gamme non pr&eacute;cis&eacute;';

$equipe = param_post('equipe');
if (empty($equipe))
	$erreur = '&Eacute;quipe non pr&eacute;cis&eacute;';

$fourn = param_post('fourn');
if (empty($fourn))
	$erreur = 'Fournisseur non pr&eacute;cis&eacute;';

$achat = param_post('achat');
if (empty($achat))
	$erreur = 'Achat non pr&eacute;cis&eacute;';

$tech = param_post('tech');
if (empty($tech))
	$erreur = 'Tech non pr&eacute;cis&eacute;';

// variables pouvant etre nulles
$reparation  = param_post('reparation'];
$accessoires = param_post('accessoires'];
$inventaire  = param_post('inventaire'];
$barcode     = param_post('barcode'];
$loanable    = param_post('loanable'];

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
	$id_app = set_equipment_new($pdo, $categorie, $nom, $modele, $gamme, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable);

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
echo '<br /><br /><a href="equipment-list.php?highlight='.$id_app.'#item'.$id_app.'">Suite</a><br /><br />';

pied_page();
?>
