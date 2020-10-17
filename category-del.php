<?php
// category-del.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

auth_or_login('category-list.php');
level_or_alert(3, 'Suppression d\'une cat&eacute;gorie');

$category_id = param_post_or_get('id');
$valid       = param_post('ok', 'no');

if (empty($category_id) || $valid == 'cancel')
	redirect('category-list.php');

$pdo = connect_db();
$category_name = get_category_by_id($pdo, $category_id)['nom'];

if ($valid == 'yes') {
	// on supprime la categorie
	$flag = del_category_by_id($pdo, $category_id);
	if ($flag) // ca a marche
		redirect('category-list.php');

	redirect('category-list.php');
	$message_alert = 'Erreur dans la suppression de la cat&eacute;gorie : '.$category_id;
	include_once('include/alert-data.php');
	exit;
	}


// $category_id $category_name
include_once('include/category-del.php');
?>
