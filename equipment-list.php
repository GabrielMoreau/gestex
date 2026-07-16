<?php
// equipment-list.php
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

$id_highlight = param_post_or_get('highlight', 0);
$is_loanable  = param_get('is_loanable');

$title = 'Liste des appareils';
if ($is_loanable == 'yes')
	$title .= ' empruntables';

$pdo = connect_db_or_alert();

// Récupère la categorie
$category_id = param_get('category_id', 0);
if ($category_id > 0) {
	$category_selected = get_category_by_id($pdo, $category_id);
	$title .= ' de la catégorie <i>'.$category_selected['name'].'</i>';
}

// Récupère l'equipe
$team_id = param_get('team_id', 0);
if ($team_id > 0) {
	$team_selected = get_team_by_id($pdo, $team_id);
	$title .= ' de l’équipe <i>'.$team_selected['name'].'</i>';
}

// Récupère le fournisseur
$supplier_id = param_get('supplier_id', 0);
if ($supplier_id > 0) {
	$supplier_selected = get_supplier_short_by_id($pdo, $supplier_id);
	$title .= ' du fournisseur <i>'.$supplier_selected['name'].'</i>';
}

// Récupère l'utilisateur
$manager_user_id = param_get('manager_user_id', 0);
if ($manager_user_id > 0) {
	$user_selected = get_user_short_by_id($pdo, $manager_user_id);
	$title .= ' en gestion par l’utilisateur <i>'.$user_selected['firstname'].' '.$user_selected['familyname'].'</i>';
}
?>

<?php en_tete($title) ?>

<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<?php if ($category_id == 0): ?>
			<th>
				Catégorie
			</th>
			<?php endif; ?>
			<th>
				Id
			</th>
			<th class="max20">
				Nom
			</th>
			<th class="max20">
				Modèle
			</th>
			<th class="sorttable_nosort max20">
				Caractéristiques
			</th>
			<?php if ($team_id == 0): ?>
			<th>
				Équipe
			</th>
			<?php endif; ?>
			<?php if ($supplier_id == 0): ?>
			<th>
				Fournisseur
			</th>
			<?php endif; ?>
			<th class="sorttable_nosort">
				Notice
			</th>
			<th class="sorttable_nosort">
			</th>
			<?php
			if ($logged_level == 2)
				echo '<th class="sorttable_nosort"></th>'.PHP_EOL;
			if ($logged_level >= 3)
				echo '<th class="sorttable_nosort" colspan=2"><span class="option-right"><a href="equipment-edit.php">'.ICON_ADD_EQUIPMENT.'</a></span></th>'.PHP_EOL;
			echo '<th class="sorttable_nosort"></th>'.PHP_EOL;
			?>
		</tr>

<?php
	// Récupère la liste de appareils
	if ($team_id != 0)
		$equipment_fetch =  get_equipment_listall_by_team($pdo, $team_id);
	else if ($category_id != 0)
		$equipment_fetch = get_equipment_listall_by_category($pdo, $category_id);
	else if ($supplier_id != 0)
		$equipment_fetch = get_equipment_listall_by_supplier($pdo, $supplier_id);
	else if ($manager_user_id != 0)
		$equipment_fetch = get_equipment_listall_by_manager_user($pdo, $manager_user_id);
	else
		$equipment_fetch = get_equipment_listall($pdo);

	$num_line = 1;
	foreach ($equipment_fetch as $equipment_item) {
		if ($is_loanable == 'yes' && $equipment_item['is_loanable'] != 1)
			continue;
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($equipment_item['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'">'.PHP_EOL;

		if ($category_id == 0) {
			echo '  <td>';
			echo      '<a href="equipment-list.php?category_id='.$equipment_item['category_id'].'">'.$equipment_item['category_name'].'</a>'; // inner join
			echo '  </td>'.PHP_EOL;
		}

		echo '  <td>';
		echo      $equipment_item['id'];
		echo '  </td>'.PHP_EOL;
		echo '  <td class="max20">';
		echo '    <a name="item'.$equipment_item['id'].'"></a><a href="equipment-view.php?id='.$equipment_item['id'].'">'. $equipment_item['name'].'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td class="max20">';
		echo      $equipment_item['model'];
		echo '  </td>'.PHP_EOL;
		echo '  <td class="max20">';
		echo      $equipment_item['feature'];
		echo '  </td>'.PHP_EOL;

		if ($team_id == 0) {
			echo '  <td>';
			// Récupère le nom d'equipe
			$team = get_team_by_id($pdo, $equipment_item['team_id']);
			echo '    <a href="equipment-list.php?team_id='.$team['id'].'">'.$team['name'].'</a>';
			echo '  </td>'.PHP_EOL;
		}

		if ($supplier_id == 0) {
			echo '  <td>';
			// Récupère le nom du fournisseur
			$supplier = get_supplier_short_by_id($pdo, $equipment_item['supplier_id']);
			if ($supplier) {
				echo '    <a href="equipment-list.php?supplier_id='.$supplier['id'].'">'.$supplier['name'].'</a>';
			}
			echo '  </td>'.PHP_EOL;
		}

		echo '  <td>';
		// cherche l'existence de la notice
		if (get_datasheet_count_by_equipment($pdo, $equipment_item['id']) > 0) {
			echo ' <a href ="equipment-view.php?id='.$equipment_item['id'].'">'.ICON_SEE_DOC.'</a>';
		}
		echo '  </td>'.PHP_EOL;

		if ($equipment_item['is_loanable'] == 1) {
			$is_loan = false;
			# $loan = get_loan_short_by_id_equipment($pdo, $equipment_item['id']); # effectuer une modification ici
			$loans = get_loans_all_by_equipment($pdo, $equipment_item['id']);
			if ($loans != false) {
				foreach($loans as $loan) {
					if ($loan["status"] == STATUS_LOAN_BORROWED) {
						$is_loan = true;
					}
				}
			}

			echo '  <td>';
			if ($is_loan) {
				if ($logged_level >= 3) {echo '    <a href="loan-del.php?id='.$loan['id'].'">';}
				echo ICON_LOAN_RETURNED;
				if ($logged_level >= 3) {echo '</a>';}
			} else {
				if ($logged_level >= 3) {echo '    <a href="loan-edit.php?equipment_id='.$equipment_item['id'].'&mode=loan">';}
				echo ICON_LOAN_BORROWED;
				if ($logged_level >= 3) {echo '</a>';}
			}
			echo '  </td>';
			echo '  <td>';
			if ($logged_level >= 3) {echo '    <a href="loan-edit.php?equipment_id='.$equipment_item['id'].'&mode=booking">';}
			echo ICON_LOAN_RESERVED;
			if ($logged_level >= 3) {echo '</a>';}
			echo '  </td>';
		} else {
			echo '  <td></td>'.PHP_EOL;
		}

		if ($equipment_item['is_loanable'] == false) {
			echo '  <td></td>'.PHP_EOL;
		}
		if ($logged_level >= 2) {
			echo '  <td>';
			echo '    <a href="equipment-edit.php?id='.$equipment_item['id'].'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
		}
		if ($logged_level >= 3) {
			echo '  <td>';
			echo '    <a href="equipment-del.php?id='.$equipment_item['id'].'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;
		}
		echo '</tr>'.PHP_EOL;
	} // end foreach
?>

	</tbody>
</table>
</div>

<?php pied_page() ?>
