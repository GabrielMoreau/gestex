<?php
// team-add.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('team-list.php');
level_or_alert(3, 'Modification d\'une &eacute;quipe');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

if (empty( $_GET['id'])) {
	//->nouvelle inscription
	$mode   = 'Ajouter';
	$action = 'team-create.php';
}
else {
	//->modif coordonnees
	$equip_id = $_GET['id'];
	$mode     = 'Modifier';
	$action   = 'team-update.php';
}

if ($pdo = connect_db()) {

$team_chief_id = 0;

if ($mode == 'Ajouter'){
	en_tete('Ajouter une &eacute;quipe');
}
else if ($mode == 'Modifier') {
	en_tete('Modifier les coordonn&eacute;es d\'une &eacute;quipe');

	// recupere le fournisseur selectionne
	$team = get_team_all_by_id($pdo, $equip_id);
	$team_chief_id = $team['chef'];
}
?>

<div class="form">
<table>
	<tbody>
		<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_equip" value="<?php if( $mode=='Modifier'){ echo $equip_id; }?>" >
		<tr>
			<th>
				Nom *
			</th>
			<td>
				<input type="text" name="nom" size="25" maxlength="30" placeholder="Nom *" value="<?php if ($mode == 'Modifier'){ echo $team['nom']; } ?>" >
			</td>
		</tr>
		<tr>
			<th>
				Description
			</th>
			<td>
				<input type="text" name="descr" size="25" maxlength="255" placeholder="Description" value="<?php if ($mode == 'Modifier'){ echo $team['descr']; } ?>" >
			</td>
		</tr>
		<tr>
			<th>
				Compte *
			</th>
			<td>
				<input type="text" name="compte" size="5" maxlength="5" placeholder="Compte *" value="<?php if ($mode == 'Modifier'){ echo $team['compte']; } ?>" >
			</td>
		</tr>
		<tr>
			<th>
				Chef d'&eacute;quipe<br />
			</th>
			<td>
			<?php // if( $mode=='Modifier'){ echo $team['chef']; } ?>
				<select name="chef">
				<?php
				// recupere laliste des chercheurs
				// $sql = 'SELECT id, nom, prenom FROM users WHERE level >= 1 and valid = 1;';
				// $stmt = $pdo->prepare($sql);
				// $stmt->execute();
				// $user_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$user_fetch = get_user_listshort_with_right($pdo, 1, $team_chief_id);
				// list($qheq,$numeq) = query_db($querry);
				// 	while ($chef = result_db($qheq)){
				foreach ($user_fetch as $chef) {
					echo '<option value="'.$chef['id'].'"';
					if ($mode == 'Modifier' && $chef['id'] == $team_chief_id) {
						echo ' selected';
					}
					echo '>'.$chef['nom'].' '.$chef['prenom'].'</option>';
				} //end foreach
				?>
				</select>
				<span class="option-right"><a href="user-add.php"><?php echo ICON_ADD_USER ?></a></span>
			</td>
		</tr>
		<tr>
			<td>Les champs avec * sont &agrave;
				remplir obligatoirement, les autres sont optionnels.
			</td>
			<td class="button">
				<input type="submit" name="Login" value="<?php echo $mode ?>">
			</td>
		</tr>
		</form>
	</tbody>
	<tbody>
		<form action="team-list.php" method="POST" name="annulForm">
		<tr>
			<td colspan="2" class="button">
				<input type="submit" name="annul" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>
</div>

<?php } else { Header("Location: list_manip.php"); exit(); } ?>

<?php pied_page() ?>
