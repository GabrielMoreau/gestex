<?php
	// Authenticate
	require("session_auth.php");
	require("html_functions.php");

	$username = $_POST['username'];
	$password = $_POST['password'];
	//valeur par defaut
	$login_failure = false;
	if (isset( $_GET['variable']) && !empty( $_GET['variable']))
		$truc = $_GET['variable'];
	else {
		if (isset( $_POST['variable']) && !empty( $_POST['variable']))
			$truc = $_POST['variable'];
		else
			$truc = 'index';
		}

	//check that this form has been submitted
	if (isset($username) && isset($password)) {
		//log the user in normally
		if (!($result = auth(0, $username, $password))){
			$login_failure = true;
		}else{
			if ($truc == 'instru')
				Header("Location: list_categorie.php");
			if ($truc == 'projet')
				Header("Location: list_manip.php?tri=date");
			if ($truc == 'index')
				Header("Location: index.php");
		}
	} else {
		//load the session so we can destroy it
		session_start();

		//unset all the variables
		session_unset();

		//destroy the session
		session_destroy();
		$login_failure = true;
	}

en_tete('Authentification');
?>

<div width="100%" height="100%" align="center" valign="center">
	<form action="login.php?variable=<?php echo $truc ?>" method="POST" name="loginForm">
		<table width="100%" height="200" cellspacing="0" cellpadding="1" valign="center">
			<tr>
				<td>
					<table width="300" cellspacing="0" cellpadding="5" bgcolor="#1F32F0" align="center" class="box">
						<tr>
							<td><span class="box_text">Identifiant</span><br /><input type="text" name="username" size="25" maxlength="25"></td>
							<td><span class="box_text">Mot de passe</span><br /><input type="password" name="password" size="25" maxlength="25"></td>
							<td><br /><input type="submit" name="Login" value="Entrer"> </td>
						</tr>
						<?php if (isset($login_failure)) { ?>
						<tr>
							<td colspan="2">
								<table width="100%" border="0" bgcolor="black" cellspacing="0" cellpadding="1">
									<tr>
										<td>
											<table width="100%" border="0" bgcolor="yellow">
												<tr>
													<td class="login_error">
														login ou mot de passe invalide !
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
							<td>&nbsp;</td>
						</tr>
						<?php } ?>
					</table>
				</td>
			</tr>
		</table>
	</form>
</div>

<?php pied_page() ?>
