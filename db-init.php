<?php
// db-init.php
$web_page = true;

// Module
require_once('module/db-functions.php');
require_once('module/html-functions.php');

en_tete('Initialisation de la base de donnée');

if ($pdo = connect_db()) {
	$team_count = get_team_count($pdo);
	$user_count = get_user_count($pdo);

	if ($team_count == 0 && $user_count == 0) {
		$err_msg = '';
		list($team_id, $err_msg) = set_team_new($pdo, 'srv-system', 'service systeme', '0', '1');
		if ($err_msg != '')
			echo '<br/>Erreur création équipe : '.$err_msg;

		$new_pwhash = password_hash('chief!', PASSWORD_DEFAULT);
		$err_msg = '';
		list($user_id, $err_msg) = set_user_new($pdo, 'Admin', 'Sys', 'sysadmin', $new_pwhash, 'sysadmin@example.com', 4, '', $team_id, '');
		if ($err_msg != '')
			echo '<br/>Erreur création utilisateur : '.$err_msg;
		else
			echo '<br>Avis : la base de donnée est maintenant initialisée';
	} else {
		echo '<br>Avis : la base de donnée est déjà initialisée';
	}
}
?>

<?php pied_page() ?>
