<?php
// team-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('team-del.php');
level_or_alert(3, 'Suppression d\'une &eacute;quipe');

$team_id = param_post_or_get('id', 0);
$valid   = param_post('ok', 'no');

if ($team_id == 0 || $valid == 'cancel')
	redirect('team-list.php');

$pdo = connect_db_or_alert();
$team_name = get_team_by_id($pdo, $team_id)['nom'];

if ($valid == 'yes') {
	$iostat = del_category_by_id($pdo, $team_id);
	if ($iostat) // ca a marche
		redirect('team-list.php');
	$message_alert = 'Erreur dans la suppression de l\'&eacute;quipe : '.$team_name.' (#'.$team_id.')';
	include_once('include/alert-data.php');
	exit();
}

// $team_id
// $team_name
include_once('include/team-del.php');
exit();
?>
