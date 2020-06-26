<?php

//manip_maint.php

// Authenticate
include("session_auth.php");

if (!auth(1))
 Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
 $tri ="nom";

//et le numero de manip
$manip_id=$_GET['id'];
if (empty($manip_id))
 Header("Location : accueil.php");

require("html_functions.php");

en_tete("Historique Manip");

if ( $connex = connect_db() ){

 // recupere les refs du user
 $querry = "SELECT prenom,nom FROM users where loggin='$logged_in_user' " ;
 list($qh,$num) = query_db($querry);
 
$data = result_db($qh);
echo " Bienvenue $data[prenom] $data[nom] ($user_id)<br /><br />";
?>

<br />
Voici la liste des Projets de la manip :<br />
<?php
 $querry = "SELECT * FROM manip where id='$manip_id' " ;
 list($qh,$num) = query_db($querry);
 ///recupere les infos de la manip
 $data = result_db($qh);
 $dossier_manip=$data[nom];
 ?>

<!-- titre -->
<table cellpadding="1" cellspacing="1" border=1 style="width: 90%; text-align: center; margin-left: auto; margin-right: auto;">
  <tbody><tr bgcolor="#f7d709">
 <?php
 echo "<td rowspan=2><h2>".$data[nom]." (".$data[id].")</h2> <i>Date</i> :".$data[date]."<br /></td>";
  echo "<td style=\" text-align: left;\">".$data[descr]."<br /></td>";
    // recupere le nom de de equipes
  $querry = "SELECT nom FROM equipe WHERE id ='$data[equipe]'";
  list($qheq,$numeq) = query_db($querry);
  $eq = result_db($qheq)  ;

 echo "<tr bgcolor=\"#f7d709\">";

  echo "<td style=\" text-align: center;\"><i>Equipe</i> :".$eq[nom]."<br />";
    // recupere le nom du chercheur
  $querry = "SELECT nom FROM users WHERE id ='$data[chercheur]'";
  list($qheq,$numeq) = query_db($querry);
  $eq = result_db($qheq)  ;

  echo "<i>Chercheur</i> :".$eq[nom]."<br />";

  echo "<i>Local</i> :".$data[local]."<br /></td>";
 echo "</tr>";
 ?>
</tr></tbody></table>
<br />

<!-- menu commandes -->
<table cellpadding="1" cellspacing="1" border="0"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr class=menu>
 <?php if ($user_level >=2){ ?>
   <td style="vertical-align: top; text-align: center;">
  <a href="add_proj.php?idm=<?php echo $manip_id ?>">Ajout d'un Projet</a>
  <br /></td>
   <td style="vertical-align: top; text-align: center;">
  <a href="assoc_proj.php?id=<?php echo $manip_id ?>">Association d'un Projet</a>
  <br /></td>
 <?php } ?>
   <td style="vertical-align: top; text-align: center;">
 <a href="accueil.php">Retour a l'accueil</a>
 <br /></td>
  
  <td style="vertical-align: top; text-align: center;">
 <a href="logout.php?variable=projet">Quitter</a>
 <br /></td> </tr></tbody>
</table>
<br />

<!-- tableau(x) des projets -->
<?php
 // un tableau par projet, contenant les taches...
 $total_manip= 0;$total_projet=0;
 $querry = "SELECT id, nom FROM projet where manip='$manip_id' ORDER BY date" ;
 list($qh,$num) = query_db($querry);
 ///recupere les infos de la manip
 while ($manips = result_db($qh)){
 $proj_id=$manips[id];
 ?>

<script language="javascript">
function windowToTop(lien){
wnd = window.open(lien,  'Project Info','location=0,directories=no,status=no,menubar=yes,resizable=1,width=550,height=550');
wnd.focus();
}
</script>

<table cellpadding="1" cellspacing="1" border="1" style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7bb09">
  <th style="vertical-align: top; text-align: left;" colspan="4" >
<?php  echo "Projet :<a href=\"#\" onclick=\"windowToTop('proj_info.php?idm=". $manip_id ."&idp=". $proj_id ."');\" title =\"Détails de ce projet\">";
  echo $manips[nom]." (".$manips[id].")"; ?> </a><br />
      </th>
  <th style="vertical-align: top; text-align: left;">
 <?php
 ///bouton lien vers la doc
 $dossier_proj ="data/".$dossier_manip."/". $manips[nom];
 //remplace les espaces par des underscore
 $dossier_proj = str_replace(" ", "_", $dossier_proj);
 // cherche l'existence de ce dossier
 ///echo $dossier_proj;
 /// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
 if (@opendir($dossier_proj) != FALSE){
  //si trouvé ajoute un bouton
  echo "Voir : <a href =\"browse_proj.php?idm=". $manip_id ."&idp=".$proj_id."\"><img src=\"images/filefind.png\" nosave=\"\" width=\"15\" title =\"Voir les docs de ce projet\"></a><br />";
 
 }
 ?>
  </th>
<?php if ($user_level >=2){ ?>
  <!-- <th colspan="3"></th> //ajout de doc   -->
    <th style="vertical-align: top; text-align: left;">
  
  <a href ="add_doc.php?idm=<?php echo $manip_id ?>&idp=<?php echo $proj_id ?>"><img src="images/stockattach.png" nosave=\"\" width=\"20\"  title ="ajouter un document à ce projet"></a><br />
  </th>
   <th style="vertical-align: top; text-align: right;">
  <a href ="add_proj.php?idm=<?php echo $manip_id ?>&idp=<?php echo $proj_id ?>"><img src="images/editcopy.png" nosave=\"\" width=\"20\" title ="Modifier ce projet"></a><br />
   </th>
    <th style="vertical-align: top; text-align: right;">
  <a href ="del_proj.php?idm=<?php echo $manip_id ?>&idp=<?php echo $proj_id ?>"><img src="images/edittrash.png" nosave=\"\" width=\"20\" title="Supprimer ce projet"></a><br />
   </th>
  
  
    <?php } else {
  echo "<th colspan=\"2\"></th>";
  } ?>
 </tr>
 <!-- <tr bgcolor="#f7d709">
  <th style="vertical-align: top; text-align: center;">Taches<br />      </th>
  <th style="vertical-align: top; text-align: center;">Debut :<br />      </th>
  <th style="vertical-align: top; text-align: center;">Par :<br />      </th>
  <th style="vertical-align: top; text-align: center;">Temps pass&eacute;:<br />      </th>
 
 <?php if ($user_level >=2){ ?>
  <th colspan="2" style="vertical-align: top; text-align: right;" >
  <a href ="add_task.php?idm=<?php echo $manip_id ?>&idp=<?php echo $proj_id ?>">Ajouter une tache</a><br />
    </th>
 <?php }
  else {
  echo "<th colspan=\"2\"></th>";
  } ?>
 </tr> --->
<?php //interrogation base de données
 $total_projet=0;
 // recupere la liste des taches de ce projet
 $querry = "SELECT id,nom,date,user, temps FROM tache WHERE projet=$proj_id ORDER BY date";
 list($qh2,$num2) = query_db($querry);
 while ($taches = result_db($qh2)) {
 
  // remplit le tableau de taches
       echo"<tr><td><img src =\"images/forward.png\" nosave=\"\" width=\"15\"></td>";
  echo"<td style=\"vertical-align: top;\">";
  ///     echo "<a href=\"task_info.php?idm=".$manip_id."&idp=".$proj_id."&idt=".$taches[id]."\" target=\"new\">".$taches[nom]."</a>";
     echo " <a href=\"#\" onclick=\"windowToTop('task_info.php?idm=".$manip_id."&idp=".$proj_id."&idt=". $taches[id]."');\">";
 echo $taches[nom]."</a>";
  echo"</td><td style=\"vertical-align: top;\">";
        echo "date:".$taches[date];
     echo"</td><td style=\"vertical-align: top;\">";

  ///interro db table temps
  $querry = "SELECT duree,user FROM temps WHERE id_tache=$taches[id]";
  list($qh4,$num4) = query_db($querry);
  $temps_tache=0;
  $users = "par: ";
  while($temps = result_db($qh4)){
   $temps_tache+= $temps[duree];
   //recupre le nom du user associe a ce temps
   $querry = "SELECT nom FROM users WHERE id=$temps[user]";
   list($qh3,$num3) = query_db($querry);
   $next_user= result_db($qh3 );
   if ( strstr($users, $next_user[nom])==FALSE)
   // si ce nom n'est pas deja dans la chaine
     $users.= $next_user[nom].", ";
  }
  echo $users;
  
  
   
       echo"</td><td style=\"vertical-align: top;\">";
   echo "dur&eacute;e :";
  echo $temps_tache." heures";
  $total_projet += $temps_tache;
   if ($user_level>=2){
   echo "<a href=\"add_time.php?idm=".$manip_id."&idp=".$proj_id."&idt=".$taches[id]."\"><img src=\"images/xclock.png\" nosave=\"\" width=\"20\" title=\"Ajouter du temps\"></a>";
     echo"</td><td style=\"vertical-align: top;\">";
   // ajout d'un document à une tache -->
    echo "<a href=\"add_doc.php?idm=".$manip_id."&idp=".$proj_id."&idt=".$taches[id]."\"><img src=\"images/stockattach.png\" nosave=\"\" width=\"20\" title =\"Ajouter un document à cette tache\"></a>";
     echo"</td><td style=\"vertical-align: top;\">";
       //modif d'une tache
    echo "<a href=\"add_task.php?idm=".$manip_id."&idp=".$proj_id."&idt=".$taches[id]."\"><img src=\"images/editcopy.png\" nosave=\"\" width=\"20\" title =\"Modifier cette tache\"></a>";
   echo"</td><td style=\"vertical-align: top;\">";
  //supression dune tache
   echo "<a href=\"del_task.php?idm=".$manip_id."&idt=".$taches[id]."\"><img src=\"images/edittrash.png\" nosave=\"\" width=\"20\" title=\"Supprimer cette tache\"></a>";
  }
   echo"</td></tr>";

 }//end while taches

   echo"<tr><td style=\"vertical-align: top;text-align: left;\" >"; 
 if ($user_level >=2){
 echo" <a href =\"add_task.php?idm=".$manip_id." ?>&idp=". $proj_id ."?>\"><img src=\"images/edit_add.png\" nosave=\"\" width=\"15\"  title=\"Ajouter une tache\"></a><br />";
 }

echo"</td><td style=\"vertical-align: top;text-align: right;\" colspan=3 >";
        echo "temps total :";
      echo"</td><td style=\"vertical-align: top;\">";
  echo $total_projet;
      echo" heures</td></tr>";
 echo"</tbody></table>";
 $total_manip+=$total_projet; 
}//end while manip
echo "<br />temps total manip : ".$total_manip." heures<br /><br />";

////////projets associés

if (!empty($data[assoc_proj])){
 echo "<h3>Projet(s) associé(s) :</h3><ul>";
 $assoc = explode(',',$data[assoc_proj]);
foreach($assoc as $a){
  // recupere l'identité du projet associé
  $querry = "SELECT id,nom FROM projet WHERE id=".$a;
  list($qh3,$num3) = query_db($querry);
  $projet_a = result_db($qh3);

 //liens vers ces projets
 echo "<li>(".$a.") ";
echo "<a href=\"#\" onclick=\"windowToTop('proj_info.php?idp=". $projet_a[id] ."')\" title =\"Détails de ce projet\">";
 echo $projet_a[nom]."</a></li>";
 }
echo "</ul>";
}

}//end if connect
?>
  
<br />
<br />
</div>
<?php pied_page() ?>
