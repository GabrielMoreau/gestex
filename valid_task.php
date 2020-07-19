<?php

require_once('html-functions.php');

/// valid_task.php
//validation d'une nouvelle task

unset($erreur); unset($nom);
//variables ne pouvant etre nulles

if (empty($_POST[id_manip]))
 $erreur="manip non pr&eacute;cis&eacute;";
else{
 $manip_id =$_POST[id_manip];
if (empty($_POST[id_proj]))
 $erreur="projet non pr&eacute;cis&eacute;";
else{
 $proj_id =$_POST[id_proj];
 if (empty($_POST[nom]))
  $erreur="nom non pr&eacute;cis&eacute;";
 else{
  $nom =$_POST[nom];
     $descr=$_POST[descr];
       //variables pouvant etre nulles

       $date =$_POST[date];
       //plusieurs fournisseurs possible
     $fourn = array_values($_POST['fourn'] );

}}}

en_tete('R&eacute;sultat inscription');

if (!empty($erreur) ){

 //erreur

 echo "<br />erreur :".$erreur;
 echo"<br /><a href=\"add_task.php?idm=".$manip_id."&idp=".$proj_id."\">Suite</a><br />\n";

 pied_page();
 exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit
require_once('db-functions.php');

if ( $connex = connect_db() ){
 // chaine :liste des fournissuers separes par des ,
 $fournisseurs = implode("," , $fourn);
 //ajout de la tache
 $querry = "INSERT INTO tache (projet,nom,descr,date,fourniss)".
   " VALUES ('$proj_id', '$nom',  '$descr', '$date', '$fournisseurs')";
  $result = mysql_query($querry);
   //

   if (!$result){
   //inscription !ok
   $erreur = mysql_error();
  echo "<br />erreur :".$erreur;
  echo"<br /><br /><a href=\"add_task.php\">Suite</a><br /><br />\n";
  }
  else{ //result=ok

  $result = mysql_query("SELECT nom FROM manip WHERE id='$manip_id'");
 $nom_manip = mysql_result($result,0);
  $result = mysql_query("SELECT nom FROM projet WHERE id='$proj_id'");
 $nom_projet = mysql_result($result,0);

//ajout d'un repertoire dans data/nom_manip/nom_projet
 $dossier = "data/".$nom_manip;
 $dossier = str_replace(" ", "_", $dossier );
 if (!is_dir ($dossier) )
  //data/nom_manip n'existe pas
  mkdir ($dossier);
$dossier .="/". $nom_projet;
 $dossier = str_replace(" ", "_", $dossier );
 if (!is_dir ($dossier) )
   //data/nom_manip/nom_projet n'existe pas
   mkdir ($dossier);
// creation du repertoire associe a cette tache
$dossier .= "/".$nom;
//remplace les espaces par des underscore
 $dossier = str_replace(" ", "_", $dossier );
mkdir($dossier);

echo "ajout de la tache ".$nom." au projet ".$nom_proj. "(manip : ".$nom_manip.")<br />";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"manip_maint.php?id=".$manip_id."\">Suite</a><br /><br />\n";
  }
 }//end if connect

pied_page();
exit();
}//else end

?>
