<?php

require("html-functions.php");

/// valid_time.php
//validation de temps passe pour une tache
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

 if (empty($_POST[id_task]))
  $erreur="tache non pr&eacute;cis&eacute;";
 else{
  $task_id =$_POST[id_task];

 if (empty($_POST[id_user]))
  $erreur="utilisateur non pr&eacute;cis&eacute;";
 else{
  if ($_POST[id_user] == 1) //admin
   $user_id =$_POST[user];
  else
   $user_id =$_POST[id_user];

  if (empty($_POST[date]))
   $erreur="date non pr&eacute;cis&eacute;";
  else{
   $date =$_POST[date];
   if (empty($_POST[temps]))
    $erreur="temps non pr&eacute;cis&eacute;";
   else{
    $temps= $_POST[temps];
    $remark=$_POST[remark];

}}}}}}

en_tete('R&eacute;sultat ajout de temps');
echo"<center>";

if (!empty($erreur) ){

 //erreur

 echo "<br />erreur :".$erreur;
 echo"<br /><a href=\"add_time.php?idm=".$manip_id."&idp=".$proj_id."&idt=".$task_id."\">Suite</a><br />\n";

 pied_page();
 exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit
require("db-functions.php");

if ( $connex = connect_db() ){

 //ajout du temps a  la tache
 $querry = "INSERT INTO temps (id_tache,date,user,duree, remarks)".
   " VALUES ('$task_id', '$date', '$user_id',  '$temps', '$remark')";
  $result = mysql_query($querry);
   //echo $querry."<br />";

   if (!$result){
   //inscription !ok
   $erreur = mysql_error();
  echo "<br />erreur :".$erreur;
  echo"<br /><br /><a href=\"add_time.php\">Suite</a><br /><br />\n";
  }
  else{ //result=ok
echo "ajout de ".$temps."heures &agrave; la tache ".$task_id."<br />";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"manip_maint.php?id=".$manip_id."\">Suite</a><br /><br />\n";
  }
 }//end if connect

pied_page();
exit();
}//else end

?>
