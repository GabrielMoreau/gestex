<?php
// equipment-edit.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('equipment-list.php');
level_or_alert(3, 'Modification d\'un appareil');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$category_id  = param_post_or_get('categorie');
$equipment_id = param_post_or_get('id', 0);
$mode = 'Modifier';
if ($equipment_id == 0) // new
	$mode   = 'Ajouter';

$pdo = connect_db_or_alert();

$equipment_selected = [];
$datasheet_path     = get_datasheet_basepath();
$datasheet_count    = 0;
if ($mode == 'Ajouter') {
	en_tete('Ajouter un appareil');
}
else if ($mode == 'Modifier') {
	en_tete('Modifier un appareil');
	// recupere l'appareil selectionne
	$equipment_selected = get_equipment_all_by_id($pdo, $equipment_id);
	$datasheet_fetch    = get_datasheet_listall_by_equipment($pdo, $equipment_id);
	$datasheet_count    = count($datasheet_fetch);
}

$team_chief_id = param_post_key('responsable', $equipment_selected, 0);
?>

<div class="form">
<form action="equipment-process.php?categorie=<?php echo $category_id ?>" method="POST" name="inscrForm" enctype="multipart/form-data">
	<input type="hidden" name="id_equipment" value="<?php echo $equipment_id ?>" >
<table>
	<tbody>
		<tr>
			<th>
				Cat&eacute;gorie
			</th>
			<td>
				<select name="categorie">
				<?php
				// listing des categories
				$category_fetch = get_category_listshort($pdo);
				foreach ($category_fetch as $category_current) {
					echo '<option value="'.$category_current['id'].'"';
					if ($category_current['id'] == param_post_key('categorie', $equipment_selected, $category_id)) {
						echo " selected";
					}

					// si on choisit ajouter, le listing preselectionne la categorie
					echo '>'.$category_current['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="category-edit.php?"><?php echo ICON_ADD_CATEGORY ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				Nom *
			</th>
			<td>
				<input type="text" name="nom" size="30"  value="<?= param_post_key('nom', $equipment_selected) ?>" placeholder="Nom *">
			</td>
		</tr>

		<tr>
			<th>
				Mod&egrave;le *
			</th>
			<td>
				<input type="text"name="modele" size="30" value="<?= param_post_key('modele', $equipment_selected) ?>" placeholder="Mod&egrave;le *">
			</td>
		</tr>
		<tr>
			<th>
				Caract&eacute;ristiques (gamme d'usage) *
			</th>
			<td>
				<input type="text" name="gamme" size="30" maxlength="100" value="<?= param_post_key('gamme', $equipment_selected) ?>" placeholder="Caract&eacute;ristiques (gamme d'usage) *">
			</td>
		</tr>

		<tr>
			<th>
				&Eacute;quipe *
			</th>
			<td>
				<select name="equipe">
				<?php
				// recupere la liste des equipes
				$team_fetch = get_team_listshort($pdo);
				foreach ($team_fetch as $team_current) {
					echo '<option value="'.$team_current['id'].'"';
					if ($team_current['id'] == param_post_key('equipe', $equipment_selected, 0)) {
						echo ' selected';
					}
					echo '>'.$team_current['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="team-edit.php?"><?php echo ICON_ADD_TEAM ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				Fournisseur *
			</th>
			<td>
				<select name="fourn">
				<?php
				// recupere la liste des fournisseurs
				$supplier_fetch = get_supplier_listshort($pdo);
				foreach ($supplier_fetch as $supplier_current) {
					echo "<option value=\"".$supplier_current['id']."\"";
					if ($supplier_current['id'] == param_post_key('fournisseur', $equipment_selected, 0)) {
						echo ' selected';
						}
					echo '>'.$supplier_current['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="supplier-edit.php?"><?php echo ICON_ADD_SUPPLIER ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				Date achat * (<i>format YYYY-MM-DD</i>)
			</th>
			<td>
				<input type="date" name="achat" size="10" maxlength="10" value="<?= param_post_key('achat', $equipment_selected, date('Y-m-d', time())) ?>">
			</td>
		</tr>

		<tr>
			<th>
				Responsable *
			</th>
			<td>
				<select name="tech">
				<?php
				// recupere la liste des tech
				$user_fetch = get_user_listshort_with_right($pdo, 1, $team_chief_id);
				foreach ($user_fetch as $user_current) {
					echo '<option value="'.$user_current['id'].'"';
					if ($mode == 'Modifier' && $user_current['id'] == $team_chief_id) {
						echo ' selected';
					}
					echo '>'.$user_current['nom'].' '.$user_current['prenom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="user-edit.php?"><?php echo ICON_ADD_USER ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				R&eacute;paration
			</th>
			<td>
				<input type="text" name="reparation" size="30" maxlength="30" value="<?= param_post_key('reparation', $equipment_selected) ?>" placeholder="R&eacute;paration">
			</td>
		</tr>
		<tr>
			<th>
				Accessoires
			</th>
			<td>
				<input type="text" name="accessoires" size="30" maxlength="30" value="<?= param_post_key('accessoires', $equipment_selected) ?>" placeholder="Accessoires">
			</td>
		</tr>
		<tr>
			<th>
				Inventaire (facultatif)
			</th>
			<td>
				<input type="text" name="inventaire" size="30" maxlength="30" value="<?= param_post_key('inventaire', $equipment_selected) ?>" placeholder="Inventaire (facultatif)">
			</td>
		</tr>

		<tr>
			<th>
				Notice (facultatif) &nbsp; <?php echo ICON_SEE_DOC ?>
			</th>
			<td>
				<?php if ($mode == 'Modifier' && $datasheet_count > 0) { ?>
				<ul>
					<?php foreach ($datasheet_fetch as $datasheet_current) { ?>
					<li><a href="<?php echo $datasheet_path.'/'.$datasheet_current['pathname'] ?>" target="_top"><?php echo $datasheet_current['description'] ?> (<?php echo $datasheet_current['pathname']?>)</a></li>
					<?php } ?>
				</ul>
				<?php } ?>
				<input type="file" name="notice" value="<?= param_post_key('notice', $equipment_selected) ?>" placeholder="Notice (facultatif)">
			</td>
		</tr>

		<tr>
			<th>
				Code barre (chiffres) &nbsp; <?php echo ICON_BARCODE ?>
			</th>
			<td>
				<input type="text" name="barcode" size="20" maxlength="20" value="<?= param_post_key('barcode', $equipment_selected) ?>" placeholder="Code barre (chiffres)">
			</td>
		</tr>

		<tr>
			<th>
				Nombre de jour max pour un emprunt (0 = inderterminé)
			</th>
			<td>
				<input type="number" name="max-loan-day" min="0" max="90" value="<?= param_post_key('max_loan_day', $equipment_selected, "0")?>" required>
			</td>
		</tr>

		<tr>
			<th>
				Empruntable (oui / non - non par d&eacute;faut)
			</th>
			<td>
				<input type="checkbox" name="loanable" value="1" <?php if (param_post_key('loanable', $equipment_selected, 0) == 1) echo 'checked' ?> >
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

<?php pied_page() ?>
