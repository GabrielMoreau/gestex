<?php
// user-edit.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('user-list.php');
level_or_alert(3, 'Modification d\'un utilisateur');

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

$user_id = param_post_or_get('id', 0);
$mode = 'Modifier';
if ($user_id == 0) // new
	$mode = 'Ajouter';

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
<form action="user-process.php" method="POST"  class="form" name="inscrForm">
	<input type="hidden" name="id"  value="<?php echo $user_id ?>">
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
				<?php if ($logged_level > 3) { echo '<input type="text" name="loggin" size="25" maxlength="25" value="'.param_post_key('loggin', $user_selected).'" placeholder="Identifiant (login) *">'; } else {
				echo '<b>'.param_post_key('loggin', $user_selected).'</b>'; } ?>
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
				<input type="text" name="familyname" size="30" maxlength="30" value="<?= param_post_key('familyname', $user_selected) ?>" placeholder="Nom de famille *">
			</td>
		</tr>
		<tr>
			<th>
				Pr&eacute;nom
			</th>
			<td>
				<input type="text" name="firstname" size="30" maxlength="30" value="<?= param_post_key('firstname', $user_selected) ?>" placeholder="Pr&eacute;nom">
			</td>
		</tr>
		<tr>
			<th>
				Adresse courriel <?=ICON_MAIL?> *
			</th>
			<td>
				<input type="email" name="email" size="50" maxlength="50" value="<?= param_post_key('email', $user_selected) ?>" placeholder="Adresse courriel *">
			</td>
		</tr>
		<tr>
			<th>
				T&eacute;l&eacute;phone <?=ICON_PHONE?>
			</th>
			<td>
				<input type="tel" name="phone" size="15" maxlength="15" value="<?= param_post_key('phone', $user_selected) ?>" placeholder="T&eacute;l&eacute;phone">
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
				<select name="team_id">
					<?php
					foreach ($team_fetch as $team_current) {
						echo '<option value="'.$team_current['id'].'"';
						// selectionne la bonne equipe
						if ($team_current['id'] == param_post_key('team_id', $user_selected, 0))
							echo ' selected';
						echo '>'.$team_current['name'].'</option>';
					} // end foreach
					?>
				</select>
				<span class="option-right"><a href="team-edit.php"><?php echo ICON_ADD_TEAM ?></a></span>
			</td>
		</tr>
		<tr>
			<th>
				Qualit&eacute; *
			</th>
			<td>
				<?php  if ($logged_level >= 3 || !isset($logged_level)) { // admin loggue ou premiere inscription: modif possible ?>
				<input type="radio" name="level" value="0" <?php if (param_post_key('level', $user_selected, 0) == 0) echo 'checked="checked"' ?> >&Eacute;tudiant<br>
				<input type="radio" name="level" value="1" <?php if (param_post_key('level', $user_selected, 0) == 1) echo 'checked="checked"' ?> >Chercheur<br>
				<input type="radio" name="level" value="2" <?php if (param_post_key('level', $user_selected, 0) == 2) echo 'checked="checked"' ?> >ITA<br>
				<?php } ?>

				<?php if (isset($logged_level) && $logged_level >= 3) { ?>
				<input type="radio" name="level" value="3" <?php if (param_post_key('level', $user_selected, 0) == 3) echo 'checked="checked"' ?> >Admin<br>
				<?php } ?>

				<?php if (isset($logged_level) && $logged_level >= 4) { ?>
				<input type="radio" name="level" value="4" <?php if (param_post_key('level', $user_selected, 0) == 4) echo 'checked="checked"' ?> >SuperAdmin<br>
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
				<input type="radio" name="theme" value="random" <?php if (param_post_key('theme', $user_selected) == 'random') echo 'checked' ?> >Al&eacute;atoire<br>
				<input type="radio" name="theme" value="clair" <?php if (param_post_key('theme', $user_selected) == 'clair') echo 'checked' ?> >Clair<br>
				<input type="radio" name="theme" value="sombre" <?php if (param_post_key('theme', $user_selected) == 'sombre') echo 'checked' ?>>Sombre<br>
				<input type="radio" name="theme" value="solarizeddark" <?php if (param_post_key('theme', $user_selected) == 'solarizeddark') echo 'checked' ?>>Solarized-Dark<br>
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
