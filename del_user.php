<?php

//del_user.php

// Authenticate
include("session_auth.php");

if (!auth(3))
 Header("Location: login.php");
require("html_functions.php");

en_tete("Suppression Utilisateur");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$valid= $_GET[ok];
$id_user = $_GET[id];
if (empty($id_user))
 Header( "Location : list_user.php");

echo "user:".$id_equip. " ok :".$valid."<br />";

if (!isset($valid) || empty($valid) || $valid=="no"){
 echo "Sur de supprimer l'utilisateur ".$user_id. " ?<br />";
 echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$user_id."&ok=yes\">OUI</a><br />";
  echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";
 
}
else{
if ( $connex = connect_db() ){


 
  //on supprime cet user
  
  $querry = "DELETE LOW_PRIORITY FROM users WHERE id=$user_id LIMIT 1";
$result = mysql_query($querry);
   //
   if (!$result){
   //inscription !ok
   $erreur = mysql_error();
   echo "<br />erreur :".$erreur;
 
 }
else 
 echo "Utilisateur ".$user_id." supprimé!<br />"; 
//on retourne a la page precedente
  echo "<a href=\"list_user.php\">Suite</a><br />";
}  
 
}

?>
<?php pied_page() ?>
</body>
</html>
