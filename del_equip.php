<?php
//del_equip.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

auth_or_login('del_equip.php');
level_or_alert(3, 'Suppression d\'une &eacute;quipe');

en_tete('Suppression &eacute;quipe');

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$id_equip = $_GET['id'];
if (empty($id_equip))
	redirect('list_equip.php');

if(empty($_GET['ok'])) // On recupere une variable ok qui sert a verifier que la personne est bien sur de supprimer la categorie choisi
	$valid = 'no'; // s'il n'y a pas d'id, on met 'no' dans $valid
else if ($_GET['ok'] == 'yes') // si ok dans l'url est 'yes', on valide la suppression
	$valid = 'yes';
else	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
	$valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid== 'no'){ // on regarde ce qu'il y a dans $valid et si c'est NULL ou 'no', on pose la question
?>

<center class="alert">
<form action="del_equip.php" method="POST">
	<input type="hidden" name="id" value="<?php echo $id_equip ?>">
	Voulez-vous supprimer l'&eacute;quipe <?php echo $id_equip ?> ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="list_equip.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php
}
else{
	if ( $pdo = connect_db() ){
		// on supprime l'equipe
		$sql = 'DELETE LOW_PRIORITY FROM equipe WHERE id = ? LIMIT 1';
		//  $result = mysql_query($querry);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_equip));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	//on retourne a la page precedente
	redirect('list_equip.php');
}
?>

<?php pied_page() ?>
