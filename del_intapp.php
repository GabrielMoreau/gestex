<?php

//del_intapp.php

// Authenticate
include("session_auth.php");

if (!auth(3))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$id_int = $_GET[id];
if (empty($id_int))
	Header("Location: list_intapp.php");

if ( $pdo = connect_db() ){

// on supprime le fournisseur
	$sql = "DELETE LOW_PRIORITY FROM intervention WHERE id = ? LIMIT 1";
	// list($qh,$num) = query_db($querry);
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_int));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

}

//on retourne a la page d'accueil
Header("Location: list_intapp.php");
?>
