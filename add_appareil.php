<?php
// add_appareil.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

auth_or_login('list_appareil.php');
level_or_alert(3, 'Modification d\'un appareil');

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$cat = '';
if (!empty($_GET['categorie']))
	$cat = $_GET['categorie'];

$app_id = '';
if (!empty($_GET['id']))
	$app_id = $_GET['id'];

if ($pdo = connect_db()) {

	$sql = 'SELECT * FROM categorie WHERE id = ?;' ;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($cat));
	$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (empty($app_id)) {
		// nouvel appareil
		// transmet la valeur de la categorie a la page valid appareil
		$mode   = 'ajouter';
		$action = 'valid_appareil.php?categorie='.$cat;
		en_tete('Ajouter un appareil');
	
	} else {
		// modif appareil
		$mode   = 'modifier';
		$action = 'modif_appareil.php?categorie='.$cat;
		en_tete('Modifier les caracteristiques d\'un appareil');

		// recupere l'appareil selectionne
		$sql = 'SELECT * FROM Listing WHERE id = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($app_id));
		$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
?>

<div class="form">
<table>
	<tbody>
		<form action="<?php echo $action ?>" method="POST" name="inscrForm" enctype="multipart/form-data">
			<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >
		<tr>
			<th>
				Cat&eacute;gorie
			</th>
			<td>
				<select name="categorie">
				<?php
				// listing des categories
				$sql = 'SELECT id, nom FROM categorie ORDER BY nom;';
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);

				foreach ($categorie as $chef) {
					echo '<option value="'.$chef['id'].'"';
					if ($mode == 'modifier' && $chef['id'] == $listing[0]['categorie']) {
						echo " selected";
					}
					if ($mode == 'ajouter' && $chef['id'] == $cat) {
						echo " selected";
					}

					// si on choisit ajouter, le listing preselectionne la categorie
					echo '>'.$chef['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="category-add.php?"><?php echo ICON_ADD_CAT ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				Nom *
			</th>
			<td>
				<input type="text" name="nom" size="30"  value="<?php if ($mode == 'modifier'){ echo $listing[0]['nom']; } ?>" placeholder="Nom *">
			</td>
		</tr>

		<tr>
			<th>
				Mod&egrave;le *
			</th>
			<td>
				<input type="text"name="modele" size="30" value="<?php if ($mode == 'modifier'){ echo $listing[0]['modele']; }?>" placeholder="Mod&egrave;le *">
			</td>
		</tr>
		<tr>
			<th>
				Gamme *
			</th>
			<td>
				<input type="text" name="gamme" size="10" maxlength="30" value="<?php if ($mode == 'modifier'){ echo $listing[0]['gamme']; }?>" placeholder="Gamme *">
			</td>
		</tr>

		<tr>
			<th>
				&Eacute;quipe *
			</th>
			<td>
				<select name="equipe">
				<?php
				// recupere la liste des equipes
				$sql = 'SELECT id, nom FROM equipe ORDER BY nom;';
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($equipe as $chef) {
					echo '<option value="'.$chef['id'].'"';
					if ($mode == 'modifier' && $chef['id'] == $listing[0]['equipe']) {
						echo ' selected';
					}
					echo '>'.$chef['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="team-add.php?"><?php echo ICON_ADD_EQUIP ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				Fournisseur *
			</th>
			<td>
				<select name="fourn">
				<?php
				// recupere la liste des fournisseurs
				$sql = 'SELECT id, nom FROM fournisseurs ORDER BY nom;';
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				$fournisseur = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($fournisseur as $chef) {
					echo "<option value=\"".$chef['id']."\"";
					if ($mode == 'modifier' && $chef['id'] == $listing[0]['fournisseur']) {
						echo ' selected';
						}
					echo '>'.$chef['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="supplier-add.php?"><?php echo ICON_ADD_FOURN ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				Date achat * (<i>format YYYY-MM-DD</i>)
			</th>
			<td>
				<input type="text" name="achat" size="10" maxlength="10" value="
					<?php
					if ($mode == 'modifier')
						echo $listing[0]['achat'];
					else
						echo date('Y-m-d', time());
					?>">
			</td>
		</tr>

		<tr>
			<th>
				Responsable *
			</th>
			<td>
				<select name="tech">
				<?php
				// recupere la liste des tech
				$sql = 'SELECT id, nom FROM users WHERE level > 1 and valid = 1;';
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($user as $chef) {
					echo '<option value="'.$chef['id'].'"';
					if ($mode == 'modifier' && $chef['id'] == $listing[0]['responsable']) {
						echo ' selected';
					}
					echo '>'.$chef['nom'].'</option>';
				} // end foreach
				?>
				</select>
				<span class="option-right"><a href="user-add.php?"><?php echo ICON_ADD_USER ?></a></span>
			</td>
		</tr>

		<tr>
			<th>
				R&eacute;paration *
			</th>
			<td>
				<input type="text" name="reparation" size="30" maxlength="30" value="<?php if ($mode == 'modifier'){echo $listing[0]['reparation'];} ?>" placeholder="R&eacute;paration *">
			</td>
		</tr>
		<tr>
			<th>
				Accessoires *
			</th>
			<td>
				<input type="text" name="accessoires" size="30" maxlength="30" value="<?php if ($mode == 'modifier'){echo $listing[0]['accessoires'];} ?>" placeholder="Accessoires *">
			</td>
		</tr>
		<tr>
			<th>
				Inventaire (facultatif)
			</th>
			<td>
				<input type="text" name="inventaire" size="30" maxlength="30" value="<?php if ($mode == 'modifier'){echo $listing[0]['inventaire'];} ?>" placeholder="Inventaire (facultatif)">
			</td>
		</tr>

		<tr>
			<th>
				Notice (facultatif)
			</th>
			<td>
				<input type="file" name="notice" value="<?php if ($mode == 'modifier'){echo $listing[0]['notice'];} ?>" placeholder="Notice (facultatif)">
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
		<form action="list_appareil.php"method="POST" name="annulForm">
		<tr>
			<td colspan="2" class="button">
				<input type="submit" name="annul" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>
</div>

<?php } else { redirect('list_appareil.php'); } ?>

<?php pied_page() ?>
