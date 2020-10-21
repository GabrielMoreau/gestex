<?php
// user-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('team-del.php');
level_or_alert(3, 'Changer l\'&eacute;tat d\'un utilisateur');

$user_id     = param_post_or_get('id');
$valid       = param_post('ok', 'no');
$user_status = param_post_or_get('status');

if (empty($user_id) || $valid == 'cancel')
	redirect('user-list.php');

$pdo = connect_db_or_alert();
$user_selected = get_user_by_id($pdo, $user_id);
$user_fullname = $user_selected['prenom'].' '.$user_selected['nom'];

if ($valid == 'yes') {
	// on change le status de cet user
	if ($user_status == 0 || $user_status == 1) {
		$iostat = set_user_status_by_id($pdo, $user_id, (($user_status + 1) % 2));
		if ($iostat) // ca a marche
			redirect('user-list.php?highlight='.$user_id.'#item'.$user_id);
		$message_alert = 'Erreur dans le changement de status de l\'utilisateur : '.$user_fullname.' (#'.$user_id.')';
		include_once('include/alert-data.php');
		exit();
	}

	//on retourne a la page precedente
	redirect('user-list.php?highlight='.$user_id.'#item'.$user_id);
}

// $user_id
// $user_status
// $user_fullname
include_once('include/user-del.php');
exit();
?>
