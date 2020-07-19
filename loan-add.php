<?php
// loan-add.php
// Authenticate
include("session_auth.php");

//if (!auth(3))
	//Header("Location: login.php");
session_start();
$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

//recuper la methode de tri
if (empty($_GET['tri']))
	$tri = 'id';
else
	$tri = $_GET['tri'];

if (empty($_GET['id'])) {
	$pret = '';
	//->nouvel appareil
} else {
	$pret = $_GET['id'];
}

if (empty($_GET['pret'])) {
	$mode    = 'ajouter';
	$action  = 'loan-create.php';
	$id_pret = '';
} else {
	$mode    = 'modifier';
	$action  = 'modiff_pret.php';
	$id_pret = $_GET['pret'];
}

//transmet la valeur de la categorie a la page valid appareil

require("html_functions.php");
if ($pdo = connect_db()) {
	if ($mode == 'ajouter') {
		en_tete('Ajouter un pr&ecirc;t');
	}
	else if ($mode == 'modifier') {
		en_tete('Modifier les pr&ecirc;ts d\'un appareil');

	// recupere l'appareil selectionne
	// $sql = 'SELECT * FROM pret WHERE id = ?;';
	// // list($qh,$num) = query_db($querry);
	// // $data = result_db($qh);
	// $stmt = $pdo->prepare($sql);
	// $stmt->execute(array($pret));
	// $pret = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" class="form" align="center">
	<tbody>
		<form action="<?php echo $action ?>" method="POST" name="inscrForm">
			<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >
		<tr>
			<td style="vertical-align: top;">Nom<br />
			</td>
			<td style="vertical-align: top;">
				<select name="nom">
				<!-- listing des appareils -->
				<?php
				$sql = 'SELECT id, nom FROM listing WHERE equipe = 15 AND id= ?;';
				// list($qheq,$numeq) = query_db($querry);
				// 	while ($chef = result_db($qheq)){
				if ($mode == 'ajouter') {
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array($pret));
					$chef = $stmt->fetchAll(PDO::FETCH_ASSOC);
					echo '<option value="'.$chef[0]['id'].'">'.$chef[0]['nom'].'</option>';
				}
				?>
				</select>
				<br />
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">&Eacute;quipe *<br />
			</td>
			<td style="vertical-align: top;">
				<select name="equipe">
				<?php
				// recupere la liste des equipes
				$sql = 'SELECT id, nom FROM equipe ORDER BY nom;';
				// list($qheq,$numeq) = query_db($querry);
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array($courseId,$courseId));
				$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
				// 	while ($chef = result_db($qheq)){
				foreach($equipe as $chef){
					echo '<option value="'.$chef['id'].'"';
					if ($mode == 'modifier' && $chef['id'] == $pret[0]['equipe']) {
						echo ' selected';
					}
					echo '>'.$chef['nom'].'</option>';
				} //end foreach
				?>
				</select>
				<br />
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">Date demande pr&ecirc;t * (<i>format YYYY-MM-DD</i>)<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="emprunt" size="10" maxlength="10" value="<?php
					if ($mode == 'modifier')
						echo $pret[0]['emprunt'];
					else
						echo date('Y-m-d', time() );
					?>" ><br />
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">Date de retour estim&eacute;e * (<i>format YYYY-MM-DD</i>)<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="retour" size="10" maxlength="10" value="<?php
					if ($mode == 'modifier')
						echo $pret[0]['retour'];
					else
						echo date('Y-m-d', time() );
					?>" ><br />
			</td>
		</tr>

		<tr>
			<td style="vertical-align: top;">Commentaire<br />
			</td>
			<td style="vertical-align: top;">
				<input type="text" name="commentaire" size="30" maxlength="30" value="<?php if($mode=='modifier'){echo $pret[0]['commentaire'];} ?>" ><br />
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

		<form action="list_categorie.php"method="POST" name="annulForm">
		<tr>
			<td colspan="2" style="vertical-align: top; text-align: right;">
				<input type="submit" name="annul" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>
<br />

<?php } else { echo "probl&egrave;me de connexion a la base de donn&eacute;e"; } ?>
<br />
</div>
<?php pied_page() ?>
