<?php
// fiche_vie.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

session_start();
if(empty($_SESSION['logged_in_user'])){
	$log = false;
	$user_level = 0;
} else {
	$user_id        = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);
	$user_level     = $_SESSION['level'];
	$log = true;
}

$id_app = $_GET['id'];
if (empty($id_app))
	redirect('list_appareil.php');

if ($pdo = connect_db()) {
	$appareil_selected = get_appareil_all_by_id($pdo, $id_app);
	$responsable = get_user_by_id($pdo, $appareil_selected['responsable']);

en_tete('Caract&eacute;ristiques de l\'appareil : <b>'.$appareil_selected['nom'].'</b>');
?>

<!-- 
<label for="element-toggle">Transpose</label>
<input id="element-toggle" type="checkbox" />
<div class="catalog" id="toggled-element">
 -->

<div class="form">
<table>
	<tbody>
		<tr>
			<th>
				Nom
			</th>
			<td>
				<?php echo $appareil_selected['nom'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Mod&egrave;le
			</th>
			<td>
				<?php echo $appareil_selected['modele'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Achat
			</th>
			<td>
				<?php echo $appareil_selected['achat'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Accessoires
			</th>
			<td>
				<?php echo $appareil_selected['accessoires'] ?>
			</td>
		</tr>
		<tr>
			<th>
				R&eacute;paration / &Eacute;talonnages
			</th>
			<td>
				<?php echo $appareil_selected['reparation'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Responsable
			</th>
			<td>
				<a href="list_user.php?item<?php echo $appareil_selected['responsable'] ?>"><?php echo $responsable['nom'] ?></a>
			</td>
		</tr>
		<tr>
			<th>
				Num&eacute;ro d'instrument
			</th>
			<td>
				<?php echo $appareil_selected['id'] ?>
			</td>
		</tr>
		<tr>
			<th>
				Inventaire
			</th>
			<td>
				<?php echo $appareil_selected['inventaire'] ?>
			</td>
		</tr>
	</tbody>
</table>
</div>

<?php } else { redirect('list_appareil.php'); } ?>

<?php pied_page() ?>
