<?php
// category-del.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

auth_or_login('category-list.php');
level_or_alert(3, 'Suppression d\'une cat&eacute;gorie');

$id_category = param_post_or_get('id');
$valid       = param_post('ok', 'no');

if (empty($id_category) || $valid == 'cancel')
	redirect('category-list.php');

if ($valid == 'yes') {
	if ($pdo = connect_db()) {
		// on supprime la categorie
		$result = del_category_by_id($pdo, $id_category);
		if (!$result) { // si ca n'a pas marche
			echo "<br />Erreur dans la suppression de la cat&eacute;gorie : ".$id_category;
		}
	}
	//on retourne a la page precedente
	redirect('category-list.php');
}

en_tete('Suppression d\'une cat&eacute;gorie');
?>

<center class="alert">
<form action="category-del.php" method="POST">
	<input type="hidden" name="id" value="<?php echo $id_category ?>">
	Voulez-vous supprimer la cat&eacute;gorie <?php echo $id_category ?> ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="category-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
