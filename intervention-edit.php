<?php
// intervention-edit.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('intervention-edit.php.php');
level_or_alert(3, 'Ajout d\'une intervention');

$equipment_id = param_post_or_get('equipment_id', 0);
$intervention_id = param_post_or_get('id', 0);

$mode = 'Ajouter';
if ($intervention_id == 0) // new
	$mode = 'Ajouter';

$pdo = connect_db_or_alert();

$equipment_selected = [];
$intervention_fetch = array();
$recipe_count = 0;
$recipe_path = get_recipe_basepath();

if ($mode == 'Ajouter') {
	en_tete('Ajouter une intervention');
} else if ($mode == 'Modifier') {
	en_tete('Modifier une intervention');
	$intervention_fetch = get_intervention_listall_by_equipment($pdo, $equipment_id);
	$recipe_fetch       = get_recipe_listall_by_intervention($pdo, $intervention_id);
	$recipe_count       = count($recipe_fetch);
}
?>



<div class="form">
<form action="intervention-process.php" method="POST" name="inscrForm" enctype="multipart/form-data">
	<input type="hidden" name="equipment_id" value="<?php if ($mode == "Ajouter") {echo $equipment_id;} ?>" >
<table>
	<tbody>
		<tr>
			<th>Equipement</th>
			<td><b><?php echo get_equipment_all_by_id($pdo, $equipment_id)["nom"] ?></b></td>
		</tr>
		<tr>
			<th>Description</th>
			<td>
				<textarea name="description" rows="4" cols="33">D</textarea>
			</td>
		</tr>
		<tr>
			<th>Société</th>
			<?php // recupere la liste des fournisseurs
			$company_fetch = get_supplier_listshort($pdo);
			?>
			<td>
				<select name="supplier_id">
					<?php foreach ($company_fetch as $company_current): ?>
					<option value="<?= $company_current['id'] ?>"
						<?= $company_current['nom'] ?>
					</option>
					<?php endforeach; ?>
				</select>
				<span class="option-right"><a href="team-edit.php"><?php echo ICON_ADD_TEAM ?></a></span>
			</td>
		</tr>
		<tr>
			<th>
				Recipe (facultatif) &nbsp; <?php echo ICON_SEE_DOC ?>
			</th>
			<td>
				<?php if ($mode == 'Modifier' && $datasheet_count > 0): ?>
				<ul>
					<?php foreach ($datasheet_fetch as $datasheet_current): ?>
					<li>
						<a href="<?= $datasheet_path . '/' . $datasheet_current['pathname'] ?>" target="_top">
							<?= $datasheet_current['description'] ?> (<?= $datasheet_current['pathname'] ?>)
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
				<input type="file" name="recipe" value="<?= param_post_key('recipe', $equipment_selected) ?>" placeholder="Fiche (facultatif)">
			</td>
		</tr>
		<tr>
			<th>Date</th>
			<td>
				<input type="date" name="date" size="10" maxlength="10" value="<?= param_post_key('date', $loan_selected, date('Y-m-d', time())) ?>" >
			</td>
		</tr>
		<tr>
			<td></td>
			<th class="button">
				<input type="submit" name="Login" value="<?php echo $mode ?>">
			</th>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td colspan="2" class="button">
				<?php if ($mode == 'Modifier') { ?>
				<input class="cancel" type="submit" name="ok" formaction="equipment-view.php?id=<?php echo $equipment_id ?>" value="Annuler">
				<?php } else { ?>
				<input class="cancel" type="submit" name="ok" formaction="equipment-list.php" value="Annuler">
				<?php } ?>
			</td>
		</tr>
		</form>
	</tbody>
</table>
</form>
</div>
