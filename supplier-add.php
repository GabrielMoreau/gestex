<?php
// supplier-add.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('supplier-list.php');
level_or_alert(2, 'Modification d\'une &eacute;quipe');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

if (empty($_GET['id'])) {
	//on vient depuis index.html
	//->nouvelle inscription
	$mode   = 'Ajouter';
	$action = 'supplier-create.php';
}
else {
	//on vient depuis list_manip.php
	//->modif coordonnees
	$id_supplier = $_GET['id'];
	$mode     = 'Modifier';
	$action   = 'supplier-update.php';
}

$pdo = connect_db_or_alert();

$supplier = [];
if ($mode == 'Ajouter') {
	en_tete('Ajouter un fournisseur');
}
else if ($mode == 'Modifier') {
	en_tete('Modifier les coordonn&eacute;es d\'un fournisseur');
	// recupere le fournisseur selectionne
	$supplier = get_supplier_all_by_id($pdo, $id_supplier);
}
else
	redirect('supplier-list.php');
?>

<div class="form">
<form action="<?php echo $action ?>" method="POST" name="inscrForm">
	<input type="hidden" name="id_fourn" value="<?php if ($mode == 'Modifier'){ echo $id_supplier; } ?>">
<table>
	<tbody>
		<tr>
			<th>
				Nom *
			</th>
			<td>
				<input type="text" name="nom" size="50" maxlength="50" value="<?= param_post_key('nom', $supplier) ?>" placeholder="Nom *">
			</td>
		</tr>
		<tr>
			<th>
				Adresse *
			</th>
			<td>
				<input type="text" name="adresse" size="50" maxlength="50" value="<?= param_post_key('adresse', $supplier) ?>" placeholder="Adresse *">
			</td>
		</tr>
		<tr>
			<th>
				Adresse courriel
			</th>
			<td>
				<input type="text" name="addr_mail" size="50" maxlength="50" value="<?= param_post_key('mail', $supplier) ?>" placeholder="Adresse courriel">
			</td>
		</tr>
		<tr>
			<th>
				T&eacute;l&eacute;phone
			</th>
			<td>
				<input type="text" name="phone" size="15" maxlength="15" value="<?= param_post_key('tel', $supplier) ?>" placeholder="T&eacute;l&eacute;phone">
			</td>
		</tr>
		<tr>
			<th>
				Fax
			</th>
			<td>
				<input type="text" name="fax" size="15" maxlength="15" value="<?= param_post_key('fax', $supplier) ?>" placeholder="Fax">
			</td>
		</tr>
		<tr>
			<th>
				URL
			</th>
			<td>
				<input type="text" name="www" size="50" maxlength="60" value="<?= param_post_key('www', $supplier) ?>" placeholder="URL">
			</td>
		</tr>
		<tr>
			<th>Contact(s) - nom, fonction, telephone...
			</th>
			<td>
				<textarea name="contact" cols="50" rows="5" placeholder="Contact(s) - nom, fonction, t&eacute;l&eacute;phone..."><?= param_post_key('contact', $supplier) ?></textarea>
			</td>
		</tr>
		<tr>
			<th>Description pour faciliter la recherche de fournisseurs<br>
			Utiliser des mots stanadards (capteur, moteur, profil&eacute;...)
			</th>
			<td>
				<textarea name="descr" cols="50" rows="5" placeholder="Description pour faciliter la recherche de fournisseurs. Utiliser des mots standards (capteur, moteur, profil&eacute;...)"><?= param_post_key('descr', $supplier) ?></textarea>
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
		<tr >
			<td colspan="2" class="button">
				<input class="cancel" type="submit" name="ok" formaction="supplier-list.php<?php if ($mode == 'Modifier'){ echo '?highlight='.$id_supplier.'#item'.$id_supplier; } ?>" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>
</form>
</div>

<?php pied_page() ?>
