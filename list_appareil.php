<?php
// list_appareil.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

session_start();
if (empty($_SESSION['logged_in_user'])) {
	$log            = false;
} else {
	$user_id        = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);
	$user_level     = $_SESSION['level'];
	$log            = true;
}

$title = 'Liste des appareils';

//recuper la methode de tri
$tri = 'id';
if (!empty($_GET['tri']))
	$tri = $_GET['tri'];

if (!$pdo = connect_db()) {
	echo 'Erreur sur la DBD';
}

// recupere la categorie
$cat = 0;
if (!empty($_GET['categorie'])) {
	$cat = $_GET['categorie'];
	$categorie_selected = get_categorie_by_id($pdo, $cat);
	$title .= ' de la cat&eacute;gorie <i>'.$categorie_selected['nom'].'</i>';
}

// recupere l'equipe
$eq = 0;
if (!empty($_GET['equipe'])) {
	$eq = $_GET['equipe'];
	$equip_selected = get_equip_by_id($pdo, $eq);
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
			if ($log == true && $user_level ==2)
				echo '<th class="sorttable_nosort"></th>'.PHP_EOL;
			if ($log == true && $user_level >=3)
				echo '<th class="sorttable_nosort" colspan=2"><span class="option-right"><a href="add_appareil.php">'.ICON_ADD_APPAREIL.'</a></span></th>'.PHP_EOL;
			?>
		</tr>

<?php
	// recupere la liste de appareils

	// if ((!empty($cat))||(!empty($eq)))
	if ($cat == 0 && $eq != 0) {
		// echo "SELECT * FROM Listing WHERE equipe = ",$eq," ORDER BY ",$tri,"  ASC;"; 

		$sql = 'SELECT * FROM Listing WHERE equipe = ? ORDER BY ? ASC;';

		// list($qh,$num) = query_db($querry);
		// $last_id=0;
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($eq, $tri));
	} else if ($eq == 0 && $cat != 0) {
		// echo "SELECT * FROM Listing WHERE categorie = ",$cat," ORDER BY ",$tri,"  ASC;"; 
		$sql = 'SELECT * FROM Listing WHERE categorie = ? ORDER BY ? ASC;';
		// list($qh,$num) = query_db($querry);
		// $last_id=0;
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($cat, $tri));
	}

	if ($cat == 0 && $eq == 0) {
		// echo "SELECT * FROM Listing ORDER BY ",$tri,"  ASC;"; 
		$sql = 'SELECT * FROM Listing ORDER BY ? ASC;';
		// list($qh,$num) = query_db($querry);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($tri));
		// $last_id=0;
	}

	// $data = result_db($qh);
	$listing =  $stmt->fetchAll(PDO::FETCH_ASSOC);

	$num_line = 1;
	foreach ($listing as $data) {
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($data['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'" id="'.$data['id'].'">'.PHP_EOL;

		if ($cat == 0) {
			echo '  <td>';
			$sql = 'SELECT id, nom FROM categorie WHERE id = ?;';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array($data['categorie']));
			$categorie =  $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo      $categorie[0]['nom'];
			echo '  </td>'.PHP_EOL;
		}

		echo '  <td>';
		echo      $data['id'];
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo '    <a href ="appareil_see.php?id='.$data['id'].'">'. $data['nom'].'</a>';
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
			$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array($data['equipe']));
			$equipe =  $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo      $equipe[0]['nom'];
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

		$sql = 'SELECT nom_notice FROM notice WHERE id_appareil = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['id']));
		$notice = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!empty($notice[0]['nom_notice'] ) || !empty($notice[1]['nom_notice']) || !empty($notice[2]['nom_notice']) || !empty($notice[3]['nom_notice']) || !empty($notice[4]['nom_notice']) || !empty($notice[5]['nom_notice']) || !empty($notice[6]['nom_notice'])){
			//si trouve ajoute un bouton
			echo ' <a href ="notice.php?id=', $data['id'],'">'.ICON_SEE_DOC.'</a><br />';
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
			echo '    <a href="add-pret.php?id=',$data['id'],'">'.ICON_BOOKING.'</a>';
			echo '  </td>'.PHP_EOL;
		}else if ($log === true && $eq==15 && $emprunt == 1) {
			echo '  <td>';
			echo '    <a href="del-pret.php?id=',$pret[0]['id'],'">'.ICON_RETURN.'</a>';
			echo '  </td>'.PHP_EOL;
		}

		if (($log === true && $user_level >= 2) && ($eq != "15 pret=15")) {
			echo '  <td>';
			echo '    <a href="add_appareil.php?id=',$data['id'],'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
		}//end if
		if (($log === true && $user_level >= 3) && ($eq != "15 pret=15")) {
			echo '  <td>';
			echo '    <a href="del_appareil.php?id=',$data['id'],'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;

		}
		echo '</tr>'.PHP_EOL;
	} // end foreach
?>

	</tbody>
</table>
</div>

<?php pied_page() ?>
