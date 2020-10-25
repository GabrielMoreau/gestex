<?php
// category-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('category-list.php');
level_or_alert(3, 'Suppression d\'une cat&eacute;gorie');

$category_id = param_post_or_get('id', 0);
$valid       = param_post('ok', 'no');

if ($category_id == 0 || $valid == 'cancel')
	redirect('category-list.php');

$pdo = connect_db_or_alert();
$category_name = get_category_by_id($pdo, $category_id)['nom'];

if ($valid == 'yes') {
	$iostat = del_category_by_id($pdo, $category_id);
	if ($iostat) // ca a marche
		redirect('category-list.php');
	$message_alert = 'Erreur dans la suppression de la cat&eacute;gorie : '.$category_name.' (#'.$category_id.')';
	include_once('include/alert-data.php');
	exit();
}

// $category_id
// $category_name
include_once('include/category-del.php');
exit();
?>
