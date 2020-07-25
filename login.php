<?php
	// Authenticate
	require_once('module/auth-functions.php');
	require_once('module/html-functions.php');

	$username='';
	if(!empty($_POST['username'])){
		$username = $_POST['username'];
	}
	$password='';
	if(!empty($_POST['password'])){
		$password = $_POST['password'];
	}
	//valeur par defaut
	$login_failure = false;

	// return URL after auth (or no auth)
	$referer = urlencode($_SERVER['HTTP_REFERER']);
	if (!empty($_GET['referer']))
		$referer = $_GET['referer'];
	if (!empty($_POST['referer']))
		$referer = $_POST['referer'];
	if (empty($referer))
		$referer = urlencode('index.php');

	$first = true;
	if (!empty($_POST['first']))
		$first = false;

	// check that this form has been submitted
	if ($username!='' && $password!='') {
		// log the user in normally
		if (auth(0, $username, $password)) {
			Header('Location: '.urldecode($referer));
			exit();
		}
		else
			$login_failure = true;
	} else {
		//load the session so we can destroy it
		session_start();

		//unset all the variables
		session_unset();

		//destroy the session
		session_destroy();

		if (!$first)
			$login_failure = true;
	}

en_tete('Authentification');
?>

<div class="auth">
	<form action="login.php" method="POST" name="loginForm">
		<input type="hidden" name="referer" value="<?php echo $referer ?>">
		<input type="hidden" name="first" value="false">
		<table>
			<tbody>
				<tr>
					<th>Identifiant</th>
					<td><input type="text" name="username" size="25" maxlength="25"></td>
				</tr>
				<tr>
					<th>Mot de passe</th>
					<td><input type="password" name="password" size="25" maxlength="25"></td>
				</tr>
				<?php if ($login_failure): ?>
				<tr>
					<td class="login-error" colspan="2">
						Identifiant ou mot de passe invalide !
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td class="button" colspan="2">
						<input type="submit" name="Login" value="Entrer">
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>

<?php pied_page() ?>
