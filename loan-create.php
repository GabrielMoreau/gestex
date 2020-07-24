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

$nom = param_post('nom');
if (empty($nom))
	$erreur = 'Nom de l\'appareil non pr&eacute;cis&eacute;';

$equipe = param_post('equipe');
if (empty($equipe))
	$erreur = '&Eacute;quipe non pr&eacute;cis&eacute;';

$emprunt = param_post('emprunt');
if (empty($emprunt))
	$erreur = 'Date d\'emprunt non pr&eacute;cis&eacute;';

//variables pouvant etre nulles
$retour      = param_post('retour');
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
	$sql = 'SELECT * FROM pret WHERE nom = ? AND equipe = ?;';
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($nom, $equipe));
	$pret = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (!empty($pret)) {
		echo 'Erreur: l\'appareil est d&eacute;j&agrave; emprunt&eacute;';
		pied_page();
		exit();
	}

	// inscription
	$sql = 'INSERT INTO pret (nom, equipe, emprunt, retour, commentaire) VALUES (?, ?, ?, ?, ?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($nom, $equipe, $emprunt, $retour, $commentaire));

	echo 'Ajout du pr&ecirc;t sur l\'appareil '.$nom.' valid&eacute;<br />';
	echo '<br /><br /><a href="loan-list.php">Suite</a>';
} // end if connect
?>

<?php pied_page() ?>
