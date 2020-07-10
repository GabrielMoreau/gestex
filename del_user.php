<?php

//del_user.php

// Authenticate
include("session_auth.php");
require("html_functions.php");

if (!auth(3))
  Header("Location: login.php");

en_tete('Suppression Utilisateur');

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$supp = $_GET['suppr'];

echo $supp;

if (empty($_GET['id']))
 Header("Location: list_user.php");
else
  $id_user = $_GET['id'];

if(empty($_GET['ok'])) // On récupère une variable ok qui sert a vérifier que la personne est bien sûr de supprimer la catégorie choisi
	$valid = 'no';	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
	$valid = 'yes';
else // si c'est n'importe quoi d'autre, on ne valide pas la suppression
  $valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid == 'no') {
	echo 'Sur de supprimer l\'utilisateur '.$id_user.' ?<br />';
	echo '<a href="del_user.php?id='.$id_user.'&ok=yes&suppr='.$supp.'">OUI</a><br />';
	echo '<a href="list_user.php">NON</a><br />';
}
else {
	if ($pdo = connect_db()) {
		//on supprime cet user
		// $sql = 'DELETE LOW_PRIORITY FROM users WHERE id = ? LIMIT 1';
		if ($supp == 0) {
			$sql = 'UPDATE users SET valid = 1 WHERE id = ?;';
		}
		else if ($supp == 1) {
			$sql = 'UPDATE users SET valid = 0 WHERE id = ?;';
		}
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_user));
	}
	//on retourne a la page precedente
	Header("Location: list_user.php");
}
?>

<?php pied_page() ?>
