<?php

//del_manip.php

// Authenticate
include("session_auth.php");

if (!auth(3))
 Header("Location: login.php");

require("html_functions.php");

en_tete('Suppression Manip');

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$valid= $_GET[ok];

$id_manip = $_GET[id];
if (empty($id_manip))
 Header("Location: accueil.php");

echo "Manip:".$id_manip. " ok :".$valid."<br />";

if (!isset($valid) || empty($valid) || $valid=="no"){
 echo "Sur de supprimer la Manip ".$manip_id. " ainsi que tous ses projets et taches ?<br />";
 echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$id_manip."&ok=yes\">OUI</a><br />";
  echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";

}
else{if ( $connex = connect_db() ){
$result=1;
//on cherche tous les projets correspondant a la manip
 $querry="SELECT id FROM projet WHERE manip=$id_manip";
 list($qh,$num) = query_db($querry);
 while ($data = result_db($qh) && $result) {
 //pour chaque projet de la manip
  // on supprime toutes les taches liees a ce projet
  $querry = "DELETE LOW_PRIORITY FROM tache WHERE projet=$data['id']";
    $result = mysql_query($querry);
  //on supprime ce projet
  $querry = "DELETE LOW_PRIORITY FROM projet WHERE manip=$id_manip";
    $result = mysql_query($querry);
 }//end while projet
 if ($result){
  //on supprime la manip
  $querry = "DELETE LOW_PRIORITY FROM manip WHERE id=$id_manip";

    $result = mysql_query($querry);
     }
   if (!$result){
   //inscription !ok
   $erreur = mysql_error();
   echo "<br />erreur :".$erreur;

 }
else
 echo "Manip ".$manip_id." supprim&eacute;e, ainsi que ses projets et taches!<br />";
//on retourne a la page precedente
  echo "<a href=\"accueil.php\">Suite</a><br />";
}}

?>
<?php pied_page() ?>
