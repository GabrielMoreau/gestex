<?php

//del_categorie.php

// Authenticate
include("session_auth.php");

if (!auth(3))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['id']))
	Header("Location: list_appareil.php");
else
	$id_cat = $_GET['id'];

if ( $pdo = connect_db() ){

// on supprime le fournisseur
	$sql = 'DELETE LOW_PRIORITY FROM categorie WHERE id = ? LIMIT 1';
	// list($qh,$num) = query_db($querry);
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_cat));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

}

//on retourne a la page d'accueil
Header("Location: list_appareil.php");
?>
