<?php
 // Authenticate
 include("session_auth.php");

 if (!auth(2))
  Header("Location: login.php");

 $user_id = $_SESSION['user_id'];
 $logged_in_user = strtolower($_SESSION['logged_in_user']);

require("html_functions.php");

/// valid_proj.php
//validation d'un nouveau projet

unset($erreur); unset($nom);
//variables ne pouvant etre nulles
if (empty($_POST[id_manip]))
 $erreur="id manip non pr&eacute;cis&eacute;";
else {
 $id_manip=$_POST[id_manip];
    if (empty($_POST[nom]))
     $erreur="nom non pr&eacute;cis&eacute;";
    else{
     $nom =$_POST[nom];
     if (empty($_POST[date]))
      $erreur="Date non pr&eacute;cis&eacute;";
     else{
       $descr=$_POST[descr];
       //variables pouvant etre nulles
       $date =$_POST[date];

}}}

en_tete("resultat ajout projet ");

if (!empty($erreur) ){

 //erreur

 echo "<br />erreur :".$erreur;
 echo"<br /><a href=\"add_proj.php?id=".$id_manip."\">Suite</a><br />\n";

 pied_page();
 exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit

$proj_id=0;

if ( $connex = connect_db() ){
  //ajout du projet
 $querry = "INSERT INTO projet (manip, nom,descr,date)".
   " VALUES ( '$id_manip', '$nom',  '$descr', '$date')";
  $result = mysql_query($querry);
   //
   if ($result){
   echo "result:".$result;
    $proj_id = last_id_db();
    echo "proj id:".$proj_id;
    //insere un tache par defaut
    $nomt = "T&acirc;che par defaut"; $descr="";
    $querry = "INSERT INTO tache (projet, nom, descr, date)".
     " VALUES ('$proj_id', '$nomt',  '$descr', '$date')";
    $result = mysql_query($querry);
    }

   if (!$result){
   //inscription !ok
   $erreur = mysql_error();
  echo "<br />erreur :".$erreur;
  echo"<br /><br /><a href=\"add_proj.php?idm=".$id_manip."\">Suite</a><br /><br />\n";
  }
  else{ //result=ok
  $result = mysql_query("SELECT nom FROM manip WHERE id='$id_manip'");
 $nom_manip = mysql_result($result,0);

//ajout d'un repertoire dans data/nom_manip
 $dossier = "data/".$nom_manip;
 $dossier = str_replace(" ", "_", $dossier );
 if (!is_dir ($dossier) )
 //data/nom_manip n'existe pas
 mkdir ($dossier);
// creation du repertoire associe a ce projet
$dossier .= "/".$nom;
//remplace les espaces par des underscore
 $dossier = str_replace(" ", "_", $dossier );
mkdir($dossier);

echo "inscription du projet ".$nom." a la manip ".$nom_manip."<br />";
echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\">";
echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"manip_maint.php?id=".$id_manip."\">Suite</a><br /><br />\n";
  }
 }//end if connect

pied_page();
exit();
}

?>
