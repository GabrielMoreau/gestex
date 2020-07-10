<?php
//del-pret.php

// Authenticate
include("session_auth.php");
if (!auth(3))
	Header("Location: list_pret.php");

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['id']))
	Header("Location: list_pret.php");
else
	$id_pret = $_GET['id'];

$valid = 'no';
if ($_POST['ok'] == 'yes') // si ok dans l'url est 'yes', on valide la suppression
	$valid = 'yes';

if ($valid == 'yes') {
	if ($pdo = connect_db()) {
		// on supprime le pret
		$sql = 'DELETE LOW_PRIORITY FROM pret WHERE id = ? LIMIT 1;';
		// list($qh,$num) = query_db($querry);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_pret));
	}
	//on retourne a la page d'accueil
	Header("Location: list_pret.php");
}
else {
	en_tete('');

	echo 'Voulez-vous supprimer le pr&ecirc;t '.$id_pret.' ?<br />'.PHP_EOL;
	echo '<form action="del-pret.php?id='.$id_pret.'" method="POST">'.PHP_EOL;
	//echo '<a href="'.$self.'?id='.$id_pret.'&ok=yes">OUI</a><br />';
	//echo '<a href="'.$_SERVER['HTTP_REFERER'].'">NON</a><br />';
	echo ' <button type="submit" name="ok" value="yes">Oui</button>'.PHP_EOL;
	echo ' <button type="submit" formaction="list_pret.php" value="no">Non</button>'.PHP_EOL;
	echo '</form>'.PHP_EOL;

	pied_page()
}

?>
