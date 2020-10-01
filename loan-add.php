<?php
// loan-add.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

//if (!auth(3))
	//Header("Location: login.php");
session_start();
if (empty($_SESSION['logged_user'])) {
	$log = false;
	$logged_level = 0;
} else {
	$logged_id   = $_SESSION['logged_id'];
	$logged_user = strtolower($_SESSION['logged_user']);
}

$id_equipment = param_get('equipment'); // -> new
$id_loan      = param_get('id'); // -> modify

if (empty($id_loan)) {
	$mode = 'Ajouter';
	en_tete('Ajouter un pr&ecirc;t');
} else {
	$mode = 'Modifier';
	en_tete('Modifier le pr&ecirc;t d\'un appareil');
}

// transmet la valeur de la categorie a la page valid appareil

if ($pdo = connect_db()) {

	if ($mode == 'Modifier') {
		$loan = get_loan_all_by_id($pdo, $id_loan);
		$id_equipment = $loan['nom'];
	}

	$equipment = get_equipment_by_id($pdo, $id_equipment);
?>

<div class="form">
<form action="loan-create.php" method="POST" name="inscrForm">
	<input type="hidden" name="id_equipment" value="<?php echo $id_equipment ?>" >
	<?php if ($mode == 'Modifier') { ?>
		<input type="hidden" name="id_loan" value="<?php echo $id_loan ?>" >
	<?php } ?>
<table>
	<tbody>
		<tr>
			<td>Nom de l'appareil
			</td>
			<td>
				<b><?php echo $equipment['nom'] ?></b>
			</td>
		</tr>

		<tr>
			<td>&Eacute;quipe *
			</td>
			<td>
				<select name="equipe">
				<?php
				// recupere la liste des equipes
				$team_fetch = get_team_listshort($pdo);
				foreach ($team_fetch as $team) {
					echo '<option value="'.$team['id'].'"';
					if ($mode == 'Modifier' && $team['id'] == $loan['equipe']) {
						echo ' selected';
					}
					echo '>'.$team['nom'].'</option>';
				} //end foreach
				?>
				</select>
				
			</td>
		</tr>

		<tr>
			<td>Date demande pr&ecirc;t * (<i>format YYYY-MM-DD</i>)
			</td>
			<td>
				<input type="text" name="emprunt" size="10" maxlength="10" value="<?php
					if ($mode == 'Modifier')
						echo $loan['emprunt'];
					else
						echo date('Y-m-d', time() );
					?>" >
			</td>
		</tr>

		<tr>
			<td>Date de retour estim&eacute;e * (<i>format YYYY-MM-DD</i>)
			</td>
			<td>
				<input type="text" name="retour" size="10" maxlength="10" value="<?php
					if ($mode == 'Modifier')
						echo $loan['retour'];
					else
						echo date('Y-m-d', time() );
					?>" >
			</td>
		</tr>

		<tr>
			<td>Commentaire
			</td>
			<td>
				<input type="text" name="commentaire" size="30" maxlength="30" value="<?php if($mode=='Modifier'){echo $loan['commentaire'];} ?>" >
			</td>
		</tr>

		<tr>
			<td>Les champs avec * sont &agrave;
				remplir obligatoirement, les autres sont optionnels.
			</td>
			<td style="vertical-align: top;" align="right">
				<input type="submit" name="Login" value="<?php echo $mode ?>">
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td colspan="2" class="button">
				<input class="cancel" type="submit" name="ok" formaction="equipment-view.php?id=<?php echo $id_equipment ?>" value="Annuler">
			</td>
		</tr>
	</tbody>
</table>
</form>
</div>

<?php } else { echo "probl&egrave;me de connexion a la base de donn&eacute;e"; } ?>

<?php pied_page() ?>
