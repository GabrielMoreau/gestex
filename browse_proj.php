<?php

//browse_proj.php

// Authenticate
include("session_auth.php");

if (!auth(1))
 Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

//recupere  le numero de manip
$manip_id=$_GET['idm'];
if (empty($manip_id))
 Header("Location : accueil.php");

//recupere  le numero de proj
$proj_id =$_GET['idp'];
if (empty($proj_id))
 Header("Location : accueil.php");

require("html_functions.php");

if ( $connex = connect_db() ){

 // recupere le nom de la manip
 $querry = "SELECT nom FROM manip where id='$manip_id' " ;
 list($qh,$num) = query_db($querry);
 $data = result_db($qh);
 $nom_manip= $data[nom];

 // recupere le nom du projet
 $querry = "SELECT nom FROM projet where id='$proj_id' " ;
 list($qh,$num) = query_db($querry);
 $data = result_db($qh);
 $nom_proj= $data[nom];

$titre ="Documents de la manip : ".$nom_manip . " projet : ".$nom_proj;

en_tete($titre);

echo "<a href=\"". $_SERVER['HTTP_REFERER']."\">Retour à la page manip...</a>";

 $dossier_proj ="data/".$nom_manip."/". $nom_proj."/";
 //remplace les espaces par des underscore
 $dossier_proj = str_replace(" ", "_", $dossier_proj);
 // cherche l'existence de ce dossier
  $sdir=array();
  $images = array(); $fichiers= array();
echo "dossier : ".$dossier_proj;
 /// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier

 if ( ($handle = @opendir($dossier_proj)) != FALSE){
   // premier element = dossier projet
       array_push ( $sdir, "" );

 //recherche des sous repertoires (taches)
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
     ////     echo $file;
   if (is_dir($dossier_proj."/".$file)){
      array_push ( $sdir, $file );
    }
   }//end if
  }//end while

 //boucle sur tous les dossiers : projet et taches
 foreach( $sdir as $thedir){
   $dossier = $dossier_proj."/".$thedir;
  if ( ($handle = @opendir($dossier)) != FALSE){
  //ouverture d'un reprtoire et lecture des fichiers
     while (false !== ($file = readdir($handle))) {
     ////echo count($images);
         if ($file != "." && $file != "..") {
             if ( eregi("^[a-zA-Z0-9_\-]+(:?\.jpg|\.gif|\.png|\.pdf|\.doc|\.xls|\.mov|\.avi|\.mpg|\.html|\.dat|\.ps|\.csv)$", $file) == TRUE ){
     ///entasse les images et autres docs
      array_push( $images,$file );
      }
    elseif ( eregi("^[a-zA-Z0-9_\-]+(:?\.txt)$", $file ) == TRUE ){
       //et les fichiers textes
      array_push ( $fichiers, $file );
          }
     }//end if file!=".."

     }//end while
      closedir($handle);

 }//end if readdir
 
///tri par ordre alphabetique 
sort($fichiers); sort($images);
  
  //si trouvé on créé un tableau 5 colonnes :
  // texte (1 colonne)
  // images... (5 colonnes)
  
if (count($images) || count($fichiers)){
?>  
<table cellpadding="1" cellspacing="1" border="1" style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
    <tbody>
<?php
    echo  "<tr bgcolor=\"#f7bb09\"><th colspan=\"5\">".  $nom_proj." (".$thedir .") : " ."</th></tr>";
 //fichiers textes
 while ( $autres = array_pop($fichiers) ){
 
 
  echo "<tr><td colspan=\"5\">";
      //inclus le fichier
      if ( $text_handle = @fopen( $dossier_proj."/".$thedir."/".$autres, "r")){
       echo "<b>".$autres."</b> ";
   while (!feof($text_handle)) 
              echo  fgets($text_handle, 4096)."<br />";
           fclose($text_handle);
       }//end if fopen
   echo "</td></tr><tr>";   
     }//while end autres

$i=0;
 //autres fichiers
while ( $file = array_pop($images) ){

 echo "<td style=\"width: 20%; text-align: center;\"><a href=\"".$dossier_proj."/".$thedir."/".$file."\" target=\"_newFrame\"><img src=\"";
   //teste l'extension
   $pos = strrpos($file, ".");
   switch ( strtolower(substr($file, $pos+1))){
    case "htm":
    case "html":
     echo "images/html.png\" /><br />";
     break;
    case "doc":
     echo "images/document.png\" /><br />";
     break;
    case "xls":
     echo "images/spreadsheet.png\" /><br />";
     break;
    case "pdf":
     echo "images/pdf.png\" /><br />";
     break;
    case "ps":
     echo "images/kghostview.png\" /><br />";
     break;
    case "dat":
    case "csv":
     echo "images/txt.png\" /><br />";
     break;
   case "gif":
    case "jpg":
    case "png":///image
     echo   $dossier_proj."/".$thedir."/".$file."\" width=\"80\" /></a><br />";
     break;
    case "avi":
    case "mov":
    case "mpg":///videos
     echo "images/video.png\" /><br />";
     break;
    default :
     echo "images/unknown.png\" /><br />";
     break;
   }//end switch
   //ajoute le nom du fichier sous l'image
   echo $file."</td>";
   $i++;
   if (($i%5 )== 0)//nouvelle ligne
    echo "</tr><tr>";

  }//while end  file
  //complete le tableau avec des cases vides
  while (($i%5)!=0){
     echo "<td></br></td>";
    $i++;
   } 
?> 
  </tr><tbody></table>
<?php
  }//end if count
 }//end foreach
 }
 else
  echo "pas de documents disponibles pour ce projet!<br />";
 }//end if connect
?>
  
<br />
<br />
</div>
<?php pied_page() ?>
