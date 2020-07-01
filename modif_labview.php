 <?php
/// modif_labview.php

// Authenticate
//include("session_auth.php");

//if (!auth(3))
	//Header("Location: login.php");

//$logged_in_user = strtolower($_SESSION['logged_in_user']);

require("html_functions.php");
require ("db_functions.php");

//modification d'une manip labview
unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST[id_app]))
	$erreur="id non pr&eacute;cis&eacute;";
else{
	$id_app =$_POST[id_app];

if (empty($_POST[manipch]))
	$erreur="manipch non pr&eacute;cis&eacute;";
else{
	$manipch =$_POST[manipch];

if (empty($_POST[technicien]))
	$erreur="d&eacute;veloppeur non pr&eacute;cis&eacute;";
else{
	$technicien =$_POST[technicien];

		$localisation=$_POST[localisation];

		if (empty($_POST[matos]))
			$erreur="mat&eacute;riel non pr&eacute;cis&eacute;";
		else{
			$matos =$_POST[matos];

			if (empty($_POST[code]))
				$erreur="descriptif du code non pr&eacute;cis&eacute;";
			else{
				$code =$_POST[code];

					$driver = $_POST[driver];

					$module = $_POST[module];

					$ecran = $_POST[ecran];

					$pdf = $_POST[pdf];

}}}}}

en_tete("resultat modification labview");

$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

$cat=$_GET[categorie];
echo "$cat";
//recupere la categorie de la page ajout appareil

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_labview.php?id=".$id_app ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM labview WHERE id='$id_app'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

		//modification manip Labview
$modif=0;
//on construit la demande
	$querry = "UPDATE LOW_PRIORITY labview SET ";

if ($manipch!=$data['manipch']){
			//modif de la manip
			$modif=1;
			$querry.="manipch='$manipch',";
	}

		if ($technicien!=$data['technicien']){
			//modif du developpeur
			$modif=1;
			$querry.="technicien='$technicien',";
		}
		if ($localisation!=$data['localisation']){
			//modif de la salle de manip
			$modif=1;
			$querry.="localisation='$localisation',";
		}

if ($matos!=$data['matos']){
			//modif du materiel
			$modif=1;
			$querry.="matos='$matos',";
		}

		if ($code!=$data['code']){
			//modif du code
			$modif=1;
			$querry.="code='$code',";
		}
		if ($driver!=$data['driver']){
			//modif des drivers
			$modif=1;
			$querry.="driver='$driver',";
		}

		if ($module!=$data['module']){
			//modif des modules labview
			$modif=1;
			$querry.="module='$module',";
		}

		if ($ecran!=$data['ecran']){
			//modif des impressions ecran
			$modif=1;
			$querry.="ecran='$ecran',";
		}
		if ($module!=$data['pdf']){
			//modif des docs pdf
			$modif=1;
			$querry.="pdf='$pdf',";
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
		echo"<br /><br /><a href=\"labview.php\">Suite</a><br /><br />\n";
		pied_page();
		exit();
		}//else end
	}//end if connect

if ( $connex = connect_db() ){

	//recupere les anciennes caracteristiques

	$querry="SELECT * FROM labview order by '$tri'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);}

//echo "<br />modification de ".$nom."<br />";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"labview.php\">Suite</a><br /><br />\n";
//quand on va sur suite, on retourne sur la page de la categorie choisie

pied_page();
exit();
}

?>
