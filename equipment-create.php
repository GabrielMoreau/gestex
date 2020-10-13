<?php
// equipment-create.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('equipment-create.php');
level_or_alert(3, 'Ajout d\'un appareil');

$logged_level = $_SESSION['logged_level'];

// validation d'un nouvel appareil

unset($erreur);

//variables ne pouvant etre nulles
$categorie = param_post('categorie');
if (empty($categorie))
	$erreur = 'Cat&eacute;gorie non pr&eacute;cis&eacute;';

$nom = param_post('nom');
if (empty($nom))
	$erreur = 'Nom de famille non pr&eacute;cis&eacute;';

$modele = param_post('modele');
if (empty($modele))
	$erreur = 'Mod&egrave;le non pr&eacute;cis&eacute;';

$feature = param_post('gamme');
if (empty($feature))
	$erreur = 'Caract&eacute;ristique non pr&eacute;cis&eacute;';

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
$reparation  = param_post('reparation');
$accessoires = param_post('accessoires');
$inventaire  = param_post('inventaire');
$barcode     = param_post('barcode');
$loanable    = param_post('loanable');

$notice = '';
if (isset($_FILES["notice"])) {
	$notice = $_FILES['notice']['name'];
	$notice = str_replace(' ', '_', $notice);
	$notice = str_replace('é', 'e', $notice);
	$notice = str_replace('è', 'e', $notice);
	$notice = str_replace('à', 'a', $notice);
}

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
	list($id_equipment, $err_msg) = set_equipment_new($pdo, $categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable);
	if ($err_msg != '' && $logged_level > 3)
		echo 'Erreur : '. $err_msg.'<br>';
	if ($notice != '')
		$id_datasheet = set_datasheet_new($pdo, $id_equipment, 'notice');
		if (!$id_datasheet)
			echo 'Erreur : la notice n\'est pas prise en compte car elle n\'est pas un fichier au format PDF !<br>';
} //end if connect

echo '<br>Ajout de '.$nom.' valid&eacute;e';
echo '<br><br><a href="equipment-list.php?highlight='.$id_equipment.'#item'.$id_equipment.'">Suite</a><br><br>';
?>

<?php pied_page() ?>
