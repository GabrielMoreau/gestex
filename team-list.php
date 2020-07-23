<?php
// team-list.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('team-list.php');
level_or_alert(1, 'Liste de toutes les &eacute;quipes');

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

en_tete('Liste de toutes les &eacute;quipes');

//recuper la methode de tri
if (empty($_GET['tri']))
	$tri = 'nom';
else
	$tri = $_GET['tri'];

$id_highlight = 0;
if (!empty($_GET['highlight']))
	$id_highlight = $_GET['highlight'];
?>

<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<th>
				Nom
			</th>
			<th>
				Description
			</th>
			<th>
				Compte
			</th>
			<th>
				Chef d'&eacute;quipe
			</th>

			<?php
			if ($logged_level == 2)
				echo '<th class="sorttable_nosort"></th>';
			if ($logged_level >= 3)
				echo '<th class="sorttable_nosort" colspan=2"><span class="option-right"><a href="team-add.php">'.ICON_ADD_EQUIP.'</a></span></th>';
			?>
		</tr>

<?php	//interrogation base de donnees
if ($pdo = connect_db()) {
	// recupere la liste de fournisseurs
	$sql = 'SELECT * FROM equipe ORDER BY ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$num_line = 1;
	foreach ($equipe as $data) {
		// remplit le tableau
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($data['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'">'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo '    <a name="item'.$data['id'].'"></a>'.$data['nom'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['descr'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['compte'];
		echo '  </td>'.PHP_EOL;

		// recupere le nom de chef d'equipe
		$sql = 'SELECT id, nom FROM users WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['chef']));
		$chef = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo '  <td style="vertical-align: top;">';
		if(!empty($chef)){
			 echo      $chef[0]['nom'];
		}
		echo '  </td>'.PHP_EOL;

		if ($logged_level >= 2) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="team-add.php?id='.$data['id'].'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
		} //end if
		if ($logged_level >= 3) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="team-del.php?id='.$data['id'].'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;
		} //end if
		echo '</tr>'.PHP_EOL;
	} //end foreach
} //end if
?>
	</tbody>
</table>
</div>

<?php pied_page() ?>
