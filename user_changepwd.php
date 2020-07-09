<?php
// user_changepwd.php
// Authenticate
include("session_auth.php");
if (!auth(1))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

	$user2chg = $_GET[id];

if (empty($user2chg ))
	Header("Location: list_manip.php");

if ( $connex = connect_db() ){

unset($passwd1);unset($passwd2);

if (!empty($_POST[user]))
	$user2chg = $_POST[user];

if (!empty($_POST[passwd1]))
	$passwd1 = $_POST[passwd1];

if (!empty($_POST[passwd2]))
	$passwd2 = $_POST[passwd2];

if (!empty($_POST[old_pass]))
	$old_pass = $_POST[old_pass];

unset($errormsg );

	//recupere l'ancien pasword et  le nom
		$querry = "SELECT nom,password FROM users WHERE id='$user2chg'";
		list($qh,$num) = query_db($querry);
		$data = result_db($qh);

if (isset($passwd1) && isset($passwd2)){
	//check that passwords match
	if ($passwd1 != $passwd2)
 		 $errormsg = "Passwords do not match, please try again";

	if (!isset($errormsg) && isset($old_pass) && $user_level<3 )
	{
			if(md5($old_pass) != $data['password'] )
		 				 $errormsg = "Wrong password, sorry!";
	}

	if (!isset($errormsg)){

		$mot_crypte=md5($passwd1);
		//ok on change

		$querry ="UPDATE users SET password='$mot_crypte' WHERE id = '$user2chg';";
		query_db($querry);
		Header("Location: list_users.php");
		exit;
	}
}
}//end if isset
//if errors, redirect to an error page.
if ($errormsg)
{
  Header("Location: error.php?errormsg=$errormsg");
  exit;
}

require("html_functions.php");
$titre = "Changement de mot de Passe";
en_tete($titre);
echo  $titre."pour <i>".$data['nom']."</i>";
?>
	<p>
	<form action="user_changepwd.php" method="post">
		<input type="hidden" name="user" value="<?php echo $user2chg; ?>">
	<table width="350" align="center" border="0" cellpadding="0" cellspacing="0" class="outer_table">
		<tr>
			<td>
				<table width="100%" border="0" class="table_head">
					<tr>
						<td align="left" nowrap class="outer_table_heading" nowrap>
							Changement de  Password:
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellpadding="1" cellspacing="2" class="table_body">
				<?php if ($user_level<3)	{	?>
					<tr>
						<td width="150" align="right" nowrap>ancien Password:</td>
							<td><input type="password" name="old_pass" style="width: 100%;"></td>
					</tr>
				<?php }	?>
					<tr>
						<td width="150" align="right" nowrap>Nouveau Password:</td>
						<td><input type="password" name="passwd1" style="width: 100%;"></td>
					</tr>
					<tr>
						<td width="150" align="right" nowrap>Nouveau Password (again):</td>
						<td><input type="password" name="passwd2" style="width: 100%;"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" class="table_bottom_panel">
					<tr>
						<td align="center">
							<input type="submit" value="Change!">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</form>

</div>

<?php pied_page() ?>
