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

en_tete('Liste des appareils');

//recuper la methode de tri
$tri = 'id';
if (!empty($_GET['tri']))
	$tri = $_GET['tri'];

//recupere la categorie
// $cat=$_GET['categorie'];
//echo "$cat";
if (empty($_GET['categorie']))
	$cat = 0;
else
	$cat = $_GET['categorie'];

//recupere l'equipe

if (empty($_GET['equipe']))
	$eq = 0;
else
	$eq = $_GET['equipe'];

// $eq=$_GET['equipe'];
// echo "$eq";
?>

<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<th>
				Cat&eacute;gorie
			</th>
			<th>
				Num&eacute;ro de l'appareil
			</th>
			<th>
				Nom
			</th>
			<th>
				Mod&egrave;le
			</th>
			<th>
				Gamme
			</th>
			<th>
				&Eacute;quipe
			</th>
			<th>
				Fournisseur
			</th>
			<th>
				Notice
			</th>
			<?php
			if ($log == true && $eq == 15)
				echo '<th></th>'.PHP_EOL;
			if ($log == true && $user_level ==2)
				echo '<th></th>'.PHP_EOL;
			if ($log == true && $user_level >=3)
				echo '<th class="sorttable_nosort" colspan="2"></th>'.PHP_EOL;
			?>
		</tr>

<?php // interrogation base de donnees
if ($pdo = connect_db()) {
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

	$num_line = 0;
	foreach ($listing as $data) {
		// remplit le tableau
		if ($num_line % 2)
			echo '<tr class="impair">'.PHP_EOL;
		else
			echo '<tr class="pair">'.PHP_EOL;
		$num_line++;

		echo '  <td style="vertical-align: top;">';
		$sql = 'SELECT id, nom FROM categorie WHERE id = ?;';
		// list($qheq,$numeq) = query_db($querry);
		// 	$equip = result_db($qheq);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['categorie']));
		$categorie =  $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo      $categorie[0]['nom'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['id'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo '    <a href ="fiche_vie.php?id='.$data['id'].'">'. $data['nom'].'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['modele'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['gamme'];
		echo '  </td>'.PHP_EOL;

		echo '  <td style="vertical-align: top;">';
		// recupere le nom d'equipe
		$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
		// list($qheq,$numeq) = query_db($querry);
		// 	$equip = result_db($qheq);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['equipe']));
		$equipe =  $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo      $equipe[0]['nom'];
		echo '  </td>'.PHP_EOL;

		echo '  <td style="vertical-align: top;">';
		// recupere le nom du fournisseur
		$sql = 'SELECT id, nom FROM fournisseurs WHERE id = ?;';
		// list($qheq,$numeq) = query_db($querry);
		// 	$equip = result_db($qheq);
		//   		echo $equip['nom'];
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['fournisseur']));
		$fournisseur =  $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($fournisseur)) { echo $fournisseur[0]['nom'];}
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		// $sql = 'SELECT id, nom FROM categorie WHERE id = ?;';
		// list($qheq,$numeq) = query_db($querry);
		// $cat = result_db($qheq);
		// $stmt = $pdo->prepare($sql);
		// $stmt->execute(array($data['categorie']));
		// $categorie =  $stmt->fetchAll(PDO::FETCH_ASSOC);
		// 	echo $categorie[0]['nom'];

		///bouton lien vers la doc
		$dossier_proj = "data/notice/".$data['id'];
		$sql = 'SELECT id FROM pret WHERE nom = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['id']));
		$pret = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(!empty($pret)){
			$emprunt = 1;
		}else{
			$emprunt = 0;
		}
		//remplace les espaces par des underscore
		// cherche l'existence de ce dossier
		//echo $dossier_proj;
		/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
		if (@opendir($dossier_proj) != FALSE){
			//si trouve ajoute un bouton
			echo 'Voir : <a href ="notice.php?id=', $data['id'],'">',$data['nom'],' '.ICON_SEE_DOC.'</a><br />';
		}
		echo '  </td>'.PHP_EOL;

		if ($log === true && $eq==15 && $emprunt == 0) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="add-pret.php?id=',$data['id'],'">'.ICON_BOOKING.'</a>';
			echo '  </td>'.PHP_EOL;
		}else if ($log === true && $eq==15 && $emprunt == 1) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="del-pret.php?id=',$pret[0]['id'],'">'.ICON_RETURN.'</a>';
			echo '  </td>'.PHP_EOL;
		}

		if (($log === true && $user_level >= 2) && ($eq != "15 pret=15")) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="add_appareil.php?id=',$data['id'],'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
		}//end if
		if (($log === true && $user_level >= 3) && ($eq != "15 pret=15")) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="del_appareil.php?id=',$data['id'],'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;

		}
		echo '</tr>'.PHP_EOL;
	} //end foreach
} //end if
?>

	</tbody>
</table>
</div>

<?php pied_page() ?>
