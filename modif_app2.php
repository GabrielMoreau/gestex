 <?php
/// modif_app2.php

// Authenticate
//include("session_auth.php");

//if (!auth(3))
	//Header("Location: login.php");

//$logged_in_user = strtolower($_SESSION['logged_in_user']);

require("mise_en_page.php");
require ("db_functions.php");



//modification d'un appareil
unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST[id_app]))
	$erreur="id non pr&eacute;cis&eacute;";	
else{
	$id_app =$_POST[id_app];

if (empty($_POST[categorie]))
	$erreur="categorie non pr&eacute;cis&eacute;";	
else{
	$categorie =$_POST[categorie];

if (empty($_POST[nom]))
	$erreur="nom non pr&eacute;cis&eacute;";	
else{
	$nom =$_POST[nom];
	if (empty($_POST[modele]))
		$erreur="Modele non pr&eacute;cis&eacute;";	
	else{
		$modele=$_POST[modele];
$gamme=$_POST[gamme];

		if (empty($_POST[equipe]))
			$erreur="equipe non pr&eacute;cis&eacute;";	
		else{
			$equipe =$_POST[equipe];
			if (empty($_POST[tech]))
				$erreur="tech non pr&eacute;cis&eacute;";	
			else{
				$tech =$_POST[tech];
				if (empty($_POST[fourn]))
					$erreur="fournisseur non pr&eacute;cis&eacute;";	
				else{
					$fourn =$_POST[fourn];

							//variables pouvant etre nulles
					$achat = $_POST[achat];
					$reparation=$_POST[reparation];
	$accessoires=$_POST[accessoires];
$inventaire=$_POST[inventaire];
$notice=$_POST[notice];
							
}}}}}}}

en_tete("resultat modification appareil");

$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

$cat=$_GET[categorie];
echo "$cat";
//récupère la catégorie de le page ajout appareil 


if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_app2.php?id=".$id_app ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM Listing WHERE id='$id_app'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

/*
echo $nom." ".$data['nom']."<br />";
echo $modele." ".$data['modele']."<br />";
echo $compte." ".$data['compte']."<br />";
echo $chef." ".$data['chef']."<br />";*/

		//modification app
$modif=0;
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY Listing SET ";

if ($categorie!=$data['categorie']){
			//modif de la categorie
			$modif=1;
			$querry.="categorie='$categorie',";
	}

		if ($nom!=$data['nom']){
			//modif du nom
			$modif=1;
			$querry.="nom='$nom',";
		}
		if ($modele!=$data['modele']){
			//modif du modele
			$modif=1;
			$querry.="modele='$modele',";
		}

if ($gamme!=$data['gamme']){
			//modif de la gamme
			$modif=1;
			$querry.="gamme='$gamme',";
		}

		if ($tech!=$data['tech']){
			//modif du tech
			$modif=1;
			$querry.="responsable='$tech',";
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
		if ($reparation!=$data['reparation']){
			//modif de reparation
			$modif=1;
			$querry.="reparation='$reparation',";
		}
if ($accessoires!=$data['accessoires']){
			//modif de accessoires
			$modif=1;
			$querry.="accessoires='$accessoires',";
		}
if ($inventaire!=$data['inventaire']){
			//modif de inventaire
			$modif=1;
			$querry.="inventaire='$inventaire',";
		}

if ($notice!=$data['notice']){
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
		$result = mysql_query($querry);
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
			echo "<br />erreur :".$erreur;
		}
	}//end if modif
	else{
		echo "aucune modif a faire";
		echo"<br /><br /><a href=\"instru.php\">Suite</a><br /><br />\n";
		pied_page();
		exit();
		}//else end
	}//end if connect

////en_tete("modification appareil Valid&eacute;e");


if ( $connex = connect_db() ){
	// recupere la liste de appareils


$querry = "SELECT * FROM categorie where id='$cat'" ;
	list($qh,$num) = query_db($querry);
	$last_id=0;
$datax = result_db($qh);}

echo "$cat";
echo "<br />modification de ".$nom."<br />";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"instru.php?categorie=".$datax[id]."\">Suite</a><br /><br />\n";
//quand on va sur suite, on retourne sur la page de la categorie choisie



pied_page();
exit();
}

?>
