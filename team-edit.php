<?php
// team-edit.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('team-list.php');
level_or_alert(3, 'Modification d’une équipe');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$team_id = param_post_or_get('team_id', 0);
$mode = 'Modifier';
if ($team_id == 0) // new
	$mode = 'Ajouter';

$pdo = connect_db_or_alert();

$team_manager_user_id = 0;

$team_selected = [];
if ($mode == 'Ajouter')
	en_tete('Ajouter une équipe');
else if ($mode == 'Modifier') {
	en_tete('Modifier les coordonnées d’une équipe');
	// Récupère le fournisseur sélectionné
	$team_selected = get_team_all_by_id($pdo, $team_id);
	$team_manager_user_id = $team_selected['manager_user_id'];
}
?>

<div class="form">
<form action="team-process.php" method="POST" name="inscrForm">
	<input type="hidden" name="team_id" value="<?php if ($mode == 'Modifier'){ echo $team_id; }?>" >
<table>
	<tbody>
		<tr>
			<th>
				Nom *
			</th>
			<td>
				<input type="text" name="name" size="25" maxlength="30" placeholder="Nom *" value="<?= param_post_key('name', $team_selected) ?>" >
			</td>
		</tr>
		<tr>
			<th>
				Description
			</th>
			<td>
				<input type="text" name="description" size="25" maxlength="255" placeholder="Description" value="<?= param_post_key('description', $team_selected) ?>" >
			</td>
		</tr>
		<tr>
			<th>
				Compte *
			</th>
			<td>
				<input type="text" name="accounting" size="5" maxlength="5" placeholder="Compte *" value="<?= param_post_key('accounting', $team_selected) ?>" >
			</td>
		</tr>
		<tr>
			<th>
				Chef d’équipe<br />
			</th>
			<td>
				<select name="manager_user_id">
				<?php
				$user_fetch = get_user_listshort_with_right($pdo, 1, $team_manager_user_id);
				foreach ($user_fetch as $user_selected) {
					echo '<option value="'.$user_selected['id'].'"';
					if ($mode == 'Modifier' && $user_selected['id'] == $team_manager_user_id) {
						echo ' selected';
					}
					echo '>'.$user_selected['familyname'].' '.$user_selected['firstname'].'</option>';
				} //end foreach
				?>
				</select>
				<span class="option-right"><a href="user-edit.php"><?php echo ICON_ADD_USER ?></a></span>
			</td>
		</tr>
		<tr>
			<td>Les champs avec * sont à
				remplir obligatoirement, les autres sont optionnels.
			</td>
			<td class="button">
				<input type="submit" name="Login" value="<?php echo $mode ?>">
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td colspan="2" class="button">
				<input class="cancel" type="submit" name="ok" formaction="team-list.php<?php if ($mode == 'Modifier'){ echo '?highlight='.$team_id.'#item'.$team_id; } ?>" value="Annuler">
			</td>
		</tr>
	</tbody>
</table>
</form>
</div>

<?php pied_page() ?>
