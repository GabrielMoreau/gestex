<?php

//del_app.php

// Authenticate
include("session_auth.php");

if (!auth(3))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['id']))
	Header("Location: list_machine.php");
else
	$id_app = $_GET['id'];

if(empty($_GET['ok'])) // On récupère une variable ok qui sert a vérifier que la personne est bien sûr de supprimer la catégorie choisi
	$valide ='no'	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
	$valide = 'yes';
else	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
	$valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid=="no"){
	echo "Sur de supprimer l'utilisateur ".$id_app. " ?<br />";
	echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$id_app."&ok=yes\">OUI</a><br />";
	echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";
}
else{
	if ( $pdo = connect_db() ){
		// on supprime le fournisseur
		$sql = 'DELETE LOW_PRIORITY FROM appareils WHERE id = ? LIMIT 1';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_app));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$result){ // si ça n'a pas marché
			echo "<br />erreur dans la suppression de l'appareil : ".$id_app;
		}else{
		  	echo "Appareil ".$id_app." supprim&eacute;!<br />";
		}
	}
	//on retourne a la page d'accueil
	Header("Location: list_machine.php");
}
?>
