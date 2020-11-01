<?php
// supplier-edit.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('supplier-list.php');
level_or_alert(2, 'Modification d\'une &eacute;quipe');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$supplier_id = param_post_or_get('id', 0);
$mode = 'Modifier';
if ($supplier_id == 0) // new
	$mode   = 'Ajouter';

$pdo = connect_db_or_alert();

$supplier_selected = [];
if ($mode == 'Ajouter') {
	en_tete('Ajouter un fournisseur');
}
else if ($mode == 'Modifier') {
	en_tete('Modifier les coordonn&eacute;es d\'un fournisseur');
	// recupere le fournisseur selectionne
	$supplier_selected = get_supplier_all_by_id($pdo, $supplier_id);
}
?>

<div class="form">
<form action="supplier-process.php" method="POST" name="inscrForm">
	<input type="hidden" name="id" value="<?php if ($mode == 'Modifier'){ echo $supplier_id; } ?>">
<table>
	<tbody>
		<tr>
			<th>
				Nom *
			</th>
			<td>
				<input type="text" name="nom" size="50" maxlength="50" value="<?= param_post_key('nom', $supplier_selected) ?>" placeholder="Nom *">
			</td>
		</tr>
		<tr>
			<th>
				Adresse *
			</th>
			<td>
				<input type="text" name="adresse" size="50" maxlength="50" value="<?= param_post_key('adresse', $supplier_selected) ?>" placeholder="Adresse *">
			</td>
		</tr>
		<tr>
			<th>
				Adresse courriel <?=ICON_MAIL?>
			</th>
			<td>
				<input type="email" name="addr_mail" size="50" maxlength="50" value="<?= param_post_key('mail', $supplier_selected) ?>" placeholder="Adresse courriel">
			</td>
		</tr>
		<tr>
			<th>
				T&eacute;l&eacute;phone <?=ICON_PHONE?>
			</th>
			<td>
				<input type="tel" name="phone" size="15" maxlength="15" value="<?= param_post_key('tel', $supplier_selected) ?>" placeholder="T&eacute;l&eacute;phone">
			</td>
		</tr>
		<tr>
			<th>
				Fax
			</th>
			<td>
				<input type="tel" name="fax" size="15" maxlength="15" value="<?= param_post_key('fax', $supplier_selected) ?>" placeholder="Fax">
			</td>
		</tr>
		<tr>
			<th>
				URL
			</th>
			<td>
				<input type="text" name="www" size="50" maxlength="60" value="<?= param_post_key('www', $supplier_selected) ?>" placeholder="URL">
			</td>
		</tr>
		<tr>
			<th>Contact(s) - nom, fonction, t&eacute;l&eacute;phone...
			</th>
			<td>
				<textarea name="contact" cols="50" rows="5" placeholder="Contact(s) - nom, fonction, t&eacute;l&eacute;phone..."><?= param_post_key('contact', $supplier_selected) ?></textarea>
			</td>
		</tr>
		<tr>
			<th>Description pour faciliter la recherche de fournisseurs<br>
			Utiliser des mots stanadards (capteur, moteur, profil&eacute;...)
			</th>
			<td>
				<textarea name="descr" cols="50" rows="5" placeholder="Description pour faciliter la recherche de fournisseurs. Utiliser des mots standards (capteur, moteur, profil&eacute;...)"><?= param_post_key('descr', $supplier_selected) ?></textarea>
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
				<input class="cancel" type="submit" name="ok" formaction="supplier-list.php<?php if ($mode == 'Modifier'){ echo '?highlight='.$supplier_id.'#item'.$supplier_id; } ?>" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>
</form>
</div>

<?php pied_page() ?>
