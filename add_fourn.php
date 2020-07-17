<?php
// add_fourn.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

auth_or_login('list_fourn.php');
level_or_alert(2, 'Modification d\'une &eacute;quipe');

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['id'])) {
	//on vient depuis index.html
	//->nouvelle inscription
	$mode   = 'ajouter';
	$action = 'valid_fourn.php';
}
else {
	//on vient depuis list_manip.php
	//->modif coordonnees
	$fourn_id = $_GET['id'];
	$mode     = 'modifier';
	$action   = 'modif_fourn.php';
}

if ($pdo = connect_db()) {

if ($mode == 'ajouter') {
	en_tete('Ajouter un fournisseur');
}
else if ($mode == 'modifier') {
	en_tete('Mdifier les coordonn&eacute;es d\'un fournisseur');
	// recupere le fournisseur selectionne
	$sql = 'SELECT * FROM fournisseurs WHERE id = ?;';
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($fourn_id));
	$fournisseur = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else
	redirect('list_fourn.php');
}
?>

<div class="form">
<table>
	<tbody>
		<form action="<?php echo $action ?>" method="POST" name="inscrForm">
			<input type="hidden" name="id_fourn" value="<?php if ($mode == 'modifier'){ echo $fourn_id; } ?>">
		<tr>
			<th>
				Nom *
			</th>
			<td>
				<input type="text" name="nom" size="50" maxlength="50" value="<?php if ($mode == 'modifier'){ echo $fournisseur[0]['nom']; } ?>" placeholder="Nom *">
			</td>
		</tr>
		<tr>
			<th>
				Adresse
			</th>
			<td>
				<input type="text" name="adresse" size="50" maxlength="50" value="<?php if ($mode == 'modifier'){ echo $fournisseur[0]['adresse']; } ?>" placeholder="Adresse">
			</td>
		</tr>
		<tr>
			<th>
				Adresse courriel *
			</th>
			<td>
				<input type="text" name="addr_mail" size="50" maxlength="50" value="<?php if ($mode == 'modifier'){ echo $fournisseur[0]['mail']; } ?>" placeholder="Adresse courriel *">
			</td>
		</tr>
		<tr>
			<th>
				T&eacute;l&eacute;phone
			</th>
			<td>
				<input type="text" name="phone" size="15" maxlength="15" value="<?php if ($mode =='modifier'){ echo $fournisseur[0]['tel']; } ?>" placeholder="T&eacute;l&eacute;phone">
			</td>
		</tr>
		<tr>
			<th>
				Fax
			</th>
			<td>
				<input type="text" name="fax" size="15" maxlength="15" value="<?php if ($mode == 'modifier'){ echo $fournisseur[0]['fax']; } ?>" placeholder="Fax">
			</td>
		</tr>
		<tr>
			<th>
				URL
			</th>
			<td>
				<input type="text" name="www" size="50" maxlength="50" value="<?php if ($mode == 'modifier'){ echo $fournisseur[0]['www']; } ?>" placeholder="URL">
			</td>
		</tr>
		<tr>
			<th>Contact(s) - nom, fonction, telephone...
			</th>
			<td>
				<textarea name="contact" cols="50" rows="5" placeholder="Contact(s) - nom, fonction, t&eacute;l&eacute;phone..."><?php if ($mode == 'modifier'){ echo $fournisseur[0]['contact']; } ?></textarea>
			</td>
		</tr>
		<tr>
			<th>Description pour faciliter la recherche de fournisseurs<br>
			Utiliser des mots stanadards (capteur, moteur, profil&eacute;...)
			</th>
			<td>
				<textarea name="descr" cols="50" rows="5" placeholder="Description pour faciliter la recherche de fournisseurs. Utiliser des mots stanadards (capteur, moteur, profil&eacute;...)"><?php if ($mode == 'modifier'){ echo $fournisseur[0]['descr']; } ?></textarea>
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
		<form action="list_fourn.php" method="POST" name="annulForm">
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
