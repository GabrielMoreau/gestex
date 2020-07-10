<?php
//del-pret.php

// Authenticate
include("session_auth.php");
require("html_functions.php");

if (!auth(3))
	Header("Location: list_pret.php");

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['id']) || $_POST['ok'] == 'cancel')
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

en_tete('Ramener un pr&ecirc;t');
?>

<center class="alert">
<form action="del-pret.php?id=<?php echo $id_pret ?>" method="POST">
	Voulez-vous supprimer le pr&ecirc;t <?php echo $id_pret ?> ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="list_pret.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
