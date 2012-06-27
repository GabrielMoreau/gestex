<?php

//del_task.php

// Authenticate
include("session_auth.php");

if (!auth(3))
 Header("Location: login.php");

require("html_functions.php");

en_tete("Suppression Tache");


$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$valid= $_GET[ok];

$id_manip = $_GET[idm];
if (empty($id_manip))
 Header( "Location : manip_maint.php");
$id_task = $_GET[idt];
if (empty($id_task))
 Header( "Location : manip_maint.php");

echo "tache:".$task_id. " ok :".$valid."<br>";

if (!isset($valid) || empty($valid) || $valid=="no"){
 echo "Sur de supprimer la tache ".$id_task. " à la manip ".$id_manip." ?<br>";
 echo "<a href=\"".$_SERVER[PHP_SELF]."?idm=".$id_manip."&idt=".$id_task."&ok=yes\">OUI</a><br>";
  echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br>";
 
}
else{

if ( $connex = connect_db() ){


 
  //on supprime cette tache
  
  $querry = "DELETE LOW_PRIORITY FROM tache WHERE id=$id_task LIMIT 1";
  ///query_db($querry);
  $result = mysql_query($querry);
   //
   if (!$result){
   //inscription !ok
   $erreur = mysql_error();
   echo "<br>erreur :".$erreur;
 
 }
else 
 echo "Tache ".$task_id." supprimé!<br>"; 
//on retourne a la page precedente
  echo "<a href=\"manip_maint.php?id=".$id_manip."\">Suite</a><br>";
}}
?>
<?php pied_page() ?>
</body>
</html>