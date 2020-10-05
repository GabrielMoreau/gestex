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

if ($pdo = connect_db()) {

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
}
?>

<div class="form">
<table>
	<tbody>
		<form action="<?php echo $action ?>" method="POST" name="inscrForm">
			<input type="hidden" name="id_fourn" value="<?php if ($mode == 'Modifier'){ echo $id_supplier; } ?>">
		<tr>
			<th>
				Nom *
			</th>
			<td>
				<input type="text" name="nom" size="50" maxlength="50" value="<?php if ($mode == 'Modifier'){ echo $supplier['nom']; } ?>" placeholder="Nom *">
			</td>
		</tr>
		<tr>
			<th>
				Adresse
			</th>
			<td>
				<input type="text" name="adresse" size="50" maxlength="50" value="<?php if ($mode == 'Modifier'){ echo $supplier['adresse']; } ?>" placeholder="Adresse">
			</td>
		</tr>
		<tr>
			<th>
				Adresse courriel *
			</th>
			<td>
				<input type="text" name="addr_mail" size="50" maxlength="50" value="<?php if ($mode == 'Modifier'){ echo $supplier['mail']; } ?>" placeholder="Adresse courriel *">
			</td>
		</tr>
		<tr>
			<th>
				T&eacute;l&eacute;phone
			</th>
			<td>
				<input type="text" name="phone" size="15" maxlength="15" value="<?php if ($mode =='Modifier'){ echo $supplier['tel']; } ?>" placeholder="T&eacute;l&eacute;phone">
			</td>
		</tr>
		<tr>
			<th>
				Fax
			</th>
			<td>
				<input type="text" name="fax" size="15" maxlength="15" value="<?php if ($mode == 'Modifier'){ echo $supplier['fax']; } ?>" placeholder="Fax">
			</td>
		</tr>
		<tr>
			<th>
				URL
			</th>
			<td>
				<input type="text" name="www" size="50" maxlength="50" value="<?php if ($mode == 'Modifier'){ echo $supplier['www']; } ?>" placeholder="URL">
			</td>
		</tr>
		<tr>
			<th>Contact(s) - nom, fonction, telephone...
			</th>
			<td>
				<textarea name="contact" cols="50" rows="5" placeholder="Contact(s) - nom, fonction, t&eacute;l&eacute;phone..."><?php if ($mode == 'Modifier'){ echo $supplier['contact']; } ?></textarea>
			</td>
		</tr>
		<tr>
			<th>Description pour faciliter la recherche de fournisseurs<br>
			Utiliser des mots stanadards (capteur, moteur, profil&eacute;...)
			</th>
			<td>
				<textarea name="descr" cols="50" rows="5" placeholder="Description pour faciliter la recherche de fournisseurs. Utiliser des mots standards (capteur, moteur, profil&eacute;...)"><?php if ($mode == 'Modifier'){ echo $supplier['descr']; } ?></textarea>
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
		<form action="supplier-list.php" method="POST" name="annulForm">
		<tr >
			<td colspan="2" class="button">
				<input type="submit" name="annul" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>
</div>

<?php pied_page() ?>
