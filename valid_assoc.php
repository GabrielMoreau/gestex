<?php
 // Authenticate
 include("session_auth.php");

 if (!auth(2))
  Header("Location: login.php");

 $user_id = $_SESSION['user_id'];
 $logged_in_user = strtolower($_SESSION['logged_in_user']);

require("html_functions.php");

/// valid_assoc.php
//validation de l'assoc' d'un projet annexe a une manip

unset($erreur); unset($nom);
//variables ne pouvant etre nulles
if (empty($_POST[id_manip]))
 $erreur="id manip non pr&eacute;cis&eacute;";
else {
 $id_manip=$_POST[id_manip];
    if (empty($_POST[assoc_p]))
     $erreur="aucune association pr&eacute;cis&eacute;"; 
   else 
 $assoc_p= $_POST[assoc_p];
}

en_tete("resultat association projet annexe");



if (!empty($erreur) ){

 //erreur

 echo "<br />erreur :".$erreur;
 echo"<br /><a href=\"manip_maint.php?id=".$id_manip."\">Suite</a><br />\n";

 pied_page();
 exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

$proj_id=0;

if ( $connex = connect_db() ){
  //ajout du projet
 $querry = "UPDATE manip SET assoc_proj=".$assoc_p ." WHERE id=".$id_manip;
  $result = mysql_query($querry);
   //
 
  
   if (!$result){
   //inscription !ok
   $erreur = mysql_error();
  echo "<br />erreur :".$erreur;
  echo"<br /><br /><a href=\"manip_maint.php?id=".$id_manip."\">Suite</a><br /><br />\n";
  }

else{

echo "association du(des) projet(s) annexe(s) ".$assoc_p." a la manip ".$nom_manip."<br />";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"manip_maint.php?id=".$id_manip."\">Suite</a><br /><br />\n";
  }
 }//end if connect

pied_page();
exit();
}

?>
