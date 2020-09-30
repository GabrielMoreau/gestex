<?php
// equipment-del.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('equipment-list.php');
level_or_alert(3, 'Suppression d\'un appareil et de ses notices associ&eacute;es');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$id_equipment = param_get('id');
if (empty($id_equipment))
	redirect('equipment-list.php');

$valid = 'no'; 
if (param_get('ok') == 'yes') // si ok dans l'url est 'yes', on valide la suppression
	$valid = 'yes';
	
en_tete('Suppression d\'un appareil et de ses notices associ&eacute;es');

if ($valid == 'no') {
	echo 'Sur de supprimer l\'appareil '.$id_equipment.' ?<br>';
	echo '<a href="equipment-del.php?id='.$id_equipment.'&ok=yes">OUI</a><br>';
	echo '<a href="equipment-view.php?id='.$id_equipment.'">NON</a><br>';
}
else {
	if ($pdo = connect_db()) {
		$result = del_equipment($pdo, $id_equipment);
		if (!$result)
			echo "<br />Erreur dans la suppression de l'appareil : ".$id_equipment;
		else
		  	echo "Appareil ".$id_equipment." supprim&eacute;!<br />";
	}
	else //on retourne a la page d'accueil
		redirect('equipment-list.php');
}
?>

<?php pied_page() ?>
