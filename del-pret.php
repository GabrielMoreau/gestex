<?php

//del-pret.php

// Authenticate
include("session_auth.php");
if (!auth(3))
	Header("Location: list_pret.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['id']))
	Header("Location: list_pret.php");
else
	$id_pret = $_GET['id'];

if(empty($_GET['ok'])) // On recupere une variable ok qui sert a verifier que la personne est bien sur de supprimer la categorie choisi
	$valid ='no';	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
	$valid = 'yes';
else	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
	$valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid=="no"){
	echo "Sur de supprimer le pret ".$id_pret. " ?<br />";
	echo "<a href=\"".$_SERVER['SCRIPT_NAME']."?id=".$id_pret."&ok=yes\">OUI</a><br />";
	echo "<a href=\"".$_SERVER['HTTP_REFERER']."\">NON</a><br />";
}
else{
	if ( $pdo = connect_db() ){
		// on supprime le pret
		$sql = 'DELETE LOW_PRIORITY FROM pret WHERE id = ? LIMIT 1';
		// list($qh,$num) = query_db($querry);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_pret));
		
	}
	//on retourne a la page d'accueil
	Header("Location: list_pret.php");
}
?>
