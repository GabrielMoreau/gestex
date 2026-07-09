<?php
// user-changepwd.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('index.php');
level_or_alert(1, 'Modification du mot de passe');

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

$errormsg = '';

if (!empty($_GET['id'] )) {
	$user2chg = $_GET['id'];
}

$pdo = connect_db_or_alert();

unset($passwd1);
unset($passwd2);

if (!empty($_POST['user']))
	$user2chg = $_POST['user'];

if (!empty($_POST['passwd1']))
	$passwd1 = $_POST['passwd1'];

if (!empty($_POST['passwd2']))
	$passwd2 = $_POST['passwd2'];

if (!empty($_POST['old_pass']))
	$old_pass = $_POST['old_pass'];

$user_selected = get_user_all_by_id($pdo, $user2chg);

if (isset($passwd1) && isset($passwd2)){
	// check that passwords match
	if ($passwd1 != $passwd2)
		$errormsg = 'Passwords do not match, please try again';

	if (!isset($errormsg) && isset($old_pass) && $logged_level < 3) {
		if(md5($old_pass) != $user_selected['password'])
			$errormsg = 'Wrong password, sorry!';
	}
	echo $errormsg;
	if ($errormsg == '') {
		$new_pwhash = md5($passwd1);
		// ok on change
		set_user_password_by_id($pdo, $user2chg, $new_pwhash);
		redirect('user-list.php');
	}
} // end if isset

en_tete('Changement de mot de passe pour <i>'.$user_selected['nom'].' '.$user_selected['prenom'].'</i>');

if (!empty($_GET['id'])) {
?>

<div class="auth">
	<form action="user-changepwd.php" method="post">
		<input type="hidden" name="user" value="<?php echo $user2chg ?>">
		<table>
			<tbody>
				<?php if ($logged_level < 3) { ?>
				<tr>
					<th>Ancien mot de passe</th>
					<td><input type="password" name="old_pass" placeholder="Ancien mot de passe"></td>
				</tr>
				<?php } ?>
				<tr>
					<th>Nouveau mot de passe</th>
					<td><input type="password" name="passwd1" placeholder="Nouveau mot de passe"></td>
				</tr>
				<tr>
					<th>Nouveau mot de passe (confirmer)</th>
					<td><input type="password" name="passwd2" placeholder="Nouveau mot de passe (confirmer)"></td>
				</tr>
				<tr>
					<td class="button" colspan=2">
						<input type="submit" value="Change!">
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>

<?php } else { echo $errormsg; } ?>

<?php pied_page() ?>
