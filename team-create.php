<?php
// team-create.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('team-del.php');
level_or_alert(3, 'Suppression d\'une &eacute;quipe');

//validation d'une nouvelle equipe

unset($erreur);
unset($loggin);
unset($password);
unset($password2);
unset($nom);

//variables ne pouvant etre nulles

if (empty($_POST['nom']))
	$erreur = 'Nom d\'&eacute;quipe non pr&eacute;cis&eacute;';
else {
	$nom = $_POST['nom'];
	if (empty($_POST['compte']))
		$erreur = 'Compte non pr&eacute;cis&eacute;';
	else {
		$compte = $_POST['compte'];
		$descr = $_POST['descr'];
		//variables pouvant etre nulles
		$chef = $_POST['chef'];
	}
}

en_tete('R&eacute;sultat inscription');

if (!empty($erreur)) {
	//erreur
	echo '<br />Erreur : '.$erreur;
	echo '<br /><a href="team-add.php">Suite</a><br />'.PHP_EOL;
	pied_page();
	exit();
}


if ($pdo = connect_db()) {
	list($id_team, $err_msg) = set_team_new($pdo, $nom, $descr, $compte, $chef);

	echo 'Ajout de '.$nom.' valid&eacute;<br />';
	echo '<br><br><a href="team-list.php?highlight='.$id_team.'#item'.$id_team.'">Suite</a><br><br>';
}
?>

<?php pied_page() ?>
