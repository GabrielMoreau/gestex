<?php
/// modif_machine.php

// Authenticate
require_once('auth-functions.php');

if (!auth(3))
	Header("Location: login.php");

$logged_in_user = strtolower($_SESSION['logged_in_user']);

require_once('html-functions.php');

//modification d'un appareil
unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST['id_app']))
	$erreur="id non pr&eacute;cis&eacute;";	
else{
	$id_app =$_POST['id_app'];
if (empty($_POST['nom']))
	$erreur="nom non pr&eacute;cis&eacute;";	
else{
	$nom =$_POST['nom'];
	if (empty($_POST['descr']))
		$erreur="Description non pr&eacute;cis&eacute;";	
	else{
		$descr=$_POST['descr'];
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
					$facture=$_POST['facture'];
							
}}}}}}

en_tete('R&eacute;sultat modification appareil');

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_machine.php?id=".$id_app ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $pdo = connect_db() ){

	//recupere les anciennes caracteristiques

	$sql = 'SELECT * FROM appareils WHERE id = ?;';
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_app));
    $appareil = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
echo $nom." ".$data['nom']."<br />";
echo $descr." ".$data['descr']."<br />";
echo $compte." ".$data['compte']."<br />";
echo $chef." ".$data['chef']."<br />";*/

		//modification app
$modif=0;
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY appareils SET ";
		if ($nom!=$appareil[0]['nom']){
			//modif du nom
			$modif=1;
			$querry.="nom='$nom',";
		}
		if ($descr!=$data['descr']){
			//modif de la descr
			$modif=1;
			$querry.="descr='$descr',";
		}
		if ($tech!=$data['tech']){
			//modif du tech
			$modif=1;
			$querry.="teche='$tech',";
		}
		if ($equipe!=$data['equipe']){
			//modif de l'equipe
			$modif=1;
			$querry.="equipe='$equipe',";
		}
		if ($fourn!=$data['fourn']){
			//modif du fourn
			$modif=1;
			$querry.="fournisseur='$fourn',";
		}
		if ($achat!=$data['achat']){
			//modif de l'achat
			$modif=1;
			$querry.="achat='$achat',";
		}
		if ($facture!=$data['facture']){
			//modif de facture
			$modif=1;
			$querry.="facture='$facture',";
		}

		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$id_app'";
	if ($modif!=0){
			//echo $querry;
		// $result = mysql_query($querry);
		$stmt = $pdo->prepare($querry);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
			echo "<br />erreur :".$erreur;
		}
	}//end if modif
	else{
		echo "aucune modif a faire";
		echo"<br /><br /><a href=\"list_machine.php\">Suite</a><br /><br />\n";
		pied_page();
		exit();
		}//else end
	}//end if connect

////en_tete('modification appareil Valid&eacute;e');

echo "<br />".$nom."modifi&eacute; ";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo"  valid&eacute;e !!";
echo"<br /><br /><a href=\"list_machine.php\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
