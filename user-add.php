<?php
// user-add.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('user-list.php');
level_or_alert(3, 'Modification d\'un utilisateur');

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

if (empty($_GET['id'])) {
	//->nouvelle inscription
	$mode       = 'Ajouter';
	$action     = 'user-create.php';
	$user2ch_id = '';
} else {
	//->modif coordonnees
	$mode       = 'Modifier';
	$action     = 'user-update.php';
	$user2ch_id = $_GET['id'];
}

if ($pdo = connect_db()) {
	if ($mode == 'Ajouter') {
		en_tete('Inscrire un utilisateur');
	} else if ($mode == 'Modifier') {
		en_tete('Modifier mon profil');
		// recupere le user concerne
		$data = get_user_all_by_id($pdo, $user2ch_id);
	}
?>

<div class="form">
<form action="<?php echo $action ?>" method="POST"  class="form" name="inscrForm">
	<input type="hidden" name="user2ch_id"  value="<?php echo $user2ch_id ?>">
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
				<b><?php echo $data['loggin'] ?></b>
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
				<input type="text" name="nom" size="30" maxlength="30" value="<?php if ($mode == 'Modifier') echo $data['nom'] ?>" placeholder="Nom de famille *">
			</td>
		</tr>
		<tr>
			<th>
				Pr&eacute;nom
			</th>
			<td>
				<input type="text" name="prenom" size="30" maxlength="30" value="<?php if ($mode == 'Modifier') echo $data['prenom'] ?>" placeholder="Pr&eacute;nom">
			</td>
		</tr>
		<tr>
			<th>
				Adresse courriel *
			</th>
			<td>
				<input type="text" name="addr_mail" size="30" maxlength="50" value="<?php if ($mode == 'Modifier') echo $data['email'] ?>" placeholder="Adresse courriel *">
			</td>
		</tr>
		<tr>
			<th>
				T&eacute;l&eacute;phone
			</th>
			<td>
				<input type="text" name="phone" size="10" maxlength="10" value="<?php if ($mode=='Modifier') echo $data['tel'] ?>" placeholder="T&eacute;l&eacute;phone">
			</td>
		</tr>
		<tr>
			<th>
				&Eacute;quipe
			</th>
			<?php // recupere la liste des equipes
			$equipe_fetch = get_team_listshort($pdo);
			?>
			<td>
				<select  name="equipe">
					<?php
					foreach ($equipe_fetch as $equipe) {
						echo '<option value="'.$equipe['id'].'"';
						// selectionne la bonne equipe
						if($mode == 'Modifier'){
							if ($equipe['id'] == $data['equipe'])
								echo ' selected';
						}
						echo '>'.$equipe['nom'].'</option>';
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
				<input type="radio" name="level" value="0" <?php if ($mode === 'Modifier' && $data['level'] == 0) echo 'checked="checked"' ?> >&Eacute;tudiant<br>
				<input type="radio" name="level" value="1" <?php if ($mode === 'Modifier' && $data['level'] == 1) echo 'checked="checked"' ?> >Chercheur<br>
				<input type="radio" name="level" value="2" <?php if ($mode === 'Modifier' && $data['level'] == 2) echo 'checked="checked"' ?> >ITA<br>
				<?php } ?>
				
				<?php if (isset($logged_level) && $logged_level >= 3) { ?>
				<input type="radio" name="level" value="3" <?php if ($mode === 'Modifier' && $data['level'] >= 3) echo 'checked="checked"' ?> >Admin<br>
				<?php } ?>

				<?php if (isset($logged_level) && $logged_level >= 4) { ?>
				<input type="radio" name="level" value="4" <?php if ($mode === 'Modifier' && $data['level'] >= 3) echo 'checked="checked"' ?> >SuperAdmin<br>
				<?php } ?>
				
				<?php if (isset($logged_level) && ($logged_level < 3)) { // consultation seulement
					switch ($data['level']) {
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
				<input type="radio" name="theme" value="random" <?php if ($mode === 'Modifier' && $data['theme'] == 'random') echo 'checked' ?> >Al&eacute;atoire<br>
				<input type="radio" name="theme" value="clair" <?php if ($mode === 'Modifier' && $data['theme'] == 'clair') echo 'checked' ?> >Clair<br>
				<input type="radio" name="theme" value="sombre" <?php if ($mode === 'Modifier' && $data['theme'] == 'sombre') echo 'checked' ?>>Sombre<br>
				<input type="radio" name="theme" value="solarizeddark" <?php if ($mode === 'Modifier' && $data['theme'] == 'solarizeddark') echo 'checked' ?>>Solarized-Dark<br>
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
</table>
</form>	
</div>

<?php } else { redirect('user-list.php'); } ?>

<?php pied_page() ?>
