<?php

//del_proj.php

// Authenticate
include("session_auth.php");

if (!auth(3))
 Header("Location: login.php");

require("html_functions.php");

en_tete("Suppression Projet");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$valid= $_GET[ok];

$id_manip = $_GET[idm];
if (empty($id_manip))
 Header( "Location : manip_maint.php");
$id_proj = $_GET[idp];
if (empty($id_proj))
 Header( "Location : manip_maint.php");

echo "Projet:".$id_proj. " ok :".$valid."<br />";

if (!isset($valid) || empty($valid) || $valid=="no"){
 echo "Sur de supprimer le projet ".$id_proj. " à la manip ".$id_manip." et toutes ses taches?<br />";
 echo "<a href=\"".$_SERVER[PHP_SELF]."?idm=".$id_manip."&idp=".$id_proj."&ok=yes\">OUI</a><br />";
  echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";
 
}
else{
if ( $connex = connect_db() ){

 // on supprime toutes les taches liées a ce projet
 $querry = "DELETE LOW_PRIORITY FROM tache WHERE projet=$id_proj";
    $result = mysql_query($querry);
 if ($result){
  //on supprime ce projet
  $querry = "DELETE LOW_PRIORITY FROM projet WHERE id=$id_proj LIMIT 1";  
  $result = mysql_query($querry);
 }

   if (!$result){
   //inscription !ok
   $erreur = mysql_error();
   echo "<br />erreur :".$erreur;
 
 }
else 
 echo "Projet ".$id_proj." supprimé, ainsi que toutes ses taches!<br />"; 
//on retourne a la page precedente
  echo "<a href=\"manip_maint.php?id=".$id_manip."\">Suite</a><br />";
}
} 

?>
<?php pied_page() ?>
