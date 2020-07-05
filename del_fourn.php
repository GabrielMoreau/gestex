<?php

//del_fourn.php

// Authenticate
include("session_auth.php");

if (!auth(2))
 Header("Location: login.php");

require("html_functions.php");

en_tete('Suppression Fournisseur');

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$valid= $_GET[ok];
$id_fourn = $_GET[id];
if (empty($id_fourn))
 Header("Location: list_fourn.php");

if (!isset($valid) || empty($valid) || $valid=="no"){
 echo "Sur de supprimer le Fournisseur ".$id_fourn." ?<br />";
 echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$id_fourn."&ok=yes\">OUI</a><br />";
  echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";

}
else{
if ( $connex = connect_db() ){

// on supprime le fournisseur
 $querry = "DELETE LOW_PRIORITY FROM fournisseurs WHERE id=$id_fourn LIMIT 1";
 $result = mysql_query($querry);
   //
   if (!$result){
   //inscription !ok
   $erreur = mysql_error();
   echo "<br />erreur :".$erreur;

  }
 else
  echo "Fournisseur ".$id_fourn." supprim&eacute;!<br />";

 }//end if connect
//on retourne a la page precedente
  echo "<a href=\"list_fourn.php"\">Suite</a><br />";
} //else end

?>
<?php pied_page() ?>
