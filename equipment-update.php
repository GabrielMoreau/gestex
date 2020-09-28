 <?php
// equipment-update.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('team-list.php');
level_or_alert(3, 'Modification d\'une &eacute;quipe');

//modification d'un appareil
unset($erreur);
//variables ne pouvant etre nulles
$id_app = param_post('id_app');
if (empty($id_app))
	$erreur = "Id non pr&eacute;cis&eacute;";

$categorie = param_post('categorie');
if (empty($categorie))
	$erreur = "Cat&eacute;gorie non pr&eacute;cis&eacute;";

$nom = param_post('nom');
if (empty($nom))
	$erreur = "Nom non pr&eacute;cis&eacute;";

$modele = param_post('modele');
if (empty($modele))
	$erreur = "Modele non pr&eacute;cis&eacute;";

$equipe = param_post('equipe');
if (empty($equipe))
	$erreur = "&Eacute;quipe non pr&eacute;cis&eacute;";

$tech = param_post('tech');
if (empty($tech))
	$erreur = "Tech non pr&eacute;cis&eacute;";

$fourn = param_post('fourn');
if (empty($fourn))
	$erreur = "Fournisseur non pr&eacute;cis&eacute;";

//variables pouvant etre nulles
$gamme       = param_post('gamme');
$achat       = param_post('achat'];
$reparation  = param_post('reparation'];
$accessoires = param_post('accessoires'];
$inventaire  = param_post('inventaire'];
$barcode     = param_post('barcode'];
$loanable    = param_post('loanable'];

$notice = $_FILES["notice"]["name"];
$notice = str_replace(' ', '_', $notice);
$notice = str_replace('é', 'e', $notice);
$notice = str_replace('è', 'e', $notice);
$notice = str_replace('à', 'a', $notice);

/*	$path = "./data";
	if(!is_dir($path)){	
		mkdir($path,0750);
	}
	$path = "./data/notice";
	if(!is_dir($path)){	
		mkdir($path,0750);
	}
	$path = "./data/notice/".$id_app;
	if(!is_dir($path)){	
		mkdir($path,0750);
	}
	if(move_uploaded_file($_FILES["notice"]["tmp_name"], $path."/".$notice )){
		echo "Ca a march&eacute;\n";
	}else{
		echo "Ca n'a pas march&eacute;\n ";
	} */

en_tete('R&eacute;sultat modification appareil');

$cat=$_GET['categorie'];
echo "$cat";
//recupere la categorie de la page ajout appareil

if (!empty($erreur)) {
	//erreur
	echo "<br />Erreur :".$erreur;
	echo"<br /><a href=\"equipment-add.php?id=".$id_app ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}

// tout est ok
if ($pdo = connect_db()) {

	//recupere les anciennes caracteristiques

	$sql = 'SELECT * FROM Listing WHERE id = ?';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_app));
	$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//modification app
	$modif = 0;
	//on construit la demande
	$querry = 'UPDATE LOW_PRIORITY Listing SET ';

	if ($categorie != $listing[0]['categorie']) {
		//modif de la categorie
		$modif = 1;
		$querry .= "categorie='$categorie',";
	}

	if ($nom != $listing[0]['nom']) {
		//modif du nom
		$modif = 1;
		$querry .= "nom='$nom',";
	}

	if ($modele != $listing[0]['modele']) {
		//modif du modele
		$modif = 1;
		$querry .= "modele='$modele',";
	}

	if ($gamme != $listing[0]['gamme']) {
		//modif de la gamme
		$modif = 1;
		$querry .= "gamme='$gamme',";
	}

	if ($tech != $listing[0]['responsable']) {
		//modif du tech
		$modif = 1;
		$querry .= "responsable='$tech',";
	}
	if ($equipe != $listing[0]['equipe']) {
		//modif de l'equipe
		$modif = 1;
		$querry .= "equipe='$equipe',";
	}
	if ($fourn != $listing[0]['fournisseur']) {
		//modif du fourn
		$modif = 1;
		$querry .= "fournisseur='$fourn',";
	}

	if ($achat != $listing[0]['achat']) {
		//modif de l'achat
		$modif = 1;
		$querry .= "achat='$achat',";
	}

	if ($reparation != $listing[0]['reparation']) {
		//modif de reparation
		$modif = 1;
		$querry .= "reparation='$reparation',";
	}

	if ($accessoires != $listing[0]['accessoires']) {
		//modif de accessoires
		$modif = 1;
		$querry .= "accessoires='$accessoires',";
	}

	if ($inventaire != $listing[0]['inventaire']) {
		//modif de inventaire
		$modif = 1;
		$querry .= "inventaire='$inventaire',";
	}

	if ($notice != $listing[0]['notice']) {
		//modif de notice
		$modif = 1;
		$querry .= "notice='$notice',";
	}

	// supprime la derniere virgule
	$querry[strlen($querry)-1]=' ';
	//ajoute la clause
	$querry.=" WHERE id='$id_app'";
	if ($modif != 0) {
		$stmt = $pdo->prepare($querry);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// $sql = 'INSERT INTO notice (nom_notice,chemin_notice,id_appareil) VALUES (?, ?, ?);';
		// $stmt = $pdo->prepare($sql);
		// $path_complet =$path."/".$notice;
		// $stmt->execute(array($notice,$path_complet,$listing[0]['id']));
	} // end if modif
	else {
		echo "Aucune modification &agrave; faire";
		echo "<br /><br /><a href=\"equipment-list.php\">Suite</a><br /><br />\n";
		pied_page();
		exit();
	} // else end
} // end if connect

// quand on va sur suite, on retourne sur la page de la categorie choisie
redirect('equipment-list.php');
?>

<?php pied_page() ?>
