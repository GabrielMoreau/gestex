<?php
// user-edit.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('user-list.php');
level_or_alert(3, 'Modification d\'un utilisateur');

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

$user_id = param_post_or_get('id', 0);
if ($user_id == 0) {
	//->nouvelle inscription
	$mode       = 'Ajouter';
	$action     = 'user-create.php';
} else {
	//->modif coordonnees
	$mode       = 'Modifier';
	$action     = 'user-update.php';
}

$pdo = connect_db_or_alert();

$user_selected = [];
if ($mode == 'Ajouter') {
	en_tete('Inscrire un utilisateur');
} else if ($mode == 'Modifier') {
	en_tete('Modifier mon profil');
	// recupere le user concerne
	$user_selected = get_user_all_by_id($pdo, $user_id);
}
?>

<div class="form">
<form action="<?php echo $action ?>" method="POST"  class="form" name="inscrForm">
	<input type="hidden" name="user2ch_id"  value="<?php echo $user_id ?>">
<table>
	<tbody>
		<tr>
			<?php if ($mode == 'Ajouter') { ?>
			<th>
				Identifiant (login) *
			</th>
			<td>
				<input type="text" name="loggin" size="25" maxlength="25" value="" placeholder="Identifiant (login) *">
			</td>
			<?php } else { ?>
			<th>
				Identifiant (login)
			</th>
			<td>
				<b><?php echo $user_selected['loggin'] ?></b>
			</td>
			<?php } ?>
		</tr>

		<?php if ($mode == 'Ajouter') { ?>
		<tr>
			<th>
				Mot de passe *
			</th>
			<td>
				<input type="password" name="password" size="25" maxlength="25" value="" placeholder="Mot de passe *">
			</td>
		</tr>
		<tr>
			<th>
				Mot de passe (confirmer) *
			</th>
			<td>
				<input type="password" name="password2" size="25" maxlength="25" value="" placeholder="Mot de passe (confirmer) *">
			</td>
		</tr>
		<?php } //end if mode ?>

		<tr>
			<th>
				Nom de famille *
			</th>
			<td>
				<input type="text" name="nom" size="30" maxlength="30" value="<?php if ($mode == 'Modifier') echo $user_selected['nom'] ?>" placeholder="Nom de famille *">
			</td>
		</tr>
		<tr>
			<th>
				Pr&eacute;nom
			</th>
			<td>
				<input type="text" name="prenom" size="30" maxlength="30" value="<?php if ($mode == 'Modifier') echo $user_selected['prenom'] ?>" placeholder="Pr&eacute;nom">
			</td>
		</tr>
		<tr>
			<th>
				Adresse courriel *
			</th>
			<td>
				<input type="text" name="addr_mail" size="30" maxlength="50" value="<?php if ($mode == 'Modifier') echo $user_selected['email'] ?>" placeholder="Adresse courriel *">
			</td>
		</tr>
		<tr>
			<th>
				T&eacute;l&eacute;phone
			</th>
			<td>
				<input type="text" name="phone" size="10" maxlength="10" value="<?php if ($mode=='Modifier') echo $user_selected['tel'] ?>" placeholder="T&eacute;l&eacute;phone">
			</td>
		</tr>
		<tr>
			<th>
				&Eacute;quipe
			</th>
			<?php // recupere la liste des equipes
			$team_fetch = get_team_listshort($pdo);
			?>
			<td>
				<select  name="equipe">
					<?php
					foreach ($team_fetch as $team_current) {
						echo '<option value="'.$team_current['id'].'"';
						// selectionne la bonne equipe
						if($mode == 'Modifier'){
							if ($team_current['id'] == $user_selected['equipe'])
								echo ' selected';
						}
						echo '>'.$team_current['nom'].'</option>';
					} // end foreach ?>
				</select>
				<span class="option-right"><a href="team-edit.php"><?php echo ICON_ADD_TEAM ?></a></span>
			</td>
		</tr>
		<tr>
			<th>
				Qualit&eacute;
			</th>
			<td>
				<?php  if ($logged_level >= 3 || !isset($logged_level)) { // admin loggue ou premiere inscription: modif possible ?>
				<input type="radio" name="level" value="0" <?php if ($mode === 'Modifier' && $user_selected['level'] == 0) echo 'checked="checked"' ?> >&Eacute;tudiant<br>
				<input type="radio" name="level" value="1" <?php if ($mode === 'Modifier' && $user_selected['level'] == 1) echo 'checked="checked"' ?> >Chercheur<br>
				<input type="radio" name="level" value="2" <?php if ($mode === 'Modifier' && $user_selected['level'] == 2) echo 'checked="checked"' ?> >ITA<br>
				<?php } ?>
				
				<?php if (isset($logged_level) && $logged_level >= 3) { ?>
				<input type="radio" name="level" value="3" <?php if ($mode === 'Modifier' && $user_selected['level'] == 3) echo 'checked="checked"' ?> >Admin<br>
				<?php } ?>

				<?php if (isset($logged_level) && $logged_level >= 4) { ?>
				<input type="radio" name="level" value="4" <?php if ($mode === 'Modifier' && $user_selected['level'] == 4) echo 'checked="checked"' ?> >SuperAdmin<br>
				<?php } ?>
				
				<?php if (isset($logged_level) && ($logged_level < 3)) { // consultation seulement
					switch ($user_selected['level']) {
						case 0: echo "&Eacute;tudiant"; break;
						case 1: echo "Chercheur"; break;
						case 2: echo "ITA"; break;
						case 3: echo "Admin";
					}
				}
				?>
			</td>
		</tr>
		<tr>
			<th>
				Th&egrave;me
			</th>
			<td>
				<input type="radio" name="theme" value="random" <?php if ($mode === 'Modifier' && $user_selected['theme'] == 'random') echo 'checked' ?> >Al&eacute;atoire<br>
				<input type="radio" name="theme" value="clair" <?php if ($mode === 'Modifier' && $user_selected['theme'] == 'clair') echo 'checked' ?> >Clair<br>
				<input type="radio" name="theme" value="sombre" <?php if ($mode === 'Modifier' && $user_selected['theme'] == 'sombre') echo 'checked' ?>>Sombre<br>
				<input type="radio" name="theme" value="solarizeddark" <?php if ($mode === 'Modifier' && $user_selected['theme'] == 'solarizeddark') echo 'checked' ?>>Solarized-Dark<br>
			</td>
		</tr>
		<tr>
			<td>Les champs avec * sont &agrave;
				remplir obligatoirement, les autres sont optionnels.
			</td>
			<td class="button">
				<input type="submit" name="Login" value="<?php echo $mode ?>" >
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr >
			<td colspan="2" class="button">
				<input class="cancel" type="submit" name="ok" formaction="user-list.php<?php if ($mode == 'Modifier'){ echo '?highlight='.$user_id.'#item'.$user_id; } ?>" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>
</form>	
</div>

<?php pied_page() ?>
