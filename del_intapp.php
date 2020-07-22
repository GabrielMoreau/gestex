<?php

//del_intapp.php

// Authenticate
require_once('auth-functions.php');

if (!auth(3))
	Header("Location: login.php");

$logged_id = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

if (empty($_GET['id']))
	Header("Location: list_intapp.php");
else
	$id_int = $_GET['id'];

	
if(empty($_GET['ok'])) // On recupere une variable ok qui sert a verifier que la personne est bien sur de supprimer la categorie choisi
	$valide ='no'	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
	$valide = 'yes';
else	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
	$valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid=="no"){
	echo "Sur de supprimer le Fournisseur ".$id_int." ?<br />";
	echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$id_int."&ok=yes\">OUI</a><br />";
	echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";
}
else{
	if ( $pdo = connect_db() ){
		// on supprime l'intervention
		$sql = "DELETE LOW_PRIORITY FROM intervention WHERE id = ? LIMIT 1";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_int));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$result){ // si ca n'a pas marche
			echo "<br />erreur dans la suppression de l'intervention : ".$id_int;
		}else{
			echo "Intervention ".$id_int." supprim&eacute;!<br />";
		}
	}

	//on retourne a la page d'accueil
	Header("Location: list_intapp.php");
}
?>
