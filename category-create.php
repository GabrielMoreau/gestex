<?php
// category-create.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

auth_or_login('category-create.php');
level_or_alert(3, 'Ajout d\'une cat&eacute;gorie');

unset($erreur);

$id_category = param_post('id_category'); // -> modify
$flag_new = true;
if (!empty($id_category))
	$flag_new = false;

//variables ne pouvant etre nulles
$categorie_name = strtolower(param_post('categorie_name'));
if (empty($categorie_name))
	$erreur = 'Cat&eacute;gorie non pr&eacute;cis&eacute;e';

$pdo = connect_db_or_alert();

if (check_category_by_name($pdo, $categorie_name))
	$erreur = 'La cat&eacute;gorie <i>'.$categorie_name.'</i> existe d&eacute;j&agrave;';

if (!empty($erreur)) {
	//erreur
	$title        = 'Erreur';
	$action       = 'category-list.php';
	$message_text = $erreur;
	include_once('include/warning-box.php');
	exit();
}

if ($flag_new)
	$id_category = set_category_new($pdo, $categorie_name);
else
	set_category_update($pdo, $id_category, $categorie_name);

$title        = 'R&eacute;sultat ajout/modification cat&eacute;gorie';
$action       = 'category-list.php';
$highlight    = $id_category;
$message_text = 'Ajout/modification de la cat&eacute;gorie '.$categorie_name.' valid&eacute;e';
include_once('include/message-box.php');
exit();
?>
