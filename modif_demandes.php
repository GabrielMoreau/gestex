 <?php
/// modif_demandes.php

// Authenticate
//include("session_auth.php");

//if (!auth(3))
	//Header("Location: login.php");

//$logged_in_user = strtolower($_SESSION['logged_in_user']);

require("html_functions.php");
require ("db_functions.php");

//modification d'une demande
unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST['id_app']))
	$erreur="id non pr&eacute;cis&eacute;";
else{
	$id_app =$_POST['id_app'];

if (empty($_POST['tache']))
	$erreur="tache non pr&eacute;cis&eacute;";
else{
	$tache =$_POST['tache'];

if (empty($_POST['nomdemandeur']))
	$erreur="nomdemandeur non pr&eacute;cis&eacute;";
else{
	$nomdemandeur =$_POST['nomdemandeur'];

	if (empty($_POST['details']))
		$erreur="details non pr&eacute;cis&eacute;";
	else{
		$details=$_POST['details'];

		if (empty($_POST['achat']))
			$erreur="achat non pr&eacute;cis&eacute;";
		else{
			$achat =$_POST['achat'];

			if (empty($_POST['avancement']))
				$erreur="avancement non pr&eacute;cis&eacute;";
			else{
				$avancement =$_POST['avancement'];

							//variables pouvant etre nulles
					$termine = $_POST['termine'];

					$piecesjointes = $_POST['piecesjointes'];

}}}}}}

en_tete('R&eacute;sultat modification demandes');

$tri = $_GET['tri'];
if (empty($tri))
	$tri ="id";

$cat=$_GET['categorie'];
echo "$cat";
//recupere la categorie de la page ajout appareil

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_demandes.php?id=".$id_app ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM demandes WHERE id='$id_app'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

/*
echo $nom." ".$data['nom']."<br />";
echo $modele." ".$data['modele']."<br />";
echo $compte." ".$data['compte']."<br />";
echo $chef." ".$data['chef']."<br />";*/

		//modification demandes
$modif=0;
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY demandes SET ";

if ($tache!=$data['tache']){
			//modif de la tache
			$modif=1;
			$querry.="tache='$tache',";
	}

		if ($nomdemandeur!=$data['nomdemandeur']){
			//modif du nom
			$modif=1;
			$querry.="nomdemandeur='$nomdemandeur',";
		}
		if ($details!=$data['details']){
			//modif du detail
			$modif=1;
			$querry.="details='$details',";
		}

if ($achat!=$data['achat']){
			//modif de l'achat
			$modif=1;
			$querry.="achat='$achat',";
		}

		if ($avancement!=$data['avancement']){
			//modif de l'avancement
			$modif=1;
			$querry.="avancement='$avancement',";
		}
		if ($termine!=$data['termine']){
			//modif des termine
			$modif=1;
			$querry.="termine='$termine',";
		}

		if ($piecesjointes!=$data['piecesjointes']){
			//modif des pieces jointes
			$modif=1;
			$querry.="piecesjointes='$piecesjointes',";
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
		echo"<br /><br /><a href=\"demandes.php\">Suite</a><br /><br />\n";
		pied_page();
		exit();
		}//else end
	}//end if connect

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM demandes WHERE id='$id_app'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);}

//echo "<br />modification de ".$nom."<br />";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"demandes.php\">Suite</a><br /><br />\n";
//quand on va sur suite, on retourne sur la page de la categorie choisie

pied_page();
exit();
}

?>
