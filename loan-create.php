<?php
// loan-create.php
$web_page = true;

require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('loan-list.php');
level_or_alert(3, 'Modification d\'un pr&ecirc;t');

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

// modify an existing loan
$id_loan = param_post('id_loan');
$flag_new = true;
if (!empty($id_loan))
	$flag_new = false;

en_tete('R&eacute;sultat demande d\'emprunt');

if (!empty($erreur)) {
	//erreur
	echo '<br />Erreur : '.$erreur;
	if ($flag_new == true)
		echo '<br /><a href="loan-add.php?equipment='.$id_equipment.'">Suite</a>';
	else
		echo '<br /><a href="loan-add.php?id='.$id_loan.'">Suite</a>';
	pied_page();
	exit();
}

if ($pdo = connect_db()) {
	if ($flag_new == true) {
		$loan = get_loan_short_by_id_equipment($pdo, $id_equipment);
		if (!empty($loan)) {
			echo 'Erreur: l\'appareil est d&eacute;j&agrave; emprunt&eacute;';
			pied_page();
			exit();
		}

		// inscription
		$id_loan = set_loan_new($pdo, $id_equipment, $id_equipe, $date_emprunt, $date_retour, $commentaire);
		echo 'Ajout du pr&ecirc;t sur l\'appareil '.$id_equipment.' valid&eacute;<br />';
	}
	else {
		set_loan_update($pdo, $id_loan, $id_equipment, $id_equipe, $date_emprunt, $date_retour, $commentaire);
		echo 'Mise &agrave; jour du pr&ecirc;t sur l\'appareil '.$id_equipment.' valid&eacute;<br />';
	}
	echo '<br /><br /><a href="loan-list.php">Suite</a>';
} // end if connect
?>

<?php pied_page() ?>
