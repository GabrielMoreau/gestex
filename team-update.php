<?php
// team-update.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('team-list.php');
level_or_alert(3, 'Modification d\'une &eacute;quipe');

$logged_user = strtolower($_SESSION['logged_user']);
$logged_level     = $_SESSION['logged_level'];

unset($erreur);

$id_equip = $_POST['id_equip'];
$nom      = $_POST['nom'];
$compte   = $_POST['compte'];
$chef     = $_POST['chef'];
$descr    = $_POST['descr'];
//variables ne pouvant etre nulles
if (empty($_POST['id_equip']))
	$erreur = 'id non pr&eacute;cis&eacute;';
if (empty($_POST['nom']))
	$erreur = 'Nom d\'&eacute;quipe non pr&eacute;cis&eacute;';
if (empty($_POST['compte']))
	$erreur = 'Compte non pr&eacute;cis&eacute;';

en_tete('R&eacute;sultat modification');

if (!empty($erreur) ){
	//erreur
	echo '<br />Erreur : '.$erreur;
	echo '<br /><a href="team-add.php?id='.$id_equip.'">Suite</a><br />'.PHP_EOL;
	pied_page();
	exit();
}

if ($pdo = connect_db()) {
	//recupere les anciennes caracteristiques
	$team = get_team_all_by_id($pdo, $id_equip);

	echo $nom." ".$team['nom']."<br />";
	echo $descr." ".$team['descr']."<br />";
	echo $compte." ".$team['compte']."<br />";
	echo $chef." ".$team['chef']."<br />";

	//modification equip
	$modif = 0;
	//on construit la demande
	$querry = 'UPDATE LOW_PRIORITY equipe SET ';
		if ($nom != $team['nom']){
			//modif du nom
			$modif = 1;
			$querry .= "nom='$nom',";
		}
		if ($descr != $team['descr']){
			//modif de la descr
			$modif = 1;
			$querry .= "descr='$descr',";
		}
		if ($compte != $team['compte']){
			//modif du compte
			$modif = 1;
			$querry .= "compte='$compte',";
		}
		if ($chef != $team['chef']){
			//modif du chef
			$modif = 1;
			$querry .= "chef='$chef',";
		}
		// supprime la derniere virgule
		$querry[strlen($querry)-1] = ' ';
		//ajoute la clause
		$querry .= " WHERE id='$id_equip'";
	if ($modif != 0){
		if ($logged_level >= 3)
			$stmt = $pdo->prepare($querry);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}//end if modif
	else {
		echo 'Aucune modif a faire';
		echo '<br /><br /><a href="team-list.php?highlight='.$id_equip.'#item'.$id_equip.'">Suite</a><br /><br />';
		pied_page();
		exit();
		} // else end
	} // end if connect

redirect('team-list.php?highlight='.$id_equip.'#item'.$id_equip);
?>
