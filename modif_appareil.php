 <?php
/// modif_appareil.php

// Authenticate
//include("session_auth.php");

//if (!auth(3))
	//Header("Location: login.php");

//$logged_in_user = strtolower($_SESSION['logged_in_user']);

require("html_functions.php");
require ("db_functions.php");

//modification d'un appareil
unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST['id_app']))
	$erreur="id non pr&eacute;cis&eacute;";
else{
	$id_app =$_POST['id_app'];

if (empty($_POST['categorie']))
	$erreur="categorie non pr&eacute;cis&eacute;";
else{
	$categorie =$_POST['categorie'];

if (empty($_POST['nom']))
	$erreur="nom non pr&eacute;cis&eacute;";
else{
	$nom =$_POST['nom'];
	if (empty($_POST['modele']))
		$erreur="Modele non pr&eacute;cis&eacute;";
	else{
		$modele=$_POST['modele'];
$gamme=$_POST['gamme'];

		if (empty($_POST['equipe']))
			$erreur="equipe non pr&eacute;cis&eacute;";
		else{
			$equipe =$_POST['equipe'];
			if (empty($_POST['tech']))
				$erreur="tech non pr&eacute;cis&eacute;";
			else{
				$tech =$_POST['tech'];
				if (empty($_POST['fourn']))
					$erreur="fournisseur non pr&eacute;cis&eacute;";
				else{
					$fourn =$_POST['fourn'];

							//variables pouvant etre nulles
					$achat = $_POST['achat'];
					$reparation=$_POST['reparation'];
	$accessoires=$_POST['accessoires'];
	$inventaire=$_POST['inventaire'];

	$notice=$_FILES["notice"]["name"];
	$notice = str_replace(' ', '_', $notice);
	$notice = str_replace('é', 'e', $notice);
	$notice = str_replace('è', 'e', $notice);
	$notice = str_replace('à', 'a', $notice);

	$path = "./data";
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
	}
	

}}}}}}}

en_tete('R&eacute;sultat modification appareil');

if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];

$cat=$_GET['categorie'];
echo "$cat";
//recupere la categorie de la page ajout appareil

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_appareil.php?id=".$id_app ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $pdo = connect_db() ){

	//recupere les anciennes caracteristiques

	$sql = 'SELECT * FROM Listing WHERE id = ?';
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_app));
	$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);
/*
echo $nom." ".$data['nom']."<br />";
echo $modele." ".$data['modele']."<br />";
echo $compte." ".$data['compte']."<br />";
echo $chef." ".$data['chef']."<br />";*/

		//modification app
$modif=0;
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY Listing SET ";

if ($categorie!=$listing[0]['categorie']){
			//modif de la categorie
			$modif=1;
			$querry.="categorie='$categorie',";
	}

		if ($nom!=$listing[0]['nom']){
			//modif du nom
			$modif=1;
			$querry.="nom='$nom',";
		}
		if ($modele!=$listing[0]['modele']){
			//modif du modele
			$modif=1;
			$querry.="modele='$modele',";
		}

if ($gamme!=$listing[0]['gamme']){
			//modif de la gamme
			$modif=1;
			$querry.="gamme='$gamme',";
		}

		if ($tech!=$listing[0]['responsable']){
			//modif du tech
			$modif=1;
			$querry.="responsable='$tech',";
		}
		if ($equipe!=$listing[0]['equipe']){
			//modif de l'equipe
			$modif=1;
			$querry.="equipe='$equipe',";
		}
		if ($fourn!=$listing[0]['fournisseur']){
			//modif du fourn
			$modif=1;
			$querry.="fournisseur='$fourn',";
		}
		if ($achat!=$listing[0]['achat']){
			//modif de l'achat
			$modif=1;
			$querry.="achat='$achat',";
		}
		if ($reparation!=$listing[0]['reparation']){
			//modif de reparation
			$modif=1;
			$querry.="reparation='$reparation',";
		}
if ($accessoires!=$listing[0]['accessoires']){
			//modif de accessoires
			$modif=1;
			$querry.="accessoires='$accessoires',";
		}
if ($inventaire!=$listing[0]['inventaire']){
			//modif de inventaire
			$modif=1;
			$querry.="inventaire='$inventaire',";
		}

if ($notice!=$listing[0]['notice']){
			//modif de notice
			$modif=1;
			$querry.="notice='$notice',";
		}

		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$id_app'";
	if ($modif!=0){
			//echo $querry;
		// $result = mysql_query($querry);
			//
			$stmt = $pdo->prepare($querry);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

 		if (!$result){
			//inscription !ok
			// $erreur = mysql_error();
			echo "<br />erreur ";
		}
		$sql = 'INSERT INTO notice (nom_notice,chemin_notice,id_appareil) VALUES (?, ?, ?);';
		$stmt = $pdo->prepare($sql);
		$path_complet =$path."/".$notice;
		$stmt->execute(array($notice,$path_complet,$listing[0]['id']));
	
	}//end if modif
	else{
		echo "aucune modif a faire";
		echo"<br /><br /><a href=\"list_appareil.php\">Suite</a><br /><br />\n";
		pied_page();
		exit();
		}//else end
	}//end if connect

////en_tete('modification appareil Valid&eacute;e');

if ( $connex = connect_db() ){
	// recupere la liste de appareils

$sql = 'SELECT * FROM categorie WHERE id = ?;' ;
// 	list($qh,$num) = query_db($querry);
// 	$last_id=0;
// $datax = result_db($qh);
$stmt = $pdo->prepare($sql);
$stmt->execute(array($cat));
$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
}




echo "$cat";
echo "<br />modification de ".$nom."<br />";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"list_appareil.php\">Suite</a><br /><br />\n";
//quand on va sur suite, on retourne sur la page de la categorie choisie

pied_page();
exit();
}

?>
