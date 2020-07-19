<?php
// loan-del.php

// Authenticate
require_once('auth-functions.php');
require_once('html-functions.php');

if (!auth(3))
	Header("Location: loan-list.php");

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['id']) || $_POST['ok'] == 'cancel')
	Header("Location: loan-list.php");
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
	Header("Location: loan-list.php");
}

en_tete('Ramener un pr&ecirc;t');
?>

<center class="alert">
<form action="loan-del.php?id=<?php echo $id_pret ?>" method="POST">
	Voulez-vous supprimer le pr&ecirc;t <?php echo $id_pret ?> ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="loan-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
