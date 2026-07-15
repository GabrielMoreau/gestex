<?php
// supplier-list.php
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
$find = param_post('find', true);

en_tete('Liste de tous les fournisseurs', $find);
?>

<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<th>
				Nom
			</th>
			<th class="sorttable_nosort">
				Adresse
			</th>
			<th>
				Téléphone
			</th>
			<th class="sorttable_nosort">
				Fax
			</th>
			<th class="sorttable_nosort">
				Courriel
			</th>
			<th class="sorttable_nosort">
				WWW
			</th>
			<th>
				Contacts
			</th>
			<th>
				Description
			</th>
			<?php if ($logged_level == 2) { ?>
			<th class="sorttable_nosort">
			</th>
			<?php } ?>
			<?php if ($logged_level >= 3) { ?>
			<th class="sorttable_nosort" colspan=2">
				<span class="option-right"><a href="supplier-edit.php"><?php echo ICON_ADD_SUPPLIER ?></a></span>
			</th>
			<?php } ?>
		</tr>

<?php // interrogation base de donnees
if ($pdo = connect_db()) {
	$supplier_fetch = get_supplier_find($pdo, $find);
	$num_line = 1;
	foreach ($supplier_fetch as $supplier_item) {
		$count = get_equipment_count_by_supplier($pdo, $supplier_item['id']);
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($supplier_item['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'">'.PHP_EOL;
		if ($count === 0) {
			echo '  <td><a name="item'.$supplier_item['id'].'"></a>'.$supplier_item['name'].'</td>'.PHP_EOL;
		} else {
			echo '  <td><a name="item'.$supplier_item['id'].'"></a><a href="equipment-list.php?supplier_id='.$supplier_item['id'].'">'.$supplier_item['name'].'</a></td>'.PHP_EOL;
		}
		echo '  <td>'.$supplier_item['address'].'</td>'.PHP_EOL;
		echo '  <td>'.$supplier_item['phone'].'</td>'.PHP_EOL;
		echo '  <td>'.$supplier_item['fax'].'</td>'.PHP_EOL;
		echo '  <td>';
		$supplier_mail = sanitize_mail($supplier_item['email']);
		if (!empty($supplier_mail))
			echo '<a href="mailto:'.$supplier_mail.'">'.ICON_MAIL.'</a>';
		echo '</td>'.PHP_EOL;
		echo '  <td>';
		$url = sanitize_url($supplier_item['www']);
		if (!empty($url))
			echo '<a href="'.$url.'">'.ICON_URL.'</a>';
		echo '</td>'.PHP_EOL;
		echo '  <td>'.$supplier_item['contact'].'</td>'.PHP_EOL;
		echo '  <td>'.$supplier_item['description'].'</td>'.PHP_EOL;
		if ($logged_level >= 2) {
			echo '  </td><td>';
			echo '<a href="supplier-edit.php?id='.$supplier_item['id'].'">'.ICON_EDIT.'</a>';
			echo '</td>'.PHP_EOL;
		} //end if
		if ($logged_level >= 3) {
			echo '  </td><td>';
			if ($count === 0) {
				echo '<a href="supplier-del.php?id='.$supplier_item['id'].'">'.ICON_TRASH.'</a>';
			} else {
				echo $count;
			}
			echo '</td>'.PHP_EOL;
		} // end if
		echo '</tr>'.PHP_EOL;
	} // end foreach
} // end if
?>
	</tbody>
</table>
</div>

<?php pied_page() ?>
