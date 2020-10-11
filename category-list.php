<?php
// category-list.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

session_start();
if (empty($_SESSION['logged_user'])) {
	$logged_level = 0; // no auth
} else {
	$logged_id    = $_SESSION['logged_id'];
	$logged_user  = strtolower($_SESSION['logged_user']);
	$logged_level = $_SESSION['logged_level'];
}

$id_highlight = param_post_or_get('highlight', 0);

en_tete('Liste des appareils par cat&eacute;gorie');
?>

<div class="catalog">
<table class="sortable narrow">
	<tbody>
		<tr>
			<th>
				Cat&eacute;gorie
			</th>
			<?php if ($logged_level >= 3) { ?>
			<th class="sorttable_nosort" colspan="2">
				<span class="option-right"><a href="category-edit.php"><?php echo ICON_ADD_CATEGORY ?></a></span>
			</th>
			<?php } ?>
		</tr>

<?php
if ($pdo = connect_db()) {
	$category_fetch = get_category_listshort($pdo);
	$num_line = 1;
	foreach ($category_fetch as $category) {
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($category['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'">'.PHP_EOL;
		echo '  <td>';
		echo '    <a href="equipment-list.php?categorie='.$category['id'].'" name="item'.$category['id'].'">'.$category['nom'].'</a>';
		echo '  </td>'.PHP_EOL;
		if ($logged_level >= 3) {
			echo '  <td>';
			echo '    <a href="category-edit.php?id='.$category['id'].'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			echo '    <a href="category-del.php?id=',$category['id'],'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;
		}
		echo '</tr>'.PHP_EOL;
	}
}
?>
	</tbody>
</table>
</div>

<?php pied_page() ?>

