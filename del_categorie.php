<?php

//del_categorie.php

// Authenticate
include("session_auth.php");

if (!auth(3))
	Header("Location: login.php");


$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$id_cat = $_GET[id];
if (empty($id_app))
	Header( "Location : instru.php");

if ( $connex = connect_db() ){

// on supprime le fournisseur
	$querry = "DELETE LOW_PRIORITY FROM categorie WHERE id=$id_cat LIMIT 1";
	list($qh,$num) = query_db($querry);
	
}

//on retourne a la page d'accueil
Header( "Location : instru.php");
?>
