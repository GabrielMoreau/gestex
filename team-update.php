<?php
// team-update.php
$web_page = true;

// Authenticate
require_once('auth-functions.php');
require_once('html-functions.php');

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
	$sql = 'SELECT * FROM equipe WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equip));
	$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo $nom." ".$equipe[0]['nom']."<br />";
	echo $descr." ".$equipe[0]['descr']."<br />";
	echo $compte." ".$equipe[0]['compte']."<br />";
	echo $chef." ".$equipe[0]['chef']."<br />";

	//modification equip
	$modif = 0;
	//on construit la demande
	$querry = 'UPDATE LOW_PRIORITY equipe SET ';
		if ($nom != $equipe[0]['nom']){
			//modif du nom
			$modif = 1;
			$querry .= "nom='$nom',";
		}
		if ($descr != $equipe[0]['descr']){
			//modif de la descr
			$modif = 1;
			$querry .= "descr='$descr',";
		}
		if ($compte != $equipe[0]['compte']){
			//modif du compte
			$modif = 1;
			$querry .= "compte='$compte',";
		}
		if ($chef!=$equipe[0]['chef']){
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
