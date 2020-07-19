<?php

//del_proj.php

// Authenticate
require_once('auth-functions.php');
require_once('html-functions.php');

if (!auth(3))
 Header("Location: login.php");

en_tete('Suppression Projet');

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$valid= $_GET['ok'];

$id_manip = $_GET['idm'];
if (empty($id_manip))
 Header("Location: manip_maint.php");
$id_proj = $_GET['idp'];
if (empty($id_proj))
 Header("Location: manip_maint.php");

echo "Projet:".$id_proj. " ok :".$valid."<br />";

if (!isset($valid) || empty($valid) || $valid=="no"){
 echo "Sur de supprimer le projet ".$id_proj. " &agrave; la manip ".$id_manip." et toutes ses taches?<br />";
 echo "<a href=\"".$_SERVER[PHP_SELF]."?idm=".$id_manip."&idp=".$id_proj."&ok=yes\">OUI</a><br />";
  echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";

}
else{
if ( $pdo = connect_db() ){

 // on supprime toutes les taches liees a ce projet
 $sql = 'DELETE LOW_PRIORITY FROM tache WHERE projet = ?';
    // $result = mysql_query($querry);
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_proj));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
 if ($result){
  //on supprime ce projet
  $sql = 'DELETE LOW_PRIORITY FROM projet WHERE id = ? LIMIT 1';
  // $result = mysql_query($querry);
  $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_proj));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
 }

   if (!$result){
   //inscription !ok
  //  $erreur = mysql_error();
   echo "<br />erreur ";

 }
else
 echo "Projet ".$id_proj." supprim&eacute;, ainsi que toutes ses taches!<br />";
//on retourne a la page precedente
  echo "<a href=\"manip_maint.php?id=".$id_manip."\">Suite</a><br />";
}
}

?>
<?php pied_page() ?>
