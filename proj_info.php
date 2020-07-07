<?php

//proj_info.php

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
 Header("Location: list_manip.php");

//et le numero du projet
$proj_id=$_GET['idp'];
if (empty($proj_id))
 Header("Location: list_manip.php");

require("html_functions.php");

en_tete('Infos Projet');

if ( $connex = connect_db() ){

 // recupere le manip selectionnee
 $querry = "SELECT nom FROM manip WHERE id='$manip_id'";
 list($qh,$num) = query_db($querry);
 $data = result_db($qh);
  echo "<h1>Manip :".$data['nom']."</h1>";
// recupere le projet selectionne
 $querry = "SELECT * FROM projet WHERE id='$proj_id' ";
 list($qh,$num) = query_db($querry);
 $projs = result_db($qh);
?>
<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 95%;" align="center">
  <tbody>
 <tr bgcolor="#f7bb09"><th colspan="3"> Projet :
 <?php echo $projs[nom] ?>
 </th>
 <td> <i>Date Debut :</i>  <?php echo $projs[date] ?></td>
 </tr>
 <tr><td colspan="4"> <i>Description :</i>  <?php echo $projs[descr] ?></td></tr>

<?php
// recupere les taches selectionnees
 $querry = "SELECT * FROM tache WHERE projet='$proj_id' ORDER BY date";
 list($qh2,$num2) = query_db($querry);
 $temps_total = 0;
 while ($taches = result_db($qh2)){
 echo"<tr bgcolor=\"#f7d70\"><th>T&acirc;che : ".$taches[nom]."</th>";
  echo"<th>Date : ".$taches[date]."</th>";

  ///interro db table temps
  $querry = "SELECT * FROM temps WHERE id_tache=$taches[id] ORDER BY date";
  list($qh4,$num4) = query_db($querry);
  $temps_tache=0; $allremarks="<br />";
  $users = "par: ";
  while($temps = result_db($qh4)){
   $temps_tache+= $temps[duree];
   //recupre le nom du user associe a ce temps
   $querry = "SELECT nom FROM users WHERE id=$temps[user]";
   list($qh3,$num3) = query_db($querry);
   $next_user= result_db($qh3 );
 // cree une liste de noms des partocipants
   if ( strstr($users, $next_user[nom])==FALSE)
    // si ce nom n'est pas deja dans la chaine
      $users.= $next_user[nom].", ";

// cree une chaine de remarques liees a ces temps
 if (!empty( $temps[remarks]) )
 $allremarks .= $temps[date].":".$temps[remarks]."<br />";

  }
 $temps_total += $temps_tache;
  echo"<th>Par : ".$users."</th>";
  echo"<th>Dur&eacute;e : ".$temps_tache." h (".$num4." enreg.)</th></tr>";
  echo "<tr><td colspan=\"4\">".$taches[descr].$allremarks."</td></tr>";
}
?>

<tr bgcolor="#f7bb09"><td colspan="3"> </td>

 <td> Temps Total Projet : <?php echo $temps_total?> h</td>
 </tr>

</tbody>
</table>
<?php }//end if connect
?>

<br /><center>
  <a href="#" onclick="javascript:self.close();">Fermer</a>
<br /></center>
</div>
<?php pied_page() ?>
