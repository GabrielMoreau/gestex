<?php
// loan-create.php
$web_page = true;

require_once('module/html-functions.php');
require_once('module/db-functions.php');
require_once('module/base-functions.php');

//validation d'un pret
unset($erreur);

//variables ne pouvant etre nulles
$id_equipment = param_post('id_equipment');
if (empty($id_equipment))
	$erreur = 'Nom de l\'appareil non pr&eacute;cis&eacute;';

$id_equipe = param_post('equipe');
if (empty($id_equipe))
	$erreur = '&Eacute;quipe non pr&eacute;cis&eacute;';

$date_emprunt = param_post('emprunt');
if (empty($date_emprunt))
	$erreur = 'Date d\'emprunt non pr&eacute;cis&eacute;';

//variables pouvant etre nulles
$date_retour = param_post('retour');
$commentaire = param_post('commentaire');

en_tete('R&eacute;sultat demande d\'emprunt');

if (!empty($erreur)) {
	//erreur
	echo '<br />Erreur : '.$erreur;
	echo '<br /><a href="loan-add.php?id='.$id_equipment.'">Suite</a>';
	pied_page();
	exit();
}

if ($pdo = connect_db()) {
	$pret = get_loan_all_by_id_equipment($pdo, $id_equipment);
	if (!empty($pret)) {
		echo 'Erreur: l\'appareil est d&eacute;j&agrave; emprunt&eacute;';
		pied_page();
		exit();
	}

	// inscription
	$id_loan = set_loan_new($pdo, $id_equipment, $id_equipe, $date_emprunt, $date_retour, $commentaire);

	echo 'Ajout du pr&ecirc;t sur l\'appareil '.$id_equipment.' valid&eacute;<br />';
	echo '<br /><br /><a href="loan-list.php">Suite</a>';
} // end if connect
?>

<?php pied_page() ?>
