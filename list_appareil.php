<?php

// Authenticate

require("session_auth.php");
session_start();
// if (!auth(1))
// 	Header("Location: login.php");
if (empty($_SESSION['logged_in_user'])) {
	$log            = false;
} else {
	$user_id        = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);
	$user_level     = $_SESSION['level'];
	$log            = true;
}

require("html_functions.php");

en_tete('Liste des appareils');

//recuper la methode de tri

if (empty($_GET['tri']))
	$tri = 'id';
else
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
<br />
<table cellpadding="2" cellspacing="2" border="1"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr>
			<td style="vertical-align: top; text-align: center;">
				<a href="<?php echo GESTEX_URL_SERVICE ?>">Retour &agrave;<br />la page du service</a>
				<br />
			</td>

			<?php if (empty($eq)) { ?>
			<td style="vertical-align: top; text-align: center;">
				<a href="add_appareil.php?categorie=<?php echo $cat ?>">Ajout<br />d'un appareil</a>	
				<br />
			</td>
			<td style="vertical-align: top; text-align: center;">
				<a href="list_fourn.php">Liste<br />des fournisseurs</a>
				<br />
			</td>
			<td style="vertical-align: top; text-align: center;">
				<a href="add_fourn.php">Ajout<br />d'un fournisseur</a>
				<br />
			</td>
			<?php } ?>

			<td style="vertical-align: top; text-align: center;">
				<a href="list_categorie.php">Retour aux cat&eacute;gories</a>
				<br />
			</td>

			<?php if ( $log == true && $user_level >=3 ) {	?>
			<td style="vertical-align: top; text-align: center;">
				<a href="add_categorie.php">Ajout<br />d'une cat&eacute;gorie</a>
				<br />
			</td>
			<?php } ?>

			<br /> <!-- semble en trop -->
		</td> <!-- semble en trop -->
		</tr>
	</tbody>
</table>

<br />
Liste des appareils : <br />
<i>Cliquer sur le nom d'un appareil pour conna&icirc;tre son mod&egrave;le, sa date d'achat, ses accessoires...</i><br />

<table cellpadding="2" cellspacing="2" border="1"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
			<th style="vertical-align: top; text-align: center;">
				<a href ="list_appareil.php?tri=categorie">Cat&eacute;gorie<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
			<a href ="list_appareil.php?tri=id">Num&eacute;ro de l'appareil<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
			<a href ="list_appareil.php?tri=nom">Nom</a><br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Mod&egrave;le<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Gamme<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				&Eacute;quipe<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Fournisseur<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Notice<br />
			</th>

			<?php
			if ($log == true && $user_level >=2)
				echo '<th></th>'.PHP_EOL;
			if ($log == true && $user_level >=3)
				echo '<th></th>'.PHP_EOL;
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

