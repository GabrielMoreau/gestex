<?php

//del_app.php

// Authenticate
include("session_auth.php");

if (!auth(3))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$id_app = $_GET['id'];
if (empty($id_app))
	Header("Location: list_app.php");

if ( $pdo = connect_db() ){

// on supprime le fournisseur
	$sql = 'DELETE LOW_PRIORITY FROM appareils WHERE id = ? LIMIT 1';
	list($qh,$num) = query_db($querry);
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_app));

}

//on retourne a la page d'accueil
Header("Location: list_app.php");
?>
