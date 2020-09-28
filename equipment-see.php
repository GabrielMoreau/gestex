<?php
// equipment-see.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

session_start();
if (empty($_SESSION['logged_user'])) {
	$log = false;
	$logged_level = 0;
} else {
	$logged_id    = $_SESSION['logged_id'];
	$logged_user  = strtolower($_SESSION['logged_user']);
	$logged_level = $_SESSION['logged_level'];
	$log = true;
}

$id_equipment = param_get('id');
if (empty($id_equipment))
	redirect('equipment-list.php');

if ($pdo = connect_db()) {
	$appareil_selected = get_equipment_all_by_id($pdo, $id_equipment);
	$responsable = get_user_by_id($pdo, $appareil_selected['responsable']);

	$datacheet_path  = get_datasheet_basepath();
	$datasheet_fetch = get_datasheet_listall_by_equipment($pdo, $id_equipment);
	$datasheet_count = count($datasheet_fetch);

en_tete('Caract&eacute;ristiques de l\'appareil : <b>'.$appareil_selected['nom'].'</b>');
?>

<!-- 
<label for="element-toggle">Transpose</label>
<input id="element-toggle" type="checkbox" />
<div class="catalog" id="toggled-element">
 -->

<div class="form">
<table>
	<tbody>
		<tr>
			<th>
				Nom
			</th>
			<td>
				<?php echo $appareil_selected['nom'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Mod&egrave;le
			</th>
			<td>
				<?php echo $appareil_selected['modele'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Achat
			</th>
			<td>
				<?php echo $appareil_selected['achat'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Accessoires
			</th>
			<td>
				<?php echo $appareil_selected['accessoires'] ?>
			</td>
		</tr>
		<tr>
			<th>
				R&eacute;paration / &Eacute;talonnages
			</th>
			<td>
				<?php echo $appareil_selected['reparation'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Responsable
			</th>
			<td>
				<a href="user-list.php?item<?php echo $appareil_selected['responsable'] ?>"><?php echo $responsable['nom'] ?></a>
			</td>
		</tr>
		<tr>
			<th>
				Num&eacute;ro d'instrument
			</th>
			<td>
				<?php echo $appareil_selected['id'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Inventaire
			</th>
			<td>
				<?php echo $appareil_selected['inventaire'] ?>
			</td>
		</tr>
		<?php if ($datasheet_count > 0) { ?>
		<tr class="datasheet">
			<th rowspan="<?php echo $datasheet_count ?>">
				Notice
			</th>
		<?php } ?>
		<?php
		foreach ($datasheet_fetch as $datasheet) {
			#if (!is_file($dossier_proj.'/'.$datasheet['pathname']))
			#	continue;
			?>
			<td>
				<a href="<?php echo $datacheet_path.'/'.$datasheet['pathname'] ?>" target="_top">
					<?php echo $datasheet['description'] ?>
				</a>
			</td>
		<?php } ?>
		<?php if ($datasheet_count > 0) { ?>
		</tr>
		<?php } ?>
		<tr>
			<th>
				Code barre
			</th>
			<td>
				<?php echo $appareil_selected['barcode'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Empruntable
			</th>
			<td>
				<?php if ($appareil_selected['loanable'] == 1){ echo 'Oui'; } else { echo 'Non'; } ?>
			</td>
		</tr>
	</tbody>
</table>
</div>

<?php } else { redirect('equipment-list.php'); } ?>

<?php pied_page() ?>