// echo "<tr>";
//      echo"<td style=\"vertical-align: top;\">";
// $sql = 'SELECT id, nom FROM categorie WHERE id = ?;';
// 	// list($qheq,$numeq) = query_db($querry);
// 		// $equip = result_db($qheq);
// 		$stmt = $pdo->prepare($sql);
// 		$stmt->execute(array($listing[0]['categorie']));
// 		$categorie =  $stmt->fetchAll(PDO::FETCH_ASSOC);
//       	echo $categorie[0]['nom'];
//  echo"</td><td style=\"vertical-align: top;\">";
// 	echo "<a href =\"fiche_vie.php?id=".$listing[0]['id']."\">". $listing[0]['nom']."</a>";
//        echo"</td><td style=\"vertical-align: top;\">";
// echo $listing[0]['modele'];
//   echo"</td><td style=\"vertical-align: top;\">";
// echo $listing[0]['gamme'];
//        echo"</td><td style=\"vertical-align: top;\">";
// 	// recupere le nom d'equipe
// 	$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
// 	// list($qheq,$numeq) = query_db($querry);
// 	// 	$equip = result_db($qheq);
// 	$stmt = $pdo->prepare($sql);
// 	$stmt->execute(array($listing[0]['equipe']));
// 	$equipe =  $stmt->fetchAll(PDO::FETCH_ASSOC);
// 	  echo $equipe[0]['nom'];
//        echo"</td><td style=\"vertical-align: top;\">";
// 	// recupere le nom du fournisseur
// 	$sql = 'SELECT id, nom FROM fournisseurs WHERE id = ?;';
// 	// list($qheq,$numeq) = query_db($querry);
// 	// 	$equip = result_db($qheq);
// 	$stmt = $pdo->prepare($sql);
// 	$stmt->execute(array($listing[0]['fournisseur']));
// 	$fournisseur =  $stmt->fetchAll(PDO::FETCH_ASSOC);
//       		echo $fournisseur[0]['nom'];
//   echo"</td><td style=\"vertical-align: top;\">";
// 	///bouton lien vers la doc
// 	$dossier_proj ="data/instru/".$listing[0]['nom'];
// 	//remplace les espaces par des underscore
// 	$dossier_proj = str_replace(" ", "_", $dossier_proj);
// 	// cherche l'existence de ce dossier
// 	//echo $dossier_proj;
// 	/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
// 	if (@opendir($dossier_proj) != FALSE){
// 		//si trouve ajoute un bouton
// 		echo "Voir : <a href =\"notice.php?id=". $data['id']."\">".$data['nom']."<img src=\"images/eye.svg\" nosave=\"\" title =\"Voir ce projet\" /></a><br />";
// 	}
// 	if (( $user_level >=2)&&($eq=="15 pret=15")) {
// echo"</td><td style=\"vertical-align: top;\">";
//       echo '<a href="add-pret.php?id=',$listing[0]['id'],'><img src="images/box-arrow-in-down.svg" nosave="" title="Demande de pr&ecirc;t" /></a>';
//      echo"</td>"; }
// 	 if (( $user_level >=2)&&($eq!="15 pret=15")) {
//       echo"</td><td style=\"vertical-align: top;\">";
//       echo '<a href="add_appareil.php?id=',$listing[0]['id'],'">'.ICON_EDIT.'</a>';
//       echo"</td>";
// 	}//end if
//  if (( $user_level >=3 )&&($eq!="15 pret=15")) {
//       echo"</td><td style=\"vertical-align: top;\">";
//       echo '<a href="del_appareil.php?id=',$listing[0][id],'"><img src="images/trash.svg" nosave="" title="Supprimer" /></a>';
//       echo"</td>";
// }
// echo"</tr>";

// while ($data = result_db($qh)) {
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
		echo      $fournisseur[0]['nom'];
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
		$dossier_proj = "data/instru/".$data['nom'];

		//remplace les espaces par des underscore
		$dossier_proj = str_replace(" ", "_", $dossier_proj);
		// cherche l'existence de ce dossier
		//echo $dossier_proj;
		/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
		if (@opendir($dossier_proj) != FALSE){
			//si trouve ajoute un bouton
			echo 'Voir : <a href ="notice.php?id=', $data['id'],'">',$data['nom'],'<img src="images/eye.svg" nosave="" title ="Voir ce projet" /></a><br />';
		}
		echo '  </td>'.PHP_EOL;

		if ($log === true && ($user_level >= 2) && ($eq == "15 pret=15")) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="add-pret.php?id=',$data['id'],'"><img src="images/box-arrow-in-down.svg" nosave="" title="Demande de pr&ecirc;t" /></a>';
			echo '  </td>'.PHP_EOL;
		}
		if (($log === true && $user_level >= 2) && ($eq != "15 pret=15")) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="add_appareil.php?id=',$data['id'],'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
		}//end if
		if (($log === true && $user_level >= 3) && ($eq != "15 pret=15")) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="del_appareil.php?id=',$data['id'],'"><img src="images/trash.svg" nosave="" title="Supprimer" /></a>';
			echo '  </td>'.PHP_EOL;

		}
		echo '</tr>'.PHP_EOL;
	} //end foreach
} //end if
?>
	</tbody>
</table>
<br />
</div>
<?php pied_page() ?>
