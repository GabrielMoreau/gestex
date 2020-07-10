<?php
/// modif_equip.php

// Authenticate
include("session_auth.php");

if (!auth(3))
	Header("Location: login.php");

$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

//modification d'une equipe

unset($erreur);
//variables ne pouvant etre nulles
if (empty($_POST['id_equip']))
	$erreur="id non pr&eacute;cis&eacute;";
else {
	$id_equip=$_POST['id_equip'];

	if (empty($_POST['nom']))
		$erreur="nom non pr&eacute;cis&eacute;";
	else {
		$nom=$_POST['nom'];
		if (empty($_POST['compte']))
			$erreur="compte non pr&eacute;cis&eacute;";
		else {
			$compte=$_POST['compte'];
			$chef=$_POST['chef'];
			$descr =$_POST['descr'];

}}}

en_tete('R&eacute;sultat modification');

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add_equip.php?id=".$id_equip ."\" >Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

if ( $pdo = connect_db() ){

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
$modif=0;
//on construit la demande
	$querry = 'UPDATE LOW_PRIORITY equipe SET ';
		if ($nom!=$equipe[0]['nom']){
			//modif du nom
			$modif=1;
			$querry.="nom='$nom',";
		}
		if ($descr!=$equipe[0]['descr']){
			//modif de la descr
			$modif=1;
			$querry.="descr='$descr',";
		}
		if ($compte!=$equipe[0]['compte']){
			//modif du compte
			$modif=1;
			$querry.="compte='$compte',";
		}
		if ($chef!=$equipe[0]['chef']){
			//modif du chef
			$modif=1;
			$querry.="chef='$chef',";
		}
		// supprime la derniere virgule
		$querry[strlen($querry)-1]=' ';
		//ajoute la clause
		$querry.=" WHERE id='$id_equip'";
	if ($modif!=0){
		if ($user_level>= 3)
			$stmt = $pdo->prepare($querry);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}//end if modif
	else{
		echo "aucune modif a faire";
		echo"<br /><br /><a href=\"list_equip.php\">Suite</a><br /><br />\n";
		pied_page();
		exit();
		}//else end
	}//end if connect

////en_tete('modification &eacute;quipe Valid&eacute;e');
Header("Location: list_equip.php");
// echo"<br /><br /><a href=\"list_equip.php\">Suite</a><br /><br />\n";
pied_page();
exit();
}

?>
