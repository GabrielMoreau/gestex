<?php
require_once('module/db-functions.php');

if ($pdo = connect_db()) {
	$team_count = get_team_count($pdo);
	$user_count = get_user_count($pdo);

	if ($team_count == 0 && $user_count == 0) {
	//inscription
	$err_msg = '';
	list($id_team, $err_msg) = set_team_new($pdo, 'srv-system', 'service systeme', '0', '1');
	if ($err_msg != '')
		echo '<br/>Erreur &eacute;quipe : '.$err_msg;

	$mot_crypte = md5('chief!');
	$err_msg = '';
	list($id_user, $err_msg) = set_user_new($pdo, 'Admin', 'Sys', 'sysadmin', $mot_crypte, 'sysadmin@example.com', 4, '', $id_team, '');
	if ($err_msg != '')
		echo '<br/>Erreur utilisateur : '.$err_msg;
	} else
		echo '<br>Info : la base de donn&eacute;e est d&eacute;j&agrave; initialis&eacute;e';
	}

?>
