<?php
// team-update.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('team-list.php');
level_or_alert(3, 'Modification d\'une &eacute;quipe');

$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

unset($erreur);

$team_id = param_post('id_equip', 0);
$nom     = param_post('nom');
$compte  = param_post('compte');
$chef    = param_post('chef');
$descr   = param_post('descr');
//variables ne pouvant etre nulles
if ($team_id == 0)
	$erreur = 'Id non pr&eacute;cis&eacute;';
if (empty($nom))
	$erreur = 'Nom d\'&eacute;quipe non pr&eacute;cis&eacute;';
if (empty($compte))
	$erreur = 'Compte non pr&eacute;cis&eacute;';

en_tete('R&eacute;sultat modification');

if (!empty($erreur) ){
	//erreur
	echo '<br />Erreur : '.$erreur;
	echo '<br /><a href="team-add.php?id='.$team_id.'">Suite</a><br />'.PHP_EOL;
	pied_page();
	exit();
}

$pdo = connect_db_or_alert();

//recupere les anciennes caracteristiques
$team_selected = get_team_all_by_id($pdo, $team_id);

echo $nom." ".$team_selected['nom']."<br />";
echo $descr." ".$team_selected['descr']."<br />";
echo $compte." ".$team_selected['compte']."<br />";
echo $chef." ".$team_selected['chef']."<br />";

//modification equip
$modif = 0;
//on construit la demande
$querry = 'UPDATE LOW_PRIORITY equipe SET ';
	if ($nom != $team_selected['nom']){
		//modif du nom
		$modif = 1;
		$querry .= "nom='$nom',";
	}
	if ($descr != $team_selected['descr']){
		//modif de la descr
		$modif = 1;
		$querry .= "descr='$descr',";
	}
	if ($compte != $team_selected['compte']){
		//modif du compte
		$modif = 1;
		$querry .= "compte='$compte',";
	}
	if ($chef != $team_selected['chef']){
		//modif du chef
		$modif = 1;
		$querry .= "chef='$chef',";
	}
	// supprime la derniere virgule
	$querry[strlen($querry)-1] = ' ';
	//ajoute la clause
	$querry .= " WHERE id='$team_id'";
if ($modif != 0){
	if ($logged_level >= 3)
		$stmt = $pdo->prepare($querry);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}//end if modif
else {
	echo 'Aucune modif a faire';
	echo '<br /><br /><a href="team-list.php?highlight='.$team_id.'#item'.$team_id.'">Suite</a><br /><br />';
	pied_page();
	exit();
	} // else end

redirect('team-list.php?highlight='.$team_id.'#item'.$team_id);
?>
