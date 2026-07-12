<?php
// category-edit.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('category-list.php');
level_or_alert(3, 'Modification d\'une cat&eacute;gorie');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$id_category = param_get('id'); // -> modify
if (empty($id_category)){
	//->nouvelle categorie
	$mode  = 'Ajouter';
	$title = 'Ajouter une cat&eacute;gorie';
}
else {
	$mode  = 'Modifier';
	$title = 'Modifier une cat&eacute;gorie';
	$pdo   = connect_db();
	$category = get_category_by_id($pdo, $id_category);
}

en_tete($title);
?>

<div class="form">
<form action="category-process.php" method="POST" name="inscrForm">
	<input type="hidden" name="category_id" value="<?php echo $id_category ?>" >
<table>
	<tbody>
		<tr>
			<th>
				Cat&eacute;gorie * (en minuscule uniquement)
			</th>
			<td>
				<input type="text" name="categorie_name" size="30" value="<?php if ($mode == 'Modifier'){echo $category['name'];} ?>" placeholder="Cat&eacute;gorie *">
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
				<input class="cancel" type="submit" name="ok" formaction="category-list.php" value="Annuler">
			</td>
		</tr>
</tbody>
</table>
</form>
</div>

<?php pied_page() ?>

