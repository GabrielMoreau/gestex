<?php

//del-pret.php

// Authenticate
include("session_auth.php");

//if (!auth(3))
	Header("Location: reserva.php?user=3");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['id']))
	Header("Location: reserva.php?user=3");
else
	$id_pret = $_GET['id'];

if(empty($_GET['ok'])) // On recupere une variable ok qui sert a verifier que la personne est bien sur de supprimer la categorie choisi
	$valide ='no'	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
	$valide = 'yes';
else	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
	$valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid=="no"){
	echo "Sur de supprimer l'utilisateur ".$id_pret. " ?<br />";
	echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$id_pret."&ok=yes\">OUI</a><br />";
	echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";
}
else{
	if ( $pdo = connect_db() ){
		// on supprime le pret
		$sql = 'DELETE LOW_PRIORITY FROM pret WHERE id = ? LIMIT 1';
		// list($qh,$num) = query_db($querry);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_app));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$result){ // si ca n'a pas marche
			echo "<br />Erreur dans la suppression du pr&ecirc;t : ".$id_pret;
		}else{
		  	echo "Pr&ecirc;t ".$id_pret." supprim&eacute;!<br />";
		}
	}
	//on retourne a la page d'accueil
	Header("Location: reserva.php?user=3");
}
?>
