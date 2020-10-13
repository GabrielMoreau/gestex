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
	$notice = $_FILES['notice']['name'];
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
$pdo = connect_db_or_alert();

//recupere les anciennes caracteristiques
$equipment_selected = get_equipment_all_by_id($pdo, $id_equipment);

//modification app
$modif = false;
if (   ($categorie   != $equipment_selected['categorie'])
	|| ($nom         != $equipment_selected['nom'])
	|| ($modele      != $equipment_selected['modele'])
	|| ($feature     != $equipment_selected['gamme'])
	|| ($tech        != $equipment_selected['responsable'])
	|| ($equipe      != $equipment_selected['equipe'])
	|| ($fourn       != $equipment_selected['fournisseur'])
	|| ($achat       != $equipment_selected['achat'])
	|| ($reparation  != $equipment_selected['reparation'])
	|| ($accessoires != $equipment_selected['accessoires'])
	|| ($inventaire  != $equipment_selected['inventaire'])
	|| ($notice      != $equipment_selected['notice'])
	|| ($barcode     != $equipment_selected['barcode'])
	|| ($loanable    != $equipment_selected['loanable']))
	$modif = true;

if ($modif) {
	$err_msg = set_equipment_update($pdo, $id_equipment, $categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable);
	if ($err_msg != '' && $logged_level > 3)
		echo 'Erreur : '. $err_msg.'<br>';
	if ($notice != '') {
		$id_datasheet = set_datasheet_new($pdo, $id_equipment, 'notice');
		if (!$id_datasheet)
			echo 'Erreur : la notice n\'est pas prise en compte car elle n\'est pas un fichier au format PDF !<br>';
	}
} // end if modif
else {
	echo 'Aucune modification &agrave; faire';
	echo '<br><br><a href="equipment-view.php?id='.$id_equipment.'">Suite</a><br><br>\n';
	pied_page();
	exit();
} // else end

// quand on va sur suite, on retourne sur la page de la categorie choisie
redirect('equipment-view.php?id='.$id_equipment);
?>

<?php pied_page() ?>
