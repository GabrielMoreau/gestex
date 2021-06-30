<?php
// loan-edit.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

// Authenticate
auth_or_login('loan-list.php');
level_or_alert(3, 'Ajout ou modification d\'un pr&ecirc;t');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

$equipment_id = param_get('equipment'); // -> new
$loan_id      = param_get('id');     // -> modify
$param_mode	  = param_get('mode', "loan");

/* if ($loan_id == 0) {
	$mode = 'Ajouter';
	en_tete('Ajouter un pr&ecirc;t');
} else if (!empty($equipment_id) && $equipment_id != 0 && $equipment_id != NULL){
	$mode = 'Reserver apres';
	en_tete('Reserver plus tard');
} else {
	$mode = 'Modifier';
	en_tete('Modifier le pr&ecirc;t d\'un appareil'); 
}*/

if ($param_mode == 'loan') {
	$mode = 'Empunter';
	en_tete('Ajouter un pr&ecirc;t');
} else if ($param_mode == 'booking'){
	$mode = 'Reserver';
	en_tete('Reserver plus tard');
} else {
	$mode = 'Modifier';
	en_tete('Modifier le pr&ecirc;t d\'un appareil'); 
}

// transmet la valeur de la categorie a la page valid appareil

$pdo = connect_db_or_alert();

$loan_selected = [];
if ($mode == 'Modifier') {
	$loan_selected = get_loan_all_by_id($pdo, $loan_id);
	$equipment_id = $loan_selected['nom'];
}
$num_line = 0;
$equipment_selected = get_equipment_by_id($pdo, $equipment_id);
$equipment_blacklist = get_loans_blacklist_by_equipment($pdo, $equipment_id);
$equipment_loans = get_loan_active_listall_by_equipment($pdo, $equipment_selected['id']);
$equipment_loan_reserved = get_last_reserved_loan($pdo, $equipment_id);

loan_list_container($pdo, $equipment_loans, $equipment_loan_reserved, $equipment_blacklist, $logged_level);?>

<div class="space-between"></div>


<div class="form" style="margin-bottom: 2rem">
<form action="loan-process.php" method="POST" name="inscrForm">
	<input type="hidden" name="id_equipment" value="<?php echo $equipment_id ?>" >
	<input type="hidden" name="mode" value="<?php echo $param_mode?>">
	<?php if ($mode == 'Modifier' || $mode == 'Reserver') { ?>
		<input type="hidden" name="id_loan" value="<?php echo $loan_id ?>" >
	<?php } ?>
<table>

		
	<tbody>

		<?php
		if ($param_mode == "edit") {?>
			<tr>
				<td style="background-color: #a6a6a8;color: black;padding-left: 7px;padding: 4px;"><b>ID <?php if (STATUS_LOAN_BORROWED == get_loan_status_by_id($pdo, $loan_id)) {echo "EMPRUNT";} else {echo "RESERVATION";}?></b></td>
				<td style="background-color: var(--color-link);color: black;text-align: center;padding: 4px;"><b><?php echo param_get('id', "UNKNOW")?></b></td>
			</tr>

			<tr>
				<td></td>
				<td></td>
			</tr><?php
		}?>

		<tr>
			<td>Nom de l'appareil
			</td>
			<td>
				<b><?php echo $equipment_selected['nom'] ?></b>
			</td>
		</tr>

		<tr>
			<td>Date demande pr&ecirc;t * (<i>format YYYY-MM-DD</i>)
			</td>
			<td>
				<input type="date" name="emprunt" size="10" maxlength="10" value="<?= param_post_key('emprunt', $loan_selected, date('Y-m-d', time())) ?>"
			</td>
		</tr>

		<tr>
			<td>Date de retour estim&eacute;e * (<i>format YYYY-MM-DD</i>)
			</td>
			<td>
				<input type="date" name="retour" size="10" maxlength="10" value="<?= param_post_key('retour', $loan_selected, date('Y-m-d', time())) ?>" >
			</td>
		</tr>

		<tr>
			<td>&Eacute;quipe redevable *
			</td>
			<td>
				<select name="equipe">
				<?php
				// recupere la liste des equipes
				$team_fetch = get_team_listshort($pdo);
				foreach ($team_fetch as $team_current) {
					echo '<option value="'.$team_current['id'].'"';
					if ($team_current['id'] == param_post_key('equipe', $loan_selected)) {
						echo ' selected';
					}
					echo '>'.$team_current['nom'].'</option>';
				} //end foreach
				?>
				</select>
				
			</td>
		</tr>

		<tr>
			<td>Commentaire (Nom de l'emprunteur)
			</td>
			<td>
				<input type="text" name="commentaire" size="30" maxlength="30" value="<?= param_post_key('commentaire', $loan_selected) ?>" >
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
				<input class="cancel" type="submit" name="ok" formaction="equipment-view.php?id=<?php echo $equipment_id ?>" value="Annuler">
			</td>
		</tr>
	</tbody>
</table>
</form>
</div>

<?php pied_page() ?>
