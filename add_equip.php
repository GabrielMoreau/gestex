<?php
// add_equip.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

auth_or_login('list_equip.php');
level_or_alert(3, 'Modification d\'une &eacute;quipe');

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty( $_GET['id'])) {
	//->nouvelle inscription
	$mode = 'ajouter';
	$action = 'valid_equip.php';
}
else {
	//->modif coordonnees
	$equip_id = $_GET['id'];
	$mode = 'modifier';
	$action= 'modif_equip.php';
}

if ($pdo = connect_db()) {

if ($mode == 'ajouter'){
	en_tete('Ajouter une &eacute;quipe');
}
else if ($mode == 'modifier') {
	en_tete('Modifier les coordonn&eacute;es d\'une &eacute;quipe');

	// recupere le fournisseur selectionne
	$sql = 'SELECT * FROM equipe WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equip_id));
	$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">
	<tbody>
		<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_equip" value="<?php if( $mode=='modifier'){ echo $equip_id; }?>" >
		<tr>
			<td style="vertical-align: top;">Nom *<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="nom" size="10" maxlength="10" value="<?php if( $mode=='modifier'){ echo $equipe[0]['nom']; } ?>" ><br />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Description<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="descr" size="25" maxlength="255" value="<?php if( $mode=='modifier'){ echo $equipe[0]['descr']; } ?>" ><br />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Compte *<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="compte" size="5" maxlength="5" value="<?php if( $mode=='modifier'){ echo $equipe[0]['compte']; } ?>" ><br />
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Chef d'&Eacute;quipe<br />
			</td>
			<td style="vertical-align: top;">
			<?php // if( $mode=='modifier'){ echo $equipe[0]['chef']; } ?>
				<select name="chef">
				<?php
				// recupere laliste des chercheurs
				$sql = 'SELECT id, nom FROM users WHERE level >= 1 and valid = 1;';
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
				// list($qheq,$numeq) = query_db($querry);
				// 	while ($chef = result_db($qheq)){
				foreach ($user as $chef) {
					echo "<option value=\"".$chef['id']."\"";
					if ($mode == "modifier" && $chef['id'] == $equipe[0]['chef']) {
						echo " selected";
					}
					echo ">".$chef['nom']."</option>";
				} //end foreach
				?>
				</select>
				<span class="option-right"><a href="add_user.php?"><?php echo ICON_ADD_USER ?></a></span>
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Les champs avec * sont &agrave;
				remplir obligatoirement, les autres sont optionnels.<br />
			</td>
			<td style="vertical-align: top;" align="right">
				<input type="submit" name="Login" value="<?php echo $mode ?>">
			</td>
		</tr>
		</form>
	</tbody>
	<tbody>
		<form action="list_equip.php" method="POST" name="annulForm">
		<tr>
			<td colspan="2" style="vertical-align: top; text-align: right;">
				<input type="submit" name="annul" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>

<?php } else { Header("Location: list_manip.php"); exit(); } ?>

<?php pied_page() ?>
