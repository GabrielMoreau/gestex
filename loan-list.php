<?php
// loan-list.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

session_start();
if (empty($_SESSION['logged_user'])) {
	$logged_level = 0;
} else {
	$logged_id        = $_SESSION['logged_id'];
	$logged_user = strtolower($_SESSION['logged_user']);
	$logged_level     = $_SESSION['logged_level'];
}

//recuper la methode de tri
if (empty($_GET['tri']))
	$tri = 'emprunt';
else
	$tri = $_GET['tri'];

//recupere l'equipe
$eq = '';
if (!empty($_GET['equipe']))
	$eq = $_GET['equipe'];

en_tete('Liste des pr&ecirc;ts');
?>

<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<th>
				Nom
			</th>
			<th>
				&Eacute;quipe
			</th>
			<th>
				Date
			</th>
			<th>
				Retour
			</th>
			<th>
				Emprunteur
			</th>
			<th>
				Num&eacute;ro de l'appareil
			</th>
			<?php 
			if ($logged_level >= 3)
				echo '<th class="sorttable_nosort"></th>'.PHP_EOL;
			?>
		</tr>

<?php	// interrogation base de donnees
if ($pdo = connect_db()) {
	// recupere la liste de appareils

	$sql = 'SELECT * FROM pret ORDER BY nom DESC;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$pret = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$num_line = 1;
	foreach ($pret as $data) {
		if ($num_line % 2)
			echo '<tr class="impair">'.PHP_EOL;
		else
			echo '<tr class="pair">'.PHP_EOL;
		$num_line++;

		// recupere le nom de l'appareil via l'ID qui est mis dans un champs texte !
		$appareil_selected = get_equipment_by_id($pdo, $data['nom']);
		echo '  <td>';
		echo      $appareil_selected['nom'];
		echo '  </td>'.PHP_EOL;

		// recupere le nom d'equipe
		$equip_selected = get_team_by_id($pdo, $data['equipe']);
		echo '  <td>';
		echo      $equip_selected['nom'];
		echo '  </td>'.PHP_EOL;

		echo '  <td>';
		echo      $data['emprunt'];
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo      $data['retour'];
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo      $data['commentaire'];
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo      $data['nom'];
		echo '  </td>'.PHP_EOL;

		if ($logged_level >= 3) {
			echo '  <td>';
			echo '    <a href="loan-del.php?id=',$data['id'],'">'.ICON_RETURN.'</a>';
			echo '  </td>'.PHP_EOL;
		}

		echo '</tr>'.PHP_EOL;
	} // end foreach
} // end if

?>
	</tbody>
</table>
</div>

<?php pied_page() ?>
