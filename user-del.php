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

if ($valid == 'yes') {
	// on change le status de cet user
	if ($user_status == 0 || $user_status == 1) {
		$sql = 'UPDATE users SET valid = ? WHERE id = ?;';
		// 1 -> 0 and 0 -> 1 (modulo)
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array((($user_status + 1) % 2), $user_id));
	}

	//on retourne a la page precedente
	redirect('user-list.php?highlight='.$user_id.'#item'.$user_id);
}

$user_selected = get_user_by_id($pdo, $user_id);
$user_fullname = $user_selected['prenom'].' '.$user_selected['nom'];

// $user_id
// $user_status
// $user_fullname
include_once('include/user-del.php');
exit();
?>
