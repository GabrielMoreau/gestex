<?php
// db-init.php
$web_page = true;

// Module
require_once('module/db-functions.php');
require_once('module/html-functions.php');

en_tete('Initialisation de la base de donn&eacute;e');

if ($pdo = connect_db()) {
	$team_count = get_team_count($pdo);
	$user_count = get_user_count($pdo);

	if ($team_count == 0 && $user_count == 0) {
		$err_msg = '';
		list($id_team, $err_msg) = set_team_new($pdo, 'srv-system', 'service systeme', '0', '1');
		if ($err_msg != '')
			echo '<br/>Erreur cr&eacute;ation &eacute;quipe : '.$err_msg;

		$new_pwhash = md5('chief!');
		$err_msg = '';
		list($id_user, $err_msg) = set_user_new($pdo, 'Admin', 'Sys', 'sysadmin', $new_pwhash, 'sysadmin@example.com', 4, '', $id_team, '');
		if ($err_msg != '')
			echo '<br/>Erreur cr&eacute;ation utilisateur : '.$err_msg;
		else
			echo '<br>Avis : la base de donn&eacute;e est maintenant initialis&eacute;e';
	} else {
		echo '<br>Avis : la base de donn&eacute;e est d&eacute;j&agrave; initialis&eacute;e';
	}
}
?>

<?php pied_page() ?>
