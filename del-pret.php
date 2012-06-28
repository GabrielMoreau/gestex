<?php

//del-pret.php

// Authenticate
include("session_auth.php");

//if (!auth(3))
	Header("Location: reserva.php?user=3");


$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$id_app = $_GET[id];
if (empty($id_app))
	Header( "Location : reserva.php?user=3");

if ( $connex = connect_db() ){

// on supprime le pret
	$querry = "DELETE LOW_PRIORITY FROM pret WHERE id=$id_app LIMIT 1";
	list($qh,$num) = query_db($querry);
	
}

//on retourne a la page d'accueil
Header( "Location : reserva.php?user=3");
?>