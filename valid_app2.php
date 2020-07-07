<?php

require("html_functions.php");
//recuper la methode de tri

/// valid_app2.php
//validation d'un nouvel appareil
unset($erreur);
//variables ne pouvant etre nulles

if (empty($_POST['categorie']))
	$erreur="Cat&eacute;gorie non pr&eacute;cis&eacute;";
else{
	$categorie =$_POST['categorie'];

if (empty($_POST['nom']))
	$erreur="Nom de famille non pr&eacute;cis&eacute;";
else{
	$nom =$_POST['nom'];

	if (empty($_POST['modele']))
		$erreur="Mod&egrave;le non pr&eacute;cis&eacute;";
	else{
		$modele=$_POST['modele'];

if (empty($_POST['gamme']))
		$erreur="Gamme non pr&eacute;cis&eacute;";
	else{

		$gamme=$_POST['gamme'];

		if (empty($_POST['equipe']))
			$erreur="&Eacute;quipe non pr&eacute;cis&eacute;";
		else{
			$equipe =$_POST['equipe'];

			if (empty($_POST['fourn']))
				$erreur="Fournisseur non pr&eacute;cis&eacute;";
			else{
				$fourn =$_POST['fourn'];

							//variables pouvant etre nulles

if (empty($_POST['achat']))
		$erreur="Achat non pr&eacute;cis&eacute;";
	else{
		$achat=$_POST['achat'];

if (empty($_POST['tech']))
				$erreur="Tech non pr&eacute;cis&eacute;";
			else{
				$tech =$_POST['tech'];

				$reparation =$_POST['reparation'];

				$accessoires =$_POST['accessoires'];

$inventaire =$_POST['inventaire'];

$notice =$_POST['notice'];

	}}}}}}}}

en_tete('R&eacute;sultat ajout appareil');

if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];

$cat=$_GET['categorie'];
//echo "$cat";
//recupere la categorie de la page ajout appareil

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_app2.php\">Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit
require("db_functions.php");

// if ( $connex = connect_db() ){
// 		//inscription
// 	$table = "Listing";

// 		$result = mysql_query("INSERT INTO $table ".
// 			"(categorie,nom,modele , gamme, equipe, fournisseur, achat, responsable, reparation,accessoires,inventaire,notice)".
// 			" VALUES ('$categorie','$nom', '$modele','$gamme', '$equipe', '$fourn','$achat','$tech', '$reparation','$accessoires','$inventaire','$notice')");
// 			//
// if (!$result){
// 			//inscription !ok
// 			$erreur = mysql_error();
// 		echo "<br />erreur :".$erreur;
// 		}

// 	}//end if connect


if ( $pdo = connect_db() ){
$sql = 'INSERT INTO Listing (categorie,nom,modele , gamme, equipe, fournisseur, achat, responsable, reparation,accessoires,inventaire,notice) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
$stmt = $pdo->prepare($sql);
$stmt->execute(array($categorie,$nom, $modele,$gamme, $equipe, $fourn,$achat,$tech, $reparation,$accessoires,$inventaire,$notice));
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<br /> Votre requ&ecirc;te a bien &eacute;t&eacute; ajout&eacute;";
}//end if connect

//On enregistre la notice dans le dossier instru/ dans un sous-dossier portant le nom de l'appareil auquel la notice est utile
// if($pdo = connect_db()){
	// $sql = 'SELECT nom FROM appareil WHERE id = ?;';
	// $stmt = $pdo->prepare($sql);
    // $stmt->execute(array($id_appareil));
	// $nom = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$path = "./instru/".$nom;
	$nom_notice=$_FILES["notice"]["name"];
	mkdir($path,0777);
	if(move_uploaded_file($nom_notice, $path )){
		echo "ça a marché";
	}else{
		echo "ça n'a pas marché";
	}
	



// }


echo "<br />ajout de ".$nom." valid&eacute;e";
echo "<br /><br /><a href=\"list_appareil.php\">Suite</a><br /><br />\n";
//quand on va sur suite, on retourne sur la page de la categorie choisie
pied_page();
exit();
}

?>
