<?php
// equipment-add.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('equipment-list.php');
level_or_alert(3, 'Modification d\'un appareil');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$id_category  = param_get('categorie');
$id_equipment = param_get('id');

if ($pdo = connect_db()) {
	if (empty($id_equipment)) {
		// nouvel appareil
		// transmet la valeur de la categorie a la page valid appareil
		$mode   = 'ajouter';
		$action = 'equipment-create.php?categorie='.$id_category;
		en_tete('Ajouter un appareil');
	
	} else {
		// modif appareil
		$mode   = 'modifier';
		$action = 'equipment-update.php?categorie='.$id_category;
		en_tete('Modifier les caracteristiques d\'un appareil');

		// recupere l'appareil selectionne
		$equipment = get_equipment_all_by_id($pdo, $id_equipment);
		$datacheet_path  = get_datasheet_basepath();
		$datasheet_fetch = get_datasheet_listall_by_equipment($pdo, $id_equipment);
		$datasheet_count = count($datasheet_fetch);
	}
?>

<div class="form">
<table>
	<tbody>
		<form action="<?php echo $action ?>" method="POST" name="inscrForm" enctype="multipart/form-data">
			<input type="hidden" name="id_equipment" value="<?php echo $id_equipment ?>" >
		<tr>
			<th>
				Cat&eacute;gorie
			</th>
			<td>
				<select name="categorie">
				<?php
				// listing des categories
				$category_fetch = get_category_listshort($pdo);
				foreach ($category_fetch as $category) {
					echo '<option value="'.$category['id'].'"';
					if ($mode == 'modifier' && $category['id'] == $equipment['categorie']) {
						echo " selected";
					}
					if ($mode == 'ajouter' && $category['id'] == $id_category) {
						echo " selected";
					}

					// si on choisit ajouter, le listing preselectionne la categorie
					echo '>'.$category['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="category-add.php?"><?php echo ICON_ADD_CATEGORY ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				Nom *
			</th>
			<td>
				<input type="text" name="nom" size="30"  value="<?php if ($mode == 'modifier'){ echo $equipment['nom']; } ?>" placeholder="Nom *">
			</td>
		</tr>

		<tr>
			<th>
				Mod&egrave;le *
			</th>
			<td>
				<input type="text"name="modele" size="30" value="<?php if ($mode == 'modifier'){ echo $equipment['modele']; }?>" placeholder="Mod&egrave;le *">
			</td>
		</tr>
		<tr>
			<th>
				Caract&eacute;ristiques (gamme d'usage) *
			</th>
			<td>
				<input type="text" name="gamme" size="30" maxlength="100" value="<?php if ($mode == 'modifier'){ echo $equipment['gamme']; }?>" placeholder="Caract&eacute;ristiques (gamme d'usage) *">
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
				foreach ($team_fetch as $team) {
					echo '<option value="'.$team['id'].'"';
					if ($mode == 'modifier' && $team['id'] == $equipment['equipe']) {
						echo ' selected';
					}
					echo '>'.$team['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="team-add.php?"><?php echo ICON_ADD_TEAM ?></a></span>
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
				foreach ($supplier_fetch as $supplier) {
					echo "<option value=\"".$supplier['id']."\"";
					if ($mode == 'modifier' && $supplier['id'] == $equipment['fournisseur']) {
						echo ' selected';
						}
					echo '>'.$supplier['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="supplier-add.php?"><?php echo ICON_ADD_SUPPLIER ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				Date achat * (<i>format YYYY-MM-DD</i>)
			</th>
			<td>
				<input type="text" name="achat" size="10" maxlength="10" value="<?php if ($mode == 'modifier') { echo $equipment['achat']; } else { echo date('Y-m-d', time()); } ?>">
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
				$user_fetch = get_user_listshort_with_right($pdo, 1);
				foreach ($user_fetch as $user) {
					echo '<option value="'.$user['id'].'"';
					if ($mode == 'modifier' && $user['id'] == $equipment['responsable']) {
						echo ' selected';
					}
					echo '>'.$user['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="user-add.php?"><?php echo ICON_ADD_USER ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				R&eacute;paration
			</th>
			<td>
				<input type="text" name="reparation" size="30" maxlength="30" value="<?php if ($mode == 'modifier'){echo $equipment['reparation'];} ?>" placeholder="R&eacute;paration">
			</td>
		</tr>
		<tr>
			<th>
				Accessoires
			</th>
			<td>
				<input type="text" name="accessoires" size="30" maxlength="30" value="<?php if ($mode == 'modifier'){echo $equipment['accessoires'];} ?>" placeholder="Accessoires">
			</td>
		</tr>
		<tr>
			<th>
				Inventaire (facultatif)
			</th>
			<td>
				<input type="text" name="inventaire" size="30" maxlength="30" value="<?php if ($mode == 'modifier'){echo $equipment['inventaire'];} ?>" placeholder="Inventaire (facultatif)">
			</td>
		</tr>

		<tr>
			<th>
				Notice (facultatif)
			</th>
			<td>
				<?php if ($mode == 'modifier' && $datasheet_count > 0) { ?>
				<ul>
					<?php foreach ($datasheet_fetch as $datasheet) { ?>
					<li><a href="<?php echo $datacheet_path.'/'.$datasheet['pathname'] ?>" target="_top"><?php echo $datasheet['description'] ?> (<?php echo $datasheet['pathname']?>)</a></li>
					<?php } ?>
				</ul>
				<?php } ?>
				<input type="file" name="notice" value="<?php if ($mode == 'modifier'){echo $equipment['notice'];} ?>" placeholder="Notice (facultatif)">
			</td>
		</tr>

		<tr>
			<th>
				Code barre (chiffres)
			</th>
			<td>
				<input type="text" name="barcode" size="20" maxlength="20" value="<?php if ($mode == 'modifier'){echo $equipment['barcode'];} ?>" placeholder="Code barre (chiffres)">
			</td>
		</tr>

		<tr>
			<th>
				Empruntable (oui / non - non par d&eacute;faut)
			</th>
			<td>
				<input type="checkbox" name="loanable" value="1" <?php if ($mode === 'modifier' && $equipment['loanable'] == 1) echo 'checked' ?> >
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
		<form action="equipment-list.php"method="POST" name="annulForm">
		<tr>
			<td colspan="2" class="button">
				<input type="submit" name="annul" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>
</div>

<?php } else { redirect('equipment-list.php'); } ?>

<?php pied_page() ?>
