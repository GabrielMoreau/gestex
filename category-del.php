<?php
// category-del.php
$web_page = true;

// Authenticate
require_once('auth-functions.php');
require_once('html-functions.php');

if (!auth(3)) // si le level du user n'est pas >= 3, on l'emmene a la page pour se logger
	Header("Location: login.php");
			  // Sinon, on passe a la suite
$logged_id = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

if (empty($_GET['id'])) // on recupere l'id de la categorie a supprimer dans l'url, s'il n'y en a pas, on va a la liste des categorie
	Header("Location: category-list.php");
else
	$id_cat = $_GET['id']; // s'il y en a un, on le stock dans id_cat

if (empty($_GET['ok'])) // On recupere une variable ok qui sert a verifier que la personne est bien sur de supprimer la categorie choisi
	$valid ='no';	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
	$valid = 'yes';
else	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
	$valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid=="no"){ // on regarde ce qu'il y a dans $valid et si c'est NULL ou 'no', on pose la question
	echo "Sur de supprimer la cat&eacute;gorie ".$id_cat. " ?<br />";
	echo "<a href=\"".$_SERVER['PHP_SELF']."?id=".$id_cat."&ok=yes\">OUI</a><br />"; // si la personne repond 'oui', on recharge la page en mettant ok=yes dans l'url 
	echo "<a href=\"category-list.php\">NON</a><br />";	// sinon, on retourne a la page precedente
}
else{ // s'il y a ok=yes dans l'url
	if ( $pdo = connect_db() ){ // et que l'on arrive a se connecter a la base de donnee
		// on supprime la categorie
		$sql = 'DELETE LOW_PRIORITY FROM categorie WHERE id = ? LIMIT 1';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_cat));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$result){ // si ca n'a pas marche
			echo "<br />Erreur dans la suppression de la cat&eacute;gorie : ".$id_cat;
		}else{
			echo "Cat&eacute;gorie ".$id_cat." supprim&eacute;!<br />";
		}
	}
	//on retourne a la page d'accueil
Header("Location: category-list.php");

}

?>
