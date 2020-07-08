<?php

//del_categorie.php
//supprime une catégorie de la base de donnée
// Authenticate
include("session_auth.php");
require("html_functions.php");

if (!auth(3)) // si le level du user n'est pas >= 3, on l'emmène à la page pour se logger
	Header("Location: login.php");
			  // Sinon, on passe à la suite
$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['id'])) // on récupère l'id de la catégorie à supprimer dans l'url, s'il n'y en a pas, on va à la liste des catégorie
	Header("Location: list_categorie.php");
else
	$id_cat = $_GET['id']; // s'il y en a un, on le stock dans id_cat

if (empty($_GET['ok'])) // On récupère une variable ok qui sert a vérifier que la personne est bien sûr de supprimer la catégorie choisi
	$valide ='no'	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
	$valide = 'yes';
else	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
	$valid = 'no'; 
if (!isset($valid) || empty($valid) || $valid=="no"){ // on regarde ce qu'il y a dans $valid et si c'est NULL ou 'no', on pose la question
	echo "Sur de supprimer la catégorie ".$id_cat. " ?<br />";
	echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$id_cat."&ok=yes\">OUI</a><br />"; // si la personne répond 'oui', on recharge la page en mettant ok=yes dans l'url 
	echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />"; // sinon, on retourne à la page précédente
}
else{ // s'il y a ok=yes dans l'url
	if ( $pdo = connect_db() ){ // et que l'on arrive à se connécter à la base de donnée
		// on supprime la categorie
		$sql = 'DELETE LOW_PRIORITY FROM categorie WHERE id = ? LIMIT 1';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_cat));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$result){ // si ça n'a pas marché
			echo "<br />erreur dans la suppression de la catégorie : ".$id_cat;
		}else{
			echo "catégorie ".$id_cat." supprim&eacute;!<br />";
		}
	}
	//on retourne a la page d'accueil
	Header("Location: list_appareil.php");
}
?>
