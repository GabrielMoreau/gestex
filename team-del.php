<?php
// team-del.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

auth_or_login('team-del.php');
level_or_alert(3, 'Suppression d\'une &eacute;quipe');

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$id_equip = $_POST['id'];
if (empty($id_equip))
	$id_equip = $_GET['id'];
if (empty($id_equip) || $_POST['ok'] == 'cancel')
	redirect('team-list.php');

$valid = 'no';
if ($_POST['ok'] == 'yes') // si ok dans l'url est 'yes', on valide la suppression
	$valid = 'yes';

if ($valid == 'yes') {
	if ( $pdo = connect_db() ){
		// on supprime l'equipe
		$sql = 'DELETE LOW_PRIORITY FROM equipe WHERE id = ? LIMIT 1';
		//  $result = mysql_query($querry);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_equip));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	//on retourne a la page precedente
	redirect('team-list.php');
}

en_tete('Suppression &eacute;quipe');
?>

<center class="alert">
<form action="team-del.php" method="POST">
	<input type="hidden" name="id" value="<?php echo $id_equip ?>">
	Voulez-vous supprimer l'&eacute;quipe <?php echo $id_equip ?> ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="team-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
