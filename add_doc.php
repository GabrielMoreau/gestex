<?php
/// add_doc.php
//ajoute un document quelconque dans un repertoire associe a un projet ou une tache
// Authenticate
include("session_auth.php");
 if (!auth(2))
  Header("Location: login.php");

 $user_id = $_SESSION['user_id'];
 $logged_in_user = strtolower($_SESSION['logged_in_user']);

$manip_id = $_GET[idm];
if (empty($manip_id))
 Header("Location: accueil.php");

$proj_id = $_GET[idp];
if (empty($proj_id))
 Header("Location: accueil.php");

$task_id = $_GET[idt];

require("html_functions.php");

if ( $connex = connect_db() ){
 // recupere les nom de manip, projet et tache selectionnes
 $result = mysql_query("SELECT nom FROM manip WHERE id='$manip_id'");
 $nom_manip = mysql_result($result,0);
 // recupere la projet selectionne
 $result = mysql_query("SELECT nom FROM projet WHERE id='$proj_id'");
 $nom_projet = mysql_result($result,0);
if (!empty($task_id)){
  // recupere la tache selectionne
 $result = mysql_query("SELECT nom FROM tache WHERE id='$task_id'");
 $nom_tache = mysql_result($result,0);
 }

$texte = $logged_in_user." (".$user_id.") Voila un formulaire pour jouter un document <br />";

 $titre.="Ajouter un document";
 $texte.= "&agrave; <b>".$nom_manip.":".$nom_projet;
 if (!empty($nom_tache))
   $texte.=":". $nom_tache;
 $texte .="</b><br />";

en_tete($titre);

echo $texte;

//verif de l'existence des repertoires dans /data

//ajout d'un repertoire dans data/nom_manip/nom_projet
 $dossier = "data/".$nom_manip;
 $dossier = str_replace(" ", "_", $dossier );
echo "test de ".$dossier."...";
 if (!is_dir ($dossier) ){
   //data/nom_manip n'existe pas
   mkdir ($dossier);
 echo "creation de ".$dossier."<br />";
}
$dossier .="/". $nom_projet;
 $dossier = str_replace(" ", "_", $dossier );
echo "test de ".$dossier."...";
 if (!is_dir ($dossier) ){
    //data/nom_manip/nom_projet n'existe pas
    mkdir ($dossier);
 echo "creation de ".$dossier."<br />";
}
// creation du repertoire associe a cette tache
$dossier .= "/".$nom;
//remplace les espaces par des underscore
 $dossier = str_replace(" ", "_", $dossier );
echo "test de ".$dossier."...";
 if (!is_dir ($dossier) ){
   //data/nom_manip/nom_projet/nom_tache n'existe pas
 mkdir($dossier);
 echo "creation de ".$dossier."<br />";
}

}//end if connex
 else
  Header("Location: accueil.php");
?>

<br />
<br />
</div>
<?php pied_page() ?>
