<?php

//del_labview.php

// Authenticate
require_once('module/auth-functions.php');

if (!auth(3))
	Header("Location: login.php");

$logged_id = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

if (empty($_GET['id']))
	Header("Location: equipment-list.php");
else
	$id_app = $_GET['id'];
	
if(empty($_GET['ok'])) // On recupere une variable ok qui sert a verifier que la personne est bien sur de supprimer la categorie choisi
	$valide ='no'	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
	$valide = 'yes';
else // si c'est n'importe quoi d'autre, on ne valide pas la suppression
	$valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid=="no"){
echo "Sur de supprimer le labview ".$id_app." ?<br />";
echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$id_app."&ok=yes\">OUI</a><br />";
echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";
}
else{
	if ( $pdo = connect_db() ){

		// on supprime l'intervention
		$sql = 'DELETE LOW_PRIORITY FROM labview WHERE id = ? LIMIT 1';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_app));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$result){ // si ca n'a pas marche
			echo "<br />erreur dans la suppression du labview : ".$id_app;
		}else{
			echo "Labview ".$id_app." supprim&eacute;!<br />";
		}
	}
	// on retourne a la page d'accueil
	Header("Location: list_labview.php");
}
?>
