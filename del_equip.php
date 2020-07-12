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
	echo "Sur de supprimer l'&eacute;quipe ".$id_equip. " ?<br />";
	echo "<a href=\"".$_SERVER['PHP_SELF']."?id=".$id_equip."&ok=yes\">OUI</a><br />"; // si la personne repond 'oui', on recharge la page en mettant ok=yes dans l'url 
	echo "<a href=\"".$_SERVER['HTTP_REFERER']."\">NON</a><br />"; // sinon, on retourne a la page precedente
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
