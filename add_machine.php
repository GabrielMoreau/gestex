<?php
/// add_machine.php
	// Authenticate
	require_once('auth-functions.php');

	if (!auth(3))
		Header("Location: login.php");

	$logged_id = $_SESSION['logged_id'];
	$logged_user = strtolower($_SESSION['logged_user']);

if (empty($_GET['id'])){
	//->nouvel appareil
	$app_id = '';
	$mode = 'ajouter';
	$action = 'valid_machine.php';
}
else{
	//->modif appareil
	$app_id = $_GET['id'];
	$mode = 'modifier';
	$action = 'modif_machine.php';
}

require_once('html-functions.php');

if ($pdo = connect_db()) {

if ($mode == 'ajouter') {
	en_tete('Ajouter un appareil');
}
else if ($mode == 'modifier'){
	en_tete('Modifier les caracteristiques d\'un appareil');
	// recupere l'appareil selectionne
	$sql = 'SELECT * FROM appareils WHERE id = ?;';
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($app_id));
	$appareils = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">
	<tbody>
		<form action="<?php echo $action ?>" method="POST" name="inscrForm">
			<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >
		<tr>
			<td style="vertical-align: top;">Nom *<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="nom" size="10" maxlength="10" value="<?php if($mode == "modifier"){ echo $appareils[0]['nom'];} ?>" ><br />
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">Description<br />
			</td>
			<td style="vertical-align: top;">
				<textarea name="descr" cols="50" rows="5"> <?php if($mode == "modifier"){ echo $appareils[0]['descr'];} ?></textarea>
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">&Eacute;quipe<br />
			</td>
			<td style="vertical-align: top;">
				<select name="equipe">
					<?php
					// recupere la liste des equipes
					$sql = 'SELECT id, nom FROM equipe;';
					// list($qheq,$numeq) = query_db($querry);
					// while ($chef = result_db($qheq)){
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach ($equipe as $chef) {
						echo "<option value=\"".$chef['id']."\"";
						if ($mode == 'modifier' && $chef['id'] == $appareils[0]['equipe']) {
							echo ' selected';
						}
					echo ">".$chef['nom']."</option>";
					} //end foreach
					?>
				</select>
				<br />
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">Responsable<br />
			</td>
			<td style="vertical-align: top;">
				<select name="tech">
					<?php
					// recupere la liste des tech
					$sql = 'SELECT id, nom FROM users WHERE level > 1;';
					// list($qheq,$numeq) = query_db($querry);
					// 	while ($chef = result_db($qheq)){
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach ($user as $chef) {
						echo "<option value=\"".$chef['id']."\"";
						if ($mode == 'modifier' && $chef['id'] == $appareils[0]['tech']) {
							echo ' selected';
						}
						echo ">".$chef['nom']."</option>";
					}//end foreach
					?>
				</select>
				<br />
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">Fournisseur<br />
			</td>
			<td style="vertical-align: top;">
				<select name="fourn">
					<?php
					// recupere la liste des fournisseurs
					$sql = 'SELECT id, nom FROM fournisseurs;';
					// list($qheq,$numeq) = query_db($querry);
					// 	while ($chef = result_db($qheq)){
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$fournisseur = $stmt->fetchAll(PDO::FETCH_ASSOC);
					foreach ($fournisseur as $chef) {
						echo "<option value=\"".$chef['id']."\"";
						if ($mode == 'modifier' && $chef['id'] == $appareils[0]['fournisseur']) {
							echo ' selected';
							}
						echo ">".$chef['nom']."</option>";
					} //end foreach
					?>
				</select>
				<br />
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">Date achat (<i>format YYYY-MM-DD</i>)<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="date" size="10" maxlength="10" value="
					<?php
					if ($mode == 'modifier')
						echo $appareils[0]['achat'];
					else
						echo date('Y-m-d', time());
					?>" >
				<br />
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">Facture<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="facture" size="30" maxlength="30" value="<?php if($mode == "modifier"){echo $appareils[0]['facture'];} ?>" ><br />
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">Les champs avec * sont &agrave;
				remplir obligatoirement, les autres sont optionnels.<br />
			</td>
			<td style="vertical-align: top;" align="right">
				<input type="submit" name="Login" value="<?php echo $mode ?>">
			</td>
		</tr><
		
		/form>
	</tbody>

	<tbody>
		<form action="list_machine.php" method="POST" name="annulForm">
		<tr>
			<td colspan="2" style="vertical-align: top; text-align: right;">
				<input type="submit" name="annul" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>
<br />

<?php } else { Header("Location: list_manip.php"); } ?>
<br />
</div>
<?php pied_page() ?>
