<?php

//del_labview.php

// Authenticate
include("session_auth.php");

if (!auth(3))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$id_app = $_GET[id];
if (empty($id_app))
	Header("Location: instru.php");

if ( $connex = connect_db() ){

// on supprime le fournisseur
	$querry = "DELETE LOW_PRIORITY FROM labview WHERE id=$id_app LIMIT 1";
	list($qh,$num) = query_db($querry);

}

//on retourne a la page d'accueil
Header("Location: labview.php");
?>
