<?php
// category-process.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

auth_or_login('category-process.php');
level_or_alert(3, 'Ajout d’une catégorie');

unset($err_msg);

$category_id = (int)param_post('category_id'); // -> modify
$flag_new = ($category_id === 0);

//variables ne pouvant etre nulles
$categorie_name = strtolower(param_post('categorie_name'));
if (empty($categorie_name))
	$err_msg = 'Catégorie non précisée';

$pdo = connect_db_or_alert();

if (check_category_by_name($pdo, $categorie_name))
	$err_msg = 'La catégorie <i>'.$categorie_name.'</i> existe déjà';

if (!empty($err_msg)) {
	//erreur
	$title        = 'Erreur';
	$action       = 'category-list.php';
	$message_text = $err_msg;
	include_once('include/warning-box.php');
	exit();
}

if ($flag_new)
	$category_id = set_category_new($pdo, $categorie_name);
else
	set_category_update($pdo, $category_id, $categorie_name);

$title        = 'Résultat ajout/modification catégorie';
$action       = 'category-list.php';
$highlight    = $category_id;
$message_text = 'Ajout/modification de la catégorie '.$categorie_name.' validée';
include_once('include/message-box.php');
exit();
?>
