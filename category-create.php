<?php
// category-create.php

require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

auth_or_login('category-create.php');
level_or_alert(3, 'Ajout d\'une cat&eacute;gorie');

unset($erreur);
//variables ne pouvant etre nulles

$categorie = param_post('categorie');
if (empty($categorie))
	$erreur = 'categorie non pr&eacute;cis&eacute;';

$pdo = connect_db();

if (check_category_by_name($pdo, $categorie))
	$erreur = 'La cat&eacute;gorie existe d&eacute;j&agrave;';

en_tete('R&eacute;sultat ajout cat&eacute;gorie');

if (!empty($erreur)) {
	//erreur
	echo '<br />Erreur : '.$erreur;
	echo '<br /><a href="category-add.php">Suite</a><br />';
	pied_page();
	exit();
}

$sql = 'INSERT INTO categorie (nom) VALUE (?);';
$stmt = $pdo->prepare($sql);
$stmt->execute(array($categorie));

echo '<br />ajout de '.$categorie.' valid&eacute;e';
echo '<br /><br /><a href="equipment-list.php">Suite</a><br /><br />';
?>

<?php pied_page() ?>
