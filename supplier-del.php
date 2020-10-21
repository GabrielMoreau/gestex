<?php
// supplier-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('supplier-list.php');
level_or_alert(3, 'Suppression d\'un fournisseur');

$supplier_id = param_post_or_get('id', 0);
$valid       = param_post('ok', 'no');

if ($supplier_id == 0 || $valid == 'cancel')
	redirect('supplier-list.php');

$pdo = connect_db_or_alert();
$supplier_name = get_supplier_by_id($pdo, $supplier_id)['nom'];

if ($valid == 'yes') {
	$flag = del_supplier_by_id($pdo, $supplier_id);
	if ($flag) // ca a marche
		redirect('supplier-list.php');
	$message_alert = 'Erreur dans la suppression du fournisseur : '.$supplier_name.' (#'.$supplier_id.')';
	include_once('include/alert-data.php');
	exit();
}

// $supplier_id
// $supplier_name
include_once('include/supplier-del.php');
exit();
?>
