<?php
// supplier-list.php
$web_page = true;

// Authenticate
require_once('auth-functions.php');
require_once('html-functions.php');

session_start();
$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

$find = true;
#if (!empty($_SESSION['fournisseur_find']))
#	$find = $_SESSION['fournisseur_find'];
if (!empty($_POST['find']))
	$find = $_POST['find'];
#if ($user_level > 0)
#	$_SESSION['fournisseur_find'] = $find;

$id_highlight = 0;
if (!empty($_GET['highlight']))
	$id_highlight = $_GET['highlight'];

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
				T&eacute;l&eacute;phone
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
			<?php if ($user_level == 2) { ?>
			<th class="sorttable_nosort">
			</th>
			<?php } ?>
			<?php if ($user_level >= 3) { ?>
			<th class="sorttable_nosort" colspan=2">
				<span class="option-right"><a href="supplier-add.php"><?php ICON_ADD_FOURN ?></a></span>
			</th>
			<?php } ?>
		</tr>

<?php	// interrogation base de donnees
if ($pdo = connect_db()) {
	$supplier_fetch = get_supplier_find($pdo, $find);
	$num_line = 1;
	foreach ($supplier_fetch as $fournisseur) {
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($fournisseur['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'" id="'.$fournisseur['id'].'">'.PHP_EOL;
		echo '  <td style="vertical-align: top;">'.$fournisseur['nom'].'</td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">'.$fournisseur['adresse'].'</td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;" nowrap>'.$fournisseur['tel'].'</td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;" nowrap>'.$fournisseur['fax'].'</td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		if (!empty($fournisseur['mail']))
			echo '    <a href="mailto:'.$fournisseur['mail'].'">'.ICON_MAIL.'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		if (!empty($fournisseur['www']))
			echo '    <a href="http://'.$fournisseur['www'].'" target="_fournView">'.ICON_URL.'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">'.$fournisseur['contact'].'</td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">'.$fournisseur['descr'].'</td>'.PHP_EOL;
		if ($user_level >= 2) {
			echo '  </td><td style="vertical-align: top;">';
			echo '    <a href="supplier-add.php?id='.$fournisseur['id'].'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
		} //end if
		if ($user_level >= 3) {
			echo '  </td><td style="vertical-align: top;">';
			echo '    <a href="supplier-del.php?id='.$fournisseur['id'].'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;
		} // end if
		echo '</tr>'.PHP_EOL;
	} // end foreach
} // end if
?>
	</tbody>
</table>
</div>

<?php pied_page() ?>
