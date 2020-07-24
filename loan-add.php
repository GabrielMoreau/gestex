<?php
// loan-add.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

//if (!auth(3))
	//Header("Location: login.php");
session_start();
if (empty($_SESSION['logged_user'])) {
	$log = false;
	$logged_level = 0;
} else {
	$logged_id   = $_SESSION['logged_id'];
	$logged_user = strtolower($_SESSION['logged_user']);
}

$id_equipment = param_get('id'); // '' -> nouvel appareil

if (empty($_GET['pret'])) {
	$mode    = 'ajouter';
	$action  = 'loan-create.php';
	$id_pret = '';
} else {
	$mode    = 'modifier';
	$action  = 'modiff_pret.php';
	$id_pret = $_GET['pret'];
}

// transmet la valeur de la categorie a la page valid appareil

if ($pdo = connect_db()) {
	if ($mode == 'ajouter') {
		en_tete('Ajouter un pr&ecirc;t');
	}
	else if ($mode == 'modifier') {
		en_tete('Modifier les pr&ecirc;ts d\'un appareil');
	}
?>

<div class="form">
<table>
	<tbody>
		<form action="<?php echo $action ?>" method="POST" name="inscrForm">
			<input type="hidden" name="id_app" value="<?php echo $id_equipment?>" >
		<tr>
			<td>Nom de l'appareil
			</td>
			<td>
				<select name="nom">
				<!-- listing des appareils -->
				<?php
				if ($mode == 'ajouter') {
					$equipment = get_equipment_by_id($pdo, $id_equipment);
					echo '<option value="'.$equipment['id'].'">'.$equipment['nom'].'</option>';
				}
				?>
				</select>
				
			</td>
		</tr>

		<tr>
			<td>&Eacute;quipe *
			</td>
			<td>
				<select name="equipe">
				<?php
				// recupere la liste des equipes
				$sql = 'SELECT id, nom FROM equipe ORDER BY nom;';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array($courseId,$courseId));
				$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach($equipe as $chef){
					echo '<option value="'.$chef['id'].'"';
					if ($mode == 'modifier' && $chef['id'] == $pret[0]['equipe']) {
						echo ' selected';
					}
					echo '>'.$chef['nom'].'</option>';
				} //end foreach
				?>
				</select>
				
			</td>
		</tr>

		<tr>
			<td>Date demande pr&ecirc;t * (<i>format YYYY-MM-DD</i>)
			</td>
			<td>
				<input type="text" name="emprunt" size="10" maxlength="10" value="<?php
					if ($mode == 'modifier')
						echo $pret[0]['emprunt'];
					else
						echo date('Y-m-d', time() );
					?>" >
			</td>
		</tr>

		<tr>
			<td>Date de retour estim&eacute;e * (<i>format YYYY-MM-DD</i>)
			</td>
			<td>
				<input type="text" name="retour" size="10" maxlength="10" value="<?php
					if ($mode == 'modifier')
						echo $pret[0]['retour'];
					else
						echo date('Y-m-d', time() );
					?>" >
			</td>
		</tr>

		<tr>
			<td>Commentaire
			</td>
			<td>
				<input type="text" name="commentaire" size="30" maxlength="30" value="<?php if($mode=='modifier'){echo $pret[0]['commentaire'];} ?>" >
			</td>
		</tr>

		<tr>
			<td>Les champs avec * sont &agrave;
				remplir obligatoirement, les autres sont optionnels.
			</td>
			<td style="vertical-align: top;" align="right">
				<input type="submit" name="Login" value="<?php echo $mode ?>">
			</td>
		</tr>
		</form>

		<form action="category-list.php"method="POST" name="annulForm">
		<tr>
			<td colspan="2" style="vertical-align: top; text-align: right;">
				<input type="submit" name="annul" value="Annuler">
			</td>
		</tr>
		</form>
	</tbody>
</table>
</div>

<?php } else { echo "probl&egrave;me de connexion a la base de donn&eacute;e"; } ?>

<?php pied_page() ?>
