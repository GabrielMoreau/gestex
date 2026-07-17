<?php
// equipment-view.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

session_start();
if (empty($_SESSION['logged_user'])) {
	$logged_level = 0;
} else {
	$logged_id    = $_SESSION['logged_id'];
	$logged_user  = strtolower($_SESSION['logged_user']);
	$logged_level = $_SESSION['logged_level'];
}

$equipment_id = param_get('equipment_id');
if (empty($equipment_id))
	redirect('equipment-list.php');

if ($pdo = connect_db()) {
	$equipment_selected = get_equipment_all_by_id($pdo, $equipment_id);
	$manager  = get_user_short_by_id($pdo, $equipment_selected['manager_user_id']);
	$team     = get_team_by_id($pdo, $equipment_selected['team_id']);
	$supplier = get_supplier_short_by_id($pdo, $equipment_selected['supplier_id']);
	$category = get_category_by_id($pdo, $equipment_selected['category_id']);

	$datacheet_path  = get_datasheet_basepath();
	$datasheet_fetch = get_datasheet_listall_by_equipment($pdo, $equipment_id);
	$datasheet_count = count($datasheet_fetch);

	if ($equipment_selected['barcode'] == 0)
		$equipment_selected['barcode'] = '';

	if ($equipment_selected['is_loanable'] == 1)
		$loan = get_loans_all_not_return_by_equipment($pdo, $equipment_id);

	$loan_borrow = get_loans_all_by_equipment_borrowed($pdo, $equipment_id);
	$equipment_loan_reserved = get_loan_all_last_returned($pdo, $equipment_id);

en_tete('Caractéristiques de l’appareil : <b>'.$equipment_selected['name'].'</b>');
?>

<div class="form">
<table>
	<tbody>
		<th colspan="2">
			<span class="option-right"><a href="equipment-list.php?category_id=<?php echo $equipment_selected['category_id'] ?>&highlight=<?php echo $equipment_id ?>#item<?php echo $equipment_id ?>"><?php echo ICON_LIST ?></a></span>
			<?php
				if ($logged_level >= 3) {
					echo '<span class="option-right"><a href="equipment-del.php?equipment_id='.$equipment_id.'">'.ICON_TRASH.'</a>&nbsp;</span>'.PHP_EOL;
					echo '<span class="option-right"><a href="equipment-edit.php?equipment_id='.$equipment_id.'">'.ICON_EDIT.'</a>&nbsp;</span>'.PHP_EOL;
					echo '<span class="option-right"><a href="intervention-edit.php?equipment_id='.$equipment_id.'">'.ICON_INTERVENTION.'</a>&nbsp;</span>'.PHP_EOL;
				}
			?>
		</th>
		<tr>
			<th>
				Nom
			</th>
			<td>
				<b><?php echo $equipment_selected['name'] ?></b>
			</td>
		</tr>
		<tr>
			<th>
				Modèle
			</th>
			<td>
				<?php echo $equipment_selected['model'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Caractéristiques
			</th>
			<td>
				<?php echo $equipment_selected['feature'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Achat
			</th>
			<td>
				<?php echo $equipment_selected['date_of_purchase'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Accessoires
			</th>
			<td>
				<?php echo $equipment_selected['accessories'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Réparation / Étalonnages
			</th>
			<td>
				<?php echo $equipment_selected['repair_comment'] ?>
			</td>
		</tr>

		<tr>
			<th>
				Responsable
			</th>
			<td>
				<a href="user-list.php?highlight=<?php echo $equipment_selected['manager_user_id'] ?>#item<?php echo $equipment_selected['manager_user_id'] ?>"><?php echo $manager['familyname'].' '.$manager['firstname'] ?></a>
			</td>
		</tr>

		<tr>
			<th>
				Équipe
			</th>
			<td>
				<a href="equipment-list.php?team_id=<?php echo $equipment_selected['team_id'] ?>&highlight=<?php echo $equipment_id ?>#item<?php echo $equipment_id ?>"><?php echo $team['name'] ?></a>
			</td>
		</tr>

		<tr>
			<th>
				Catégorie
			</th>
			<td>
				<a href="equipment-list.php?category_id=<?php echo $equipment_selected['category_id'] ?>&highlight=<?php echo $equipment_id ?>#item<?php echo $equipment_id ?>"><?php echo $category['name'] ?></a>
			</td>
		</tr>

		<tr>
			<th>
				Fournisseur
			</th>
			<td>
				<a href="supplier-list.php?highlight=<?php echo $equipment_selected['supplier_id'] ?>#item<?php echo $equipment_selected['supplier_id'] ?>"><?php echo $supplier['name'] ?></a>
			</td>
		</tr>

		<tr>
			<th>
				Numéro d’instrument
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
				<?php echo $equipment_selected['inventory_number'] ?>
			</td>
		</tr>
		<?php if ($datasheet_count > 0) { ?>
		<tr class="datasheet">
			<th rowspan="<?php echo $datasheet_count ?>">
				Notice &nbsp; <?php echo ICON_SEE_DOC ?>
			</th>
				<?php if ($datasheet_count == 1) { ?>
			<td>
				<a href="<?php echo $datacheet_path.'/'.$datasheet_fetch[0]['pathname'] ?>" target="_top"><?php echo $datasheet_fetch[0]['description'] ?></a>
				<?php if ($logged_level >= 3) {echo '<span class="option-right"><a href="datasheet-del.php?datasheet_id='.$datasheet_fetch[0]['id'].'">'.ICON_TRASH.'</a></span>';} ?>
			</td>
			<?php } else { ?>
				<?php $first = true; foreach ($datasheet_fetch as $datasheet_current) { ?>
				<?php if ($first) {$first = false;} else {echo '</tr>'.PHP_EOL.'<tr>'.PHP_EOL;} ?>
				<td>
					<a href="<?php echo $datacheet_path.'/'.$datasheet_current['pathname'] ?>" target="_top"><?php echo $datasheet_current['description'] ?></a>
					<?php if ($logged_level >= 3) {echo '<span class="option-right"><a href="datasheet-del.php?datasheet_id='.$datasheet_current['id'].'">'.ICON_TRASH.'</a></span>';} ?>
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
				<?php if ($equipment_selected['is_loanable'] == 1) {
					$is_borrowed = false;

					if ($loan != false)
						foreach($loan as $loan_current) {
							if ($loan_current["status"] == STATUS_LOAN_BORROWED) {
								$is_borrowed = True;
								break;
							}
						}

					if ($is_borrowed)
						echo 'Oui, en prêt';
					else
						echo 'Oui';


					if ($is_borrowed) {
						echo '<span class="option-right">';
						if ($logged_level >= 3) {echo '<a href="loan-edit.php?equipment_id='.$equipment_selected['id'].'&mode=booking">';}
						echo ICON_LOAN_RESERVED;
						if ($logged_level >= 3) {echo '</a>';}
						echo '</span>'.PHP_EOL;

						loan_list_container($pdo, $loan, $equipment_loan_reserved, $loan_borrow, $logged_level);
					} else {
						echo '<span class="option-right">';
						if ($logged_level >= 3) {echo '<a href="loan-edit.php?equipment_id='.$equipment_selected['id'].'&mode=loan">';}
						echo ICON_LOAN_BORROWED;
						if ($logged_level >= 3) {echo '</a>';}

						echo '</span>'.PHP_EOL;
						loan_list_container($pdo, $loan, $equipment_loan_reserved, $loan_borrow, $logged_level);
					}
				} else { echo 'Non'.PHP_EOL; } ?>
			</td>
		</tr>
	</tbody>
</table>
</div>

<?php } else { redirect('equipment-list.php'); } ?>

<?php pied_page() ?>
