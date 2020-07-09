<?php
	// Authenticate
	require("session_auth.php");

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
			$truc ="pret";
		}

	//check that this form has been submitted
	if (isset($username) && isset($password)) {
		//log the user in normally
		if (!($result=auth(0, $username, $password))){
			$login_failure = true;
		}else{
			if ($truc == "instru")
				Header("Location: list_categorie.php");
			if ($truc == "projet")
				Header("Location: list_manip.php?tri=date");
			if ($truc == "pret")
				Header("Location: user_pret.php");
		}
	}else {
		//load the session so we can destroy it
		session_start();

		//unset all the variables
		session_unset();

		//destroy the session
		session_destroy();
		$login_failure = true;
	}
	if ($truc == "projet")
		require("html_functions.php");
	if ($truc == "instru")
		require("html_functions.php");
	if ($truc == "pret")
		require("html_functions.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
		<head>
			<?php
 				if ($truc == "projet")
					echo "<title>Gestion des Projets</title>";
				if ($truc == "instru")
					echo "<title>Gestion du parc d'instrumentation</title>";
				if ($truc == "pret")
					echo "<title>Gestion des pr&ecirc;ts du mat&eacute;riel instrumentation</title>";
			?>

			<link href="pool_project.css" rel="stylesheet" type="text/css">
		</head>
		<body align="center" valign="center" onLoad="document.loginForm.username.focus();">
			<?php
				echo $login_failure ."<br />";
			?>
			<div width="100%" height="100%" align="center" valign="center">
				<table cellpadding="2" cellspacing="0" border="0"style="text-align: left; width: 75%;" align="center">
  					<tbody>
    					<tr bgcolor="#f7d709">
      						<td style="vertical-align: top;"><br />
								<img src="images/<?php GESTEX_LOGO_ENTITY ?>" width="150"  border="0" hspace="0" vspace="0">
							</td>
							<?php
								if ($truc == "instru")
									echo  "<td style=\"vertical-align: top;\"><h1>Gestion du Parc Instrumentation</h1>";
								if ($truc == "pret")
									echo  "<td style=\"vertical-align: top;\"><h1>Gestion des pr&ecirc;ts du mat&eacute;riel instrumentation</h1>";
								if ($truc == "projet"){
									echo  "<td style=\"vertical-align: top;\"><h1>Gestion des Projet</h1>";
									echo	"<br />Pour acc&eacute;der &agrave; l'outil de gestion des projets, il faut y etre r&eacute;f&eacute;renc&eacute;.<br />
											Si ce n'est pas deja fait, vous pouvez en faire la demande <a href=\"add_user.php\">ici </a>";
								}
							?>
							</td>
         					<td style="vertical-align: top;"><br />
								<?php
									if ($truc =="projet")
										echo "<img src=\"images/pool_project.jpg\" nosave=\"\" height=\"100\">";
								?>
							</td>
						</tr>
 					</tbody>
				</table>

				<form action="login.php?variable=<?php echo $truc ?>" method="POST" name="loginForm">
					<table width="100%" height="200" cellspacing="0" cellpadding="1" valign="center">
						<tr>
							<td>
								<table width="300" cellspacing="0" cellpadding="5" bgcolor="#1F32F0" align="center" class="box">
									<tr>
										<td><img src="images/<?php GESTEX_LOGO_ENTITY ?>" width="69" height="69" border="0" hspace="0" vspace="0"></td>
										<td><span class="box_text">Identifiant</span><br /><input type="text" name="username" size="25" maxlength="25"></td>
										<td><span class="box_text">Mot de passe</span><br /><input type="password" name="password" size="25" maxlength="25"></td>
										<td><br /><input type="submit" name="Login" value="Entrer"> </td>
									</tr>
									<?php if (isset($login_failure)) { ?>
									<tr>
										<td>&nbsp;</td>
										<td colspan="3">
											<table width="100%" border="0" bgcolor="black" cellspacing="0" cellpadding="1">
												<tr>
													<td>
														<table width="100%" border="0" bgcolor="yellow">
															<tr><td class="login_error"> login ou mot de passe invalide!
																</td></tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<?php } ?>
								</table>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<?php pied_page() ?>
		</body>
	</html>
