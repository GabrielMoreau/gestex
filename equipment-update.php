 <?php
// equipment-update.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('team-list.php');
level_or_alert(3, 'Modification d\'une &eacute;quipe');

//modification d'un appareil
unset($erreur);
//variables ne pouvant etre nulles
$id_equipment = param_post('id_equipment');
if (empty($id_equipment))
	$erreur = "Id non pr&eacute;cis&eacute;";

$categorie = param_post('categorie');
if (empty($categorie))
	$erreur = "Cat&eacute;gorie non pr&eacute;cis&eacute;";

$nom = param_post('nom');
if (empty($nom))
	$erreur = "Nom non pr&eacute;cis&eacute;";

$modele = param_post('modele');
if (empty($modele))
	$erreur = "Modele non pr&eacute;cis&eacute;";

$equipe = param_post('equipe');
if (empty($equipe))
	$erreur = "&Eacute;quipe non pr&eacute;cis&eacute;";

$tech = param_post('tech');
if (empty($tech))
	$erreur = "Tech non pr&eacute;cis&eacute;";

$fourn = param_post('fourn');
if (empty($fourn))
	$erreur = "Fournisseur non pr&eacute;cis&eacute;";

//variables pouvant etre nulles
$feature     = param_post('gamme');
$achat       = param_post('achat');
$reparation  = param_post('reparation');
$accessoires = param_post('accessoires');
$inventaire  = param_post('inventaire');
$barcode     = param_post('barcode');
$loanable    = param_post('loanable');

$notice = '';
if (isset($_FILES["notice"])) {
	$notice = $_FILES["notice"]["name"];
	$notice = str_replace(' ', '_', $notice);
	$notice = str_replace('é', 'e', $notice);
	$notice = str_replace('è', 'e', $notice);
	$notice = str_replace('à', 'a', $notice);
}

en_tete('R&eacute;sultat modification appareil');

if (!empty($erreur)) {
	//erreur
	echo '<br>Erreur : '.$erreur;
	echo '<br><a href="equipment-add.php?id='.$id_equipment.'">Suite</a><br>\n';

	pied_page();
	exit();
}

// tout est ok
if ($pdo = connect_db()) {

	//recupere les anciennes caracteristiques
	$equipment_registered = get_equipment_all_by_id($pdo, $id_equipment);

	//modification app
	$modif = 0;
	if (($categorie != $equipment_registered['categorie'])
		|| ($nom != $equipment_registered['nom'])
		|| ($modele != $equipment_registered['modele'])
		|| ($feature != $equipment_registered['gamme'])
		|| ($tech != $equipment_registered['responsable'])
		|| ($equipe != $equipment_registered['equipe'])
		|| ($fourn != $equipment_registered['fournisseur'])
		|| ($achat != $equipment_registered['achat'])
		|| ($reparation != $equipment_registered['reparation'])
		|| ($accessoires != $equipment_registered['accessoires'])
		|| ($inventaire != $equipment_registered['inventaire'])
		|| ($notice != $equipment_registered['notice'])
		|| ($barcode != $equipment_registered['barcode'])
		|| ($loanable != $equipment_registered['loanable']))
		$modif = 1;

	if ($modif != 0) {
		$err_msg = set_equipment_update($pdo, $id_equipment, $categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable);
		if ($err_msg != '' && $logged_level > 3)
			echo 'Erreur : '. $err_msg.'<br>';
		if ($notice != '') {
			$id_datasheet = set_datasheet_new($pdo, $id_equipment, $_FILES["notice"]["name"], $_FILES["notice"]["tmp_name"]);
			echo "New datasheet ".$id_equipment." number # ".$id_datasheet;
		}
	} // end if modif
	else {
		echo 'Aucune modification &agrave; faire';
		echo '<br><br><a href="equipment-view.php?id='.$id_equipment.'">Suite</a><br><br>\n';
		pied_page();
		exit();
	} // else end
} // end if connect

// quand on va sur suite, on retourne sur la page de la categorie choisie
redirect('equipment-view.php?id='.$id_equipment);
?>

<?php pied_page() ?>
