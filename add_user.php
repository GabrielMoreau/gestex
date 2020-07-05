<?php
/// inscription.php
// Authenticate
include("session_auth.php");

/// on peut se logger autrement qu'en admin!
/// pour une demande d'inscription
auth(3);

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

$user2ch_id = $_GET['id'];
if (empty($user2ch_id)) {
	//->nouvelle inscription
	$mode   = 'ajouter';
	$action = 'valid_inscr.php';
} else {
	//->modif coordonnees
	$mode   = 'modif';
	$action = 'modif_inscr.php';
}

require("html_functions.php");

if ($pdo = connect_db()) {
	if ($mode == 'ajouter'){
		en_tete("Voila un formulaire pour inscrire un utilisateur");
	} else if ($mode=="modif") {
		en_tete("Voila un formulaire pour modifier vos coordonn&eacute;es ");
		// recupere la liste des users
		// $querry = "SELECT * FROM users WHERE id='$user2ch_id'";
		// list($qh,$num) = query_db($querry);
		// $data = result_db($qh);
		$sql = 'SELECT * FROM users WHERE id = ?;'
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($user2ch_id));
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$data = $user[0];
	}

	echo 'id:'.$user_id .' : '.$logged_in_user.' lvl:'.$user_level
?>

<form action="<?php echo $action ?>" method="POST" name="inscrForm">
	<input type="hidden" name="user2ch_id"  value="<?php echo $user2ch_id ?>" >
<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">
	<tbody>
		<tr>
			<td style="vertical-align: top;">Nom de loggin *<br />
			</td>
			<td style="vertical-align: top;">
				<?php if ($mode == 'ajouter') { ?>
					<input type="text" name="loggin" size="25" maxlength="25" value="" ><br />
				<?php } else { ?>
					// on ne change pas le loggin
					<?php echo $logged_in_user ?><br />
				<?php } ?>
			</td>
		</tr>

		<?php if ($mode == 'ajouter') { ?>
		<tr>
			<td style="vertical-align: top;">Mot de passe *<br />
			</td>
			<td style="vertical-align: top;">
				<input type="password" name="password" size="25" maxlength="25" value="<?php echo $data['password'] ?>" ><br />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Mot de passe (pour confirmer) *<br />
			</td>
			<td style="vertical-align: top;">
				<input type="password" name="password2" size="25" maxlength="25" value="<?php echo $data['password'] ?>" ><br />
			</td>
		</tr>
		<?php } //end if mode ?>

		<tr>
			<td style="vertical-align: top;">Nom de famille *<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="nom" size="25" maxlength="25" value="<?php echo $data['nom'] ?>" ><br />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Pr&eacute;nom<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="prenom" size="25" maxlength="25" value="<?php echo $data['prenom'] ?>" ><br />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Adresse courriel *<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="addr_mail" size="25" maxlength="50" value="<?php echo $data['email'] ?>" ><br />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">T&eacute;l&eacute;phone<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="phone" size="10" maxlength="10" value="<?php echo $data['tel'] ?>" ><br />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">&Eacute;quipe<br />
			</td>
			<?php // recupere la liste des equipes
			// $querry = "SELECT id, nom FROM equipe";
			// list($qheq,$numeq) = query_db($querry);
			$sql = 'SELECT id, nom FROM equipe order by nom';
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$equipe_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
			?>
			<td style="vertical-align: top;">
				<select  name="equipe">
					<?php
					// while($equipes = result_db($qheq)){
					foreach ($equipe_fetch as $equipe) {
						echo '<option value="'.$equipe['id'].'"';
						/// selectionne la bonne equipe
						if ($equipe['id'] == $data['equipe'])
							echo ' selected';
						echo '>'.$equipe['nom'].'</option>';
					} //end foreach ?>
				</select>
				<br />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Qualit&eacute;<br />
			</td>
			<td style="vertical-align: top;">
				<?php  if ($user_level >= 3 || !isset($user_level)) { // admin loggue ou premiere inscription: modif possible ?>
				<input type="radio" name="level" value="0" <?php if ($data['level']==0) echo 'checked="checked"' ?> >&Eacute;tudiant<br />
				<input type="radio" name="level" value="1" <?php if ($data['level']==1) echo 'checked="checked"' ?> >Chercheur<br />
				<input type="radio" name="level" value="2" <?php if ($data['level']==2) echo 'checked="checked"' ?> >ITA<br />
				<?php } ?>
				
				<?php if (isset($user_level) && $user_level==3) { ?>
				<input type="radio" name="level" value="3" <?php if ($data['level']==3) echo 'checked="checked"' ?> >Admin<br />
				<?php } ?>
				
				<?php if ( isset($user_level) && ($user_level < 3 )){ /// consultation seulement
					switch($data['level']) {
						case 0: echo "&Eacute;tudiant<br />";break;
						case 1: echo "Chercheur<br />";break;
						case 2: echo "ITA<br />";break;
						case 3: echo "Admin<br />";
					}
				}
				?>
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Les champs avec * sont &agrave;
				remplir obligatoirement, les autres sont optionnels.<br />
			</td>
			<td style="vertical-align: top;" align="right">
				<input type="submit" name="Login" value="<?php echo $mode; ?>" >
			</td>
		</tr>
	</tbody>
</table>
</form>	

<?php } else { Header("Location: list_users.php"); exit(0); } ?>
<br />
</div>
<?php pied_page() ?>
