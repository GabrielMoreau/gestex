<?php

require("html_functions.php");

/// valid_manip.php
//validation d'une nouvelle manip

unset($erreur); unset($nom);
//variables ne pouvant etre nulles

    if (empty($_POST['nom']))
     $erreur="nom non pr&eacute;cis&eacute;";
    else{
     $nom =$_POST['nom'];
     if (empty($_POST['local']))
      $erreur="Local non pr&eacute;cis&eacute;";
     else{
      $local = $_POST['local'];

       $descr=$_POST['descr'];
       //variables pouvant etre nulles
       $equipe =$_POST['equipe'];
       $chercheur =$_POST['cherch'];
       $chercheur_bis =$_POST['cherch_bis'];
       $date =$_POST['date'];

}}

en_tete('R&eacute;sultat inscription');

if (!empty($erreur) ){

 //erreur

 echo "<br />erreur :".$erreur;
 echo"<br /><a href=\"add_manip.php\">Suite</a><br />\n";

 pied_page();
 exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit
require("db_functions.php");

if ( $pdo = connect_db() ){
  //ajout de la manip
 $sql = "INSERT INTO manip (nom,descr,equipe,chercheur, chercheur_bis,local,date) VALUES (?, ?, ?, ?, ?, ?, ?);";
  // $result = mysql_query($querry);
   //
   $stmt = $pdo->prepare($sql);
	$stmt->execute(array($nom, $descr, $equipe, $chercheur, $chercheur_bis, $local, $date));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if ($result){
   $manip_id = last_id_db($result);
   $nom = "Projet par defaut"; $descr="";
   //insere un projet par defaut
   $sql = "INSERT INTO projet (manip, nom, descr, date) VALUES (?, ?,  ?, ?)";
  //  $result = mysql_query($querry);
  $stmt = $pdo->prepare($sql);
	$stmt->execute(array($manip_id, $nom, $descr, $date));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

   if ($result){
    $proj_id = last_id_db($result);
    //insere un tache par defaut
    $nom = "T&acirc;che par defaut"; $descr="";
    $sql = "INSERT INTO tache (projet, nom, descr, date, fourniss) VALUES (?, ?, ?, ?, 0)";
    // $result = mysql_query($querry);
  $stmt = $pdo->prepare($sql);
	$stmt->execute(array($proj_id, $nom, $descr, $date));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    }
   }
  //  if (!$result){
  //  //inscription !ok
  // //  $erreur = mysql_error();
  // echo "<br />erreur :".$erreur;
  // echo"<br /><br /><a href=\"add_manip.php\">Suite</a><br /><br />\n";
  // }
  // else{ //result=ok
//ajout d'un repertoire dans data
//remplace les espaces par des underscore
 $dossier_proj = str_replace(" ", "_", $nom);
mkdir("data/".$dossier_proj);

echo "inscription de la manip ".$nom."<br />";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"list_manip.php\">Suite</a><br /><br />\n";
  // }
 }//end if connect

pied_page();
exit();
}

?>
