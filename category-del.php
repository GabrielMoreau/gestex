<?php
// category-del.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

auth_or_login('category-list.php');
level_or_alert(3, 'Suppression d\'une cat&eacute;gorie');

$id_category = param_post_or_get('id');
$valid       = param_post('ok', 'no');

if (empty($id_category) || $valid == 'cancel')
	redirect('category-list.php');

$pdo = connect_db();
$category_name = get_category_by_id($pdo, $id_category)['nom'];

if ($valid == 'yes') {
	// on supprime la categorie
	$result = del_category_by_id($pdo, $id_category);
	if (!$result) { // si ca n'a pas marche
		echo "<br />Erreur dans la suppression de la cat&eacute;gorie : ".$id_category;
	}
	//on retourne a la page precedente
	redirect('category-list.php');
}

// $id_category $category_name
include_once('include/category-del.php');
?>
