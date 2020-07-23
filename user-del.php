<?php
// user-del.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('team-del.php');
level_or_alert(3, 'Changer l\'&eacute;tat d\'un utilisateur');

$logged_id        = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$id_user = $_POST['id'];
if (empty($id_user))
	$id_user = $_GET['id'];
if (empty($id_user) || $_POST['ok'] == 'cancel')
	redirect('user-list.php');

$status_user = $_POST['status'];
if (empty($status_user))
	$status_user = $_GET['status'];

$valid = 'no';
if ($_POST['ok'] == 'yes') // si ok dans l'url est 'yes', on valide la suppression
	$valid = 'yes';

if ($valid == 'yes') {
	if ($pdo = connect_db()) {
		//on supprime cet user
		// $sql = 'DELETE LOW_PRIORITY FROM users WHERE id = ? LIMIT 1';
		if ($status_user == 0 || $status_user == 1) {
			$sql = 'UPDATE users SET valid = ? WHERE id = ?;';
			// 1 -> 0 and 0 -> 1 (modulo)
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array((($status_user + 1) % 2), $id_user));
		}
	}
	//on retourne a la page precedente
	redirect('user-list.php?highlight='.$id_user.'#item'.$id_user);
}

en_tete('Changer l\'&eacute;tat d\'un utilisateur');
?>

<center class="alert">
<form action="user-del.php" method="POST">
	<input type="hidden" name="id" value="<?php echo $id_user ?>">
	<input type="hidden" name="status" value="<?php echo $status_user ?>">
	Voulez-vous changer l'&eacute;tat de l'utilisateur <?php echo $id_user ?> ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="user-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page(); ?>
