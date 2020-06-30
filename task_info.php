<?php

//task_info.php

// Authenticate
include("session_auth.php");

if (!auth(1))
 Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

//et le numero de manip
$manip_id=$_GET['idm'];
if (empty($manip_id))
 Header("Location : accueil.php");

//et le numero du projet
$proj_id=$_GET['idp'];
if (empty($proj_id))
 Header("Location : accueil.php");

//et le numero de la tache
$task_id=$_GET['idt'];
if (empty($task_id))
 Header("Location : accueil.php");

require("html_functions.php");

en_tete("Infos Tache ".$task_id);

if ( $connex = connect_db() ){

 // recupere le manip selectionnée
 $querry = "SELECT nom FROM manip WHERE id='$manip_id'";
 list($qh,$num) = query_db($querry);
 $data = result_db($qh);
  echo "<h1>Manip :".$data['nom']."</h1>";

// recupere le projet selectionné
 $querry = "SELECT nom FROM projet WHERE id='$proj_id'";
 list($qh,$num) = query_db($querry);
 $data = result_db($qh);
  echo "<h2>Projet :".$data['nom']."</h2>";

// recupere la tache selectionnée
 $querry = "SELECT * FROM tache WHERE id='$task_id'";
 list($qh,$num) = query_db($querry);
 $tasks = result_db($qh);

// recupere le temps passé pour cette tache
 $total_time=0;
 $users=array();
 $timings=array();
 $querry = "SELECT * FROM temps WHERE id_tache=".$task_id." ORDER BY date";
 list($qht,$numtime) = query_db($querry);

 while( $time = result_db($qht)){
   $total_time += $time[duree];
  //recherche de noms d'users
  $querry = "SELECT nom FROM users WHERE id =".$time[user];
   list($qhn,$numn)= query_db($querry);
  $user = result_db($qhn);
  array_push($users,$user[nom]);
  array_push($timings,$user[nom]);
  array_push($timings,$time[date], $time[duree]);
  if (!empty( $time[remarks]))
   array_push($timings, $time[remarks]);
  else
      array_push($timings, "no rmk");

 }

//fournisseurs -> tableau
$fourn = explode (",",  $tasks[fourniss]);
 ?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">
  <tbody>
 <th> Tache :
 <?php echo $tasks[nom]; ?>
 </th>
 <tr><td> <i>Description :</i> <?php echo $tasks[descr] ?></td></tr>
 <tr><td> <i>Date Debut :</i> <?php echo $tasks[date] ?></td></tr>
 <tr><td> <i>par :</i>
  <?php
 if (count($users) !=0){

  //affiche les noms d'users
  //reset($users);
  while($temp = array_shift($users))
   echo $temp.", ";
  }
  ?> </td></tr>
 <tr><td> <i>Fournisseurs :</i>
 <?php
  //recherche de noms de fournisseurs
 $querry = "SELECT nom FROM fournisseurs WHERE";
 for( $i=0; $i!=count($fourn); $i++){
  if ($i!=0)
   $querry .=" OR";
  $querry .=" id=$fourn[$i]";
  }//end for
 list($qh,$num) = query_db($querry);
 //resultat
 while($data = result_db($qh))
  echo $data['nom'].", ";
 ?> </td></tr>
 <tr><td>
 <i>temps Pass&eacute; :</i> <?php echo $total_time."h (".$numtime." enregistrements)" ?> </td></tr>
 <tr><td>
 <?php
  ///raz du pointeur sur $timings
  ///reset ($timings);

   while ( $temp = array_shift($timings)) {
    /// nom date durée remarques
    echo $temp ."(".array_shift($timings).") ".array_shift($timings)." h : ".array_shift($timings)."<br />";

  } ?>
 </td></tr>
</tbody>
</table>
<?php }//end if connect
?>

<br />
<br />
</div>
<?php pied_page() ?>
