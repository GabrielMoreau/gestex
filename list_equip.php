<?php
// list_equip.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

auth_or_login('list_equip.php');
level_or_alert(1, 'Liste de toutes les &eacute;quipes');

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

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
			if ($user_level == 2)
				echo '<th class="sorttable_nosort"></th>';
			if ($user_level >= 3)
				echo '<th class="sorttable_nosort" colspan=2"><span class="option-right"><a href="add_equip.php">'.ICON_ADD_EQUIP.'</a></span></th>';
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
		if (($num_line % 2 ) == 0)
			$class = 'pair';
		$num_line++;
		if ($data['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'" id="'.$data['id'].'">'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['nom'];
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

		if ($user_level >= 2) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="add_equip.php?id='.$data['id'].'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
		} //end if
		if ($user_level >= 3) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="del_equip.php?id='.$data['id'].'">'.ICON_TRASH.'</a>';
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
