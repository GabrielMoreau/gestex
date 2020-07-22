<?php
// category-add.php
$web_page = true;

// Authenticate
require_once('auth-functions.php');
require_once('html-functions.php');

auth_or_login('category-list.php');
level_or_alert(3, 'Modification d\'une &eacute;quipe');

$logged_id        = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

if (empty($_GET['id'])){
	//->nouvelle categorie
	$mode   = 'ajouter';
	$action = 'category-create.php';
	$cat_id = '';
}
else
	$cat_id = $_GET['id'];

if ($pdo = connect_db()) {
	if ($mode=="ajouter")
		en_tete('Ajouter une cat&eacute;gorie');

		// recupere l'appareil selectionne
		$sql = 'SELECT * FROM categorie ';
		// list($qh,$num) = query_db($querry);
		// $data = result_db($qh);
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="form">
<table>
	<tbody>
		<form action="<?php echo $action ?>" method="POST" name="inscrForm">
			<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >
		<tr>
			<th>
				Cat&eacute;gorie * (en minuscule uniquement)
			</th>
			<td>
				<input type="text" name="categorie" size="30" value="" placeholder="Cat&eacute;gorie *">
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
		<form action="equipment-list.php" method="POST" name="annulForm">
		<tr>
			<td colspan="2" class="button">
				<input type="submit" name="annul" value="Annuler">
			</td>
		</tr>
	</form>
</tbody>
</table>
</div>

<?php pied_page() ?>

