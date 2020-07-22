<?php

// Authenticate

require_once('auth-functions.php');

if (!auth(1))
	Header("Location: login.php");

$logged_id = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);
$logged_level= $_SESSION['logged_level'];

require_once('html-functions.php');

en_tete('Liste des demandes en cours');

//recupere la methode de tri
if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];

//recupere la categorie
// $cat=$_GET['categorie'];
if (empty($_GET['categorie']))
	$cat ="";
else
	$cat = $_GET['categorie'];
//echo "$cat";
?>

<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr>
			<td style="vertical-align: top; text-align: center;">
				a href="<?php GESTEX_URL_ENTITY ?>">Retour &agrave;<br />l'intranet</a>
				<br />
			</td>
			<td style="vertical-align: top; text-align: center;">
				<a href="add_demande.php">Ajouter<br />une demande</a>
				<br />
			</td>
			<td style="vertical-align: top; text-align: center;">
				<a href="historique_demande.php">Historique<br />des demandes</a>
				<br />
			</td>
			<?php if ( $logged_level >=2 ) { ?>
			<td style="vertical-align: top; text-align: center;">
				<a href="logout.php?variable=instru">Quitter</a>
				<br />
			</td>
			<?php }	?>
		</tr>
	</tbody>
</table>

<br />
Liste des demandes en cours :<br />

<table cellpadding="2" cellspacing="2" border="1"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
	<tr bgcolor="#f7d709">
		<th style="vertical-align: top; text-align: center;">
			T&acirc;che<br />
		</th>
		<th style="vertical-align: top; text-align: center;">
			Nom du demandeur<br />
		</th>
		<th style="vertical-align: top; text-align: center;">
			D&eacute;tails<br />
		</th>
		<th style="vertical-align: top; text-align: center;">
			Date de demande<br />
		</th>
		<th style="vertical-align: top; text-align: center;">
			Avancement<br />
		</th>
		<th style="vertical-align: top; text-align: center;">
			Termin&eacute;e<br />
		</th>
		<th style="vertical-align: top; text-align: center;">
			Pi&egrave;ces jointes<br />
		</th>
		<?php if ( $logged_level >=2 )
			echo "</th><th>";
			if ( $logged_level >=3 )
			echo "</th><th>";
			?>
		</tr>
		<?php	//interrogation base de donnees

		if ( $pdo = connect_db() ){
			$sql = 'SELECT * FROM demandes WHERE termine = "non";';
			// list($qh,$num) = query_db($querry);
			// $last_id=0;
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		foreach($demandes as $data){
			// remplit le tableau
			echo"<tr><td style=\"vertical-align: top;\">";
			echo $data['tache'];
			echo"</td><td style=\"vertical-align: top;\">";
			echo $data['nomdemandeur'];
			echo"</td><td style=\"vertical-align: top;\">";
			echo $data['details'];
			echo"</td><td style=\"vertical-align: top;\">";

			echo $data['achat'];
			echo"</td><td style=\"vertical-align: top;\">";
			echo $data['avancement'];
			echo"</td><td style=\"vertical-align: top;\">";
			echo $data['termine'];

			echo"</td><td style=\"vertical-align: top;\">";
			echo $data['piecesjointes'];

			/// bouton lien vers la doc
			$dossier_proj ="data/instru/demandes/".$data['tache'];

			//remplace les espaces par des underscore
			$dossier_proj = str_replace(" ", "_", $dossier_proj);
			// cherche l'existence de ce dossier
			// echo $dossier_proj;
			/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
		if (@opendir($dossier_proj) != FALSE){
			// si trouve ajoute un bouton
			echo "Voir : <a href =\"joint_demande.php?id=". $data['id']."\">".$data['tache'].' '.ICON_SEE_DOC.'></a><br />';
		}
		if ($logged_level >= 2) {
			echo"</td><td style=\"vertical-align: top;\">";
			echo '<a href="add_demande.php?id='.$data['id'].'">'.ICON_EDIT.'</a>';
			echo"</td>";
		}//end if
		if ($logged_level >= 3) {
			echo"</td><td style=\"vertical-align: top;\">";
			echo '<a href="del_demande.php?id='.$data['id'].'">'.ICON_TRASH.'</a>';
			echo"</td>";

		}
		echo"</tr>";
	}//end foreach
	?>

	</tbody>
</table>
<br />
</div>
<?php pied_page() ?>
