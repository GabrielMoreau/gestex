<?php

//del_equip.php

// Authenticate
include("session_auth.php");

if (!auth(3))
 Header("Location: login.php");

require("html_functions.php");

en_tete("Suppression Equipe");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$valid= $_GET[ok];

$id_equip = $_GET[id];
if (empty($id_equip))
 Header( "Location : list_equip.php");

echo "equipe:".$id_equip. " ok :".$valid."<br />";

if (!isset($valid) || empty($valid) || $valid=="no"){
 echo "Sur de supprimer l'équipe ".$id_equip. " ?<br />";
 echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$id_equip."&ok=yes\">OUI</a><br />";
  echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";
 
}
else{
if ( $connex = connect_db() ){

// on supprime le fournisseur
 $querry = "DELETE LOW_PRIORITY FROM equipe WHERE id=$id_equip LIMIT 1";
 $result = mysql_query($querry);
   //
   if (!$result){
   //inscription !ok
   $erreur = mysql_error();
   echo "<br />erreur :".$erreur;
 
 }
else 
 echo "Equipe  ".$id_equip." supprimée!<br />"; 
//on retourne a la page precedente
  echo "<a href=\"list_equip.php\">Suite</a><br />";
} 
}

?>
<?php pied_page() ?>
