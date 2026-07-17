<?php
// user-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('user-list.php');
level_or_alert(3, 'Changer l’état d’un utilisateur');

$user_id     = param_post_or_get('user_id', 0);
$valid       = param_post('ok', 'no');
$user_status = param_post_or_get('status', 0);

if ($user_id == 0 || $valid == 'cancel')
	redirect('user-list.php');

$pdo = connect_db_or_alert();
$user_selected = get_user_short_by_id($pdo, $user_id);
$user_fullname = $user_selected['firstname'].' '.$user_selected['familyname'];

if ($valid == 'yes') {
	// Changer le statut de cet utilisateur
	if ($user_status == 0 || $user_status == 1) {
		$iostat = set_user_valid_by_id($pdo, $user_id, (($user_status + 1) % 2));
		if ($iostat) // Ça a marché
			redirect('user-list.php?highlight='.$user_id.'#item'.$user_id);
		$message_alert = 'Erreur dans le changement du statut de l’utilisateur : '.$user_fullname.' (#'.$user_id.')';
		include_once('include/alert-data.php');
		exit();
	}

	// Retourner à la page précédente
	redirect('user-list.php?highlight='.$user_id.'#item'.$user_id);
}

// $user_id
// $user_status
// $user_fullname
include_once('include/user-del.php');
exit();
?>
