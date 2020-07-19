<?php

//del_task.php

// Authenticate
include("auth-functions.php");

if (!auth(3))
 Header("Location: login.php");

require("html-functions.php");

en_tete('Suppression T&acirc;che');

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$valid= $_GET['ok'];

$id_manip = $_GET['idm'];
if (empty($id_manip))
 Header("Location: manip_maint.php");
$id_task = $_GET['idt'];
if (empty($id_task))
 Header("Location: manip_maint.php");

echo "tache:".$task_id. " ok :".$valid."<br />";

if (!isset($valid) || empty($valid) || $valid=="no"){
 echo "Sur de supprimer la tache ".$id_task. " &agrave; la manip ".$id_manip." ?<br />";
 echo "<a href=\"".$_SERVER[PHP_SELF]."?idm=".$id_manip."&idt=".$id_task."&ok=yes\">OUI</a><br />";
  echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";

}
else{

if ( $pdo = connect_db() ){

  //on supprime cette tache

  $sql = 'DELETE LOW_PRIORITY FROM tache WHERE id = ? LIMIT 1';
  ///query_db($querry);
  // $result = mysql_query($querry);
  $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_task));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   //
   if (!$result){
   //inscription !ok
  //  $erreur = mysql_error();
   echo "<br />erreur ";

 }
else
 echo "T&acirc;che ".$task_id." supprim&eacute;!<br />";
//on retourne a la page precedente
  echo "<a href=\"manip_maint.php?id=".$id_manip."\">Suite</a><br />";
}}
?>
<?php pied_page() ?>
