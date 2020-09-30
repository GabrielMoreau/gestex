<?php
// equipment-list.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

session_start();
if (empty($_SESSION['logged_user'])) {
	$log          = false;
} else {
	$logged_id    = $_SESSION['logged_id'];
	$logged_user  = strtolower($_SESSION['logged_user']);
	$logged_level = $_SESSION['logged_level'];
	$log          = true;
}

$id_highlight = param_get('highlight', 0);

$title = 'Liste des appareils';

if (!$pdo = connect_db()) {
	echo 'Erreur sur la DBD';
}

// recupere la categorie
$cat = 0;
if (!empty($_GET['categorie'])) {
	$cat = $_GET['categorie'];
	$categorie_selected = get_category_by_id($pdo, $cat);
	$title .= ' de la cat&eacute;gorie <i>'.$categorie_selected['nom'].'</i>';
}

// recupere l'equipe
$eq = 0;
if (!empty($_GET['equipe'])) {
	$eq = $_GET['equipe'];
	$equip_selected = get_team_by_id($pdo, $eq);
	$title .= ' de l\'&eacute;quipe <i>'.$equip_selected['nom'].'</i>';
}

en_tete($title);
?>

<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<?php if ($cat == 0) { ?>
			<th>
				Cat&eacute;gorie
			</th>
			<?php } ?>
			<th>
				Num&eacute;ro de l'appareil
			</th>
			<th>
				Nom
			</th>
			<th>
				Mod&egrave;le
			</th>
			<th class="sorttable_nosort">
				Gamme
			</th>
			<?php if ($eq == 0) { ?>
			<th>
				&Eacute;quipe
			</th>
			<?php } ?>
			<th>
				Fournisseur
			</th>
			<th class="sorttable_nosort">
				Notice
			</th>
			<?php
			if ($log == true && $eq == 15)
				echo '<th class="sorttable_nosort"></th>'.PHP_EOL;
			if ($log == true && $logged_level == 2)
				echo '<th class="sorttable_nosort"></th>'.PHP_EOL;
			if ($log == true && $logged_level >= 3)
				echo '<th class="sorttable_nosort" colspan=2"><span class="option-right"><a href="equipment-add.php">'.ICON_ADD_EQUIPMENT.'</a></span></th>'.PHP_EOL;
			?>
		</tr>

<?php
	// recupere la liste de appareils
	if ($cat == 0 && $eq != 0) {
		$sql = 'SELECT * FROM Listing WHERE equipe = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($eq));
	} else if ($eq == 0 && $cat != 0) {
		$sql = 'SELECT * FROM Listing WHERE categorie = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($cat));
	} else {
		$sql = 'SELECT * FROM Listing;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array());
	}
	$equipment_fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);

	$num_line = 1;
	foreach ($equipment_fetch as $data) {
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($data['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'">'.PHP_EOL;

		if ($cat == 0) {
			echo '  <td>';
			$categorie = get_category_by_id($pdo, $data['categorie']);
			echo      $categorie['nom'];
			echo '  </td>'.PHP_EOL;
		}

		echo '  <td>';
		echo      $data['id'];
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo '    <a name="item'.$data['id'].'"></a><a href="equipment-view.php?id='.$data['id'].'">'. $data['nom'].'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo      $data['modele'];
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo      $data['gamme'];
		echo '  </td>'.PHP_EOL;

		if ($eq == 0) {
			echo '  <td>';
			// recupere le nom d'equipe
			$team =  get_team_by_id($pdo, $data['equipe']);
			echo      $team['nom'];
			echo '  </td>'.PHP_EOL;
		}

		echo '  <td>';
		// recupere le nom du fournisseur
		$sql = 'SELECT id, nom FROM fournisseurs WHERE id = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['fournisseur']));
		$fournisseur =  $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($fournisseur)) { echo $fournisseur[0]['nom'];}
		echo '  </td>'.PHP_EOL;

		echo '  <td>';
		// cherche l'existence de la notice
		if (get_datasheet_count_by_equipment($pdo, $data['id']) > 0) {
			echo ' <a href ="equipment-view.php?id=', $data['id'],'">'.ICON_SEE_DOC.'</a>';
		}
		echo '  </td>'.PHP_EOL;

		$sql = 'SELECT id FROM pret WHERE nom = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['id']));
		$pret = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!empty($pret)){
			$emprunt = 1;
		}else{
			$emprunt = 0;
		}
		if ($log === true && $eq==15 && $emprunt == 0) {
			echo '  <td>';
			echo '    <a href="loan-add.php?id=',$data['id'],'">'.ICON_BOOKING.'</a>';
			echo '  </td>'.PHP_EOL;
		}else if ($log === true && $eq==15 && $emprunt == 1) {
			echo '  <td>';
			echo '    <a href="loan-del.php?id=',$pret[0]['id'],'">'.ICON_RETURN.'</a>';
			echo '  </td>'.PHP_EOL;
		}

		if (($log === true && $logged_level >= 2) && ($eq != "15 pret=15")) {
			echo '  <td>';
			echo '    <a href="equipment-add.php?id=',$data['id'],'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
		}//end if
		if (($log === true && $logged_level >= 3) && ($eq != "15 pret=15")) {
			echo '  <td>';
			echo '    <a href="equipment-del.php?id=',$data['id'],'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;

		}
		echo '</tr>'.PHP_EOL;
	} // end foreach
?>

	</tbody>
</table>
</div>

<?php pied_page() ?>
