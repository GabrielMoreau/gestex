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
	// recupere la liste de appareils
	$sql = 'SELECT * FROM Listing WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_app));
	$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);

en_tete('Caract&eacute;ristiques de l\'appareil : <b>'.$listing[0]['nom'].'</b>');
?>

<!--
<label for="element-toggle">Transpose</label>
<input id="element-toggle" type="checkbox" />
<div class="catalog" id="toggled-element">
 -->
<div class="catalog transpose">
<table class="narrow">
	<tbody>
		<tr>
			<th>
				Nom
			</th>
			<th>
				Mod&egrave;le
			</th>
			<th>
				Achat
			</th>
			<th>
				Accessoires
			</th>
			<th>
				R&eacute;paration / &Eacute;talonnages
			</th>
			<th>
				Responsable
			</th>

			<th>
				Num&eacute;ro d'instrument
			</th>
			<th>
				Inventaire
			</th>
		</tr>

<?php
	// recupere la liste de appareils
	$num_line = 0;
	foreach ($listing as $data) {
		if ($num_line % 2)
			echo '<tr class="impair">'.PHP_EOL;
		else
			echo '<tr class="pair">'.PHP_EOL;
		$num_line++;

	echo '  <td>';
	echo      $data['nom'].'&nbsp;';
	echo '  </td>';
	echo '  <td>';
	echo      $data['modele'].'&nbsp;';
	echo '  </td>';
	echo '  <td>';
	echo      $data['achat'].'&nbsp;';
	echo '  </td>';
	echo '  <td>';
	echo      $data['accessoires'].'&nbsp;';
	echo '  </td>';
	echo '  <td>';
	echo      $data['reparation'].'&nbsp;';
	echo '  </td>';


	// recupere le nom du tech
	$sql = 'SELECT id, nom FROM users WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($data['responsable']));
	$resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo '  <td>';
	if (!empty($resp)) {
		echo $resp[0]['nom'];
	}
	echo '  &nbsp;</td>';
	echo '  <td>';
	echo      $data['id'].'&nbsp;';
	echo '  </td>';
	echo '  <td>';
	echo      $data['inventaire'].'&nbsp;';
	echo '  </td>';
	echo '</tr>';
	} // end foreach
} // end if
?>
	</tbody>
</table>
</div>

<?php pied_page() ?>
