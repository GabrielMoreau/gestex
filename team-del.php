<?php
// team-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('team-del.php');
level_or_alert(3, 'Suppression d\'une &eacute;quipe');

$logged_id        = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$team_id = $_POST['id'];
if (empty($team_id))
	$team_id = $_GET['id'];
if (empty($team_id) || $_POST['ok'] == 'cancel')
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
		$stmt->execute(array($team_id));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	//on retourne a la page precedente
	redirect('team-list.php');
}
?>

<?php en_tete('Suppression d\'une &eacute;quipe'); ?>

<center class="alert">
<form action="team-del.php" method="POST">
	<input type="hidden" name="id" value="<?php echo $team_id ?>">
	Voulez-vous supprimer l'&eacute;quipe <?php echo $team_id ?> ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="team-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
