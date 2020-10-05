<?php
// equipment-view.php
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
	$equipment_selected = get_equipment_all_by_id($pdo, $id_equipment);
	$responsible = get_user_by_id($pdo, $equipment_selected['responsable']);
	$team        = get_team_by_id($pdo, $equipment_selected['equipe']);
	$supplier    = get_supplier_by_id($pdo, $equipment_selected['fournisseur']);
	$category    = get_category_by_id($pdo, $equipment_selected['categorie']);

	$datacheet_path  = get_datasheet_basepath();
	$datasheet_fetch = get_datasheet_listall_by_equipment($pdo, $id_equipment);
	$datasheet_count = count($datasheet_fetch);

	if ($equipment_selected['barcode'] == 0)
		$equipment_selected['barcode'] = '';

	if ($equipment_selected['loanable'] == 1)
		$loan = get_loan_all_by_id_equipment($pdo, $id_equipment);

en_tete('Caract&eacute;ristiques de l\'appareil : <b>'.$equipment_selected['nom'].'</b>');
?>

<div class="form">
<table>
	<tbody>
		<th colspan="2">
			<span class="option-right"><a href="equipment-list.php?categorie=<?php echo $equipment_selected['categorie'] ?>"><?php echo ICON_LIST ?></a></span>
			<?php
				if ($logged_level >= 3)
					echo '<span class="option-right"><a href="equipment-add.php?id='.$id_equipment.'">'.ICON_EDIT.'</a>&nbsp;</span>'.PHP_EOL;
			?>
		</th>
		<tr>
			<th>
				Nom
			</th>
			<td>
				<?php echo $equipment_selected['nom'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Mod&egrave;le
			</th>
			<td>
				<?php echo $equipment_selected['modele'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Caract&eacute;ristiques
			</th>
			<td>
				<?php echo $equipment_selected['gamme'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Achat
			</th>
			<td>
				<?php echo $equipment_selected['achat'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Accessoires
			</th>
			<td>
				<?php echo $equipment_selected['accessoires'] ?>
			</td>
		</tr>
		<tr>
			<th>
				R&eacute;paration / &Eacute;talonnages
			</th>
			<td>
				<?php echo $equipment_selected['reparation'] ?>
			</td>
		</tr>

		<tr>
			<th>
				Responsable
			</th>
			<td>
				<a href="user-list.php?highlight=<?php echo $equipment_selected['responsable'] ?>#item<?php echo $equipment_selected['responsable'] ?>"><?php echo $responsible['nom'].' '.$responsible['prenom'] ?></a>
			</td>
		</tr>

		<tr>
			<th>
				&Eacute;quipe
			</th>
			<td>
				<a href="equipment-list.php?equipe=<?php echo $equipment_selected['equipe'] ?>&highlight=<?php echo $id_equipment ?>#item<?php echo $id_equipment ?>"><?php echo $team['nom'] ?></a>
			</td>
		</tr>

		<tr>
			<th>
				Cat&eacute;gorie
			</th>
			<td>
				<a href="equipment-list.php?categorie=<?php echo $equipment_selected['categorie'] ?>&highlight=<?php echo $id_equipment ?>#item<?php echo $id_equipment ?>"><?php echo $category['nom'] ?></a>
			</td>
		</tr>

		<tr>
			<th>
				Fournisseur
			</th>
			<td>
				<a href="supplier-list.php?highlight=<?php echo $equipment_selected['fournisseur'] ?>#item<?php echo $equipment_selected['fournisseur'] ?>"><?php echo $supplier['nom'] ?></a>
			</td>
		</tr>

		<tr>
			<th>
				Num&eacute;ro d'instrument
			</th>
			<td>
				<?php echo $equipment_selected['id'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Inventaire
			</th>
			<td>
				<?php echo $equipment_selected['inventaire'] ?>
			</td>
		</tr>
		<?php if ($datasheet_count > 0) { ?>
		<tr class="datasheet">
			<th rowspan="<?php echo $datasheet_count ?>">
				Notice
			</th>
				<?php if ($datasheet_count == 1) { ?>
			<td>
				<a href="<?php echo $datacheet_path.'/'.$datasheet_fetch[0]['pathname'] ?>" target="_top"><?php echo $datasheet_fetch[0]['description'] ?></a>
			</td>
			<?php } else { ?>
				<?php $first = true; foreach ($datasheet_fetch as $datasheet) { ?>
				<?php if ($first) {$first = false;} else {echo '</tr>'.PHP_EOL.'<tr>'.PHP_EOL;} ?>
				<td>
					<a href="<?php echo $datacheet_path.'/'.$datasheet['pathname'] ?>" target="_top"><?php echo $datasheet['description'] ?></a>
				</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<?php } ?>
		<tr>
			<th>
				Code barre &nbsp; <?php echo ICON_BARCODE ?>
			</th>
			<td>
				<?php echo $equipment_selected['barcode'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Empruntable
			</th>
			<td>
				<?php if ($equipment_selected['loanable'] == 1) {
					if ($loan) {
						echo 'Oui, en pr&ecirc;t &#8594; '.$loan['retour'];
						if ($logged_level >= 3) {echo ' <span class="option-right"><a href="loan-del.php?id='.$loan['id'].'">';}
						echo ICON_RETURN;
						if ($logged_level >= 3) {echo '</a></span>';}
					} else {
						echo 'Oui &nbsp;';
						if ($logged_level >= 3) {echo ' <span class="option-right"><a href="loan-add.php?equipment='.$equipment_selected['id'].'">';}
						echo ICON_BOOKING;
						if ($logged_level >= 3) {echo '</a></span>';}
					}
				} else { echo 'Non'; } ?>
			</td>
		</tr>
	</tbody>
</table>
</div>

<?php } else { redirect('equipment-list.php'); } ?>

<?php pied_page() ?>
