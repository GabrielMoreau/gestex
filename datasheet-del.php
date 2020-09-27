<?php
// datasheet-del.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('equipment-list.php');
level_or_alert(3, 'Suppression d\'une notice');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$id_datasheet = $_GET['id'];
if (empty($_GET['id']))
	redirect('equipment-list.php');

$valid = 'no';
if (param_get('ok') == 'yes') // si ok dans l'url est 'yes', on valide la suppression
	$valid = 'yes';

en_tete('Suppression d\'une notice');

if ($valid == 'no') {
	echo 'Sur de supprimer la notice '.$id_datasheet.' ?<br>';
	echo '<a href="datasheet-del.php?id='.$id_datasheet.'&ok=yes">OUI</a><br>';
	echo '<a href="equipment-list.php">NON</a><br>';
}
else {
	if ($pdo = connect_db()) {

		// on supprime la notice
		$sql = 'SELECT chemin_notice FROM notice WHERE id = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_datasheet));
		$datasheet_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);  // on recupere le chemin de la notice a supprimer

		if (!$datasheet_fetch) { // si ca n'a pas marche
			echo "<br />Erreur dans la r&eacute;cup&eacute;ration du chemin de la notice : ".$id_datasheet;
		} else {
			if(file_exists($datasheet_fetch[0]['chemin_notice'])) {
				$result = unlink($datasheet_fetch[0]['chemin_notice']);
				if (!$result) { // si ca n'a pas marche
					echo "<br />Erreur dans la suppression du fichier avec la notice : ".$id_datasheet;
				} else {
					$sql = 'DELETE LOW_PRIORITY FROM notice WHERE id = ? LIMIT 1;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array($id_datasheet));
					echo "Notice ".$id_datasheet." supprim&eacute;!<br />";
				}
			} else {
				echo "Erreur: la notice &agrave; supprimer n'existe pas";
			}
		}
	}
	else // on retourne a la page d'accueil
		redirect('equipment-list.php');
}
?>

<?php pied_page() ?>
