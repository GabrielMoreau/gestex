<?php

//del_manip.php

// Authenticate
include("auth-functions.php");
require_once('html-functions.php');

if (!auth(3))
  Header("Location: login.php");
en_tete('Suppression Manip');

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);


if (empty($_GET['id']))
  Header("Location: list_manip.php");
else
  $id_manip = $_GET['id'];

if(empty($_GET['ok'])) // On recupere une variable ok qui sert a verifier que la personne est bien sur de supprimer la categorie choisi
	$valide ='no'	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
	$valide = 'yes';
else	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
	$valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid=="no"){
 echo "Sur de supprimer la Manip ".$id_manip. " ainsi que tous ses projets et taches ?<br />";
 echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$id_manip."&ok=yes\">OUI</a><br />";
  echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />";

}
else{
  if ( $pdo = connect_db() ){
    //on cherche tous les projets correspondant a la manip
    $sql='SELECT id FROM projet WHERE manip = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_manip));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $data){
      //pour chaque projet de la manip
      // on supprime toutes les taches liees a ce projet
      $sql = 'DELETE LOW_PRIORITY FROM tache WHERE projet = ?';
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($data['id']));
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      //on supprime ce projet
      $sql = "DELETE LOW_PRIORITY FROM projet WHERE manip = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($id_manip));
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }//end while projet
    if ($result){
      //on supprime la manip
      $sql = 'DELETE LOW_PRIORITY FROM manip WHERE id = ?';
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($id_manip));
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    if (!$result){
      //inscription !ok
      echo "<br />erreur dasn la suppression de la manip : ".$id_manip;
    }
    else
      echo "Manip ".$manip_id." supprim&eacute;e, ainsi que ses projets et taches!<br />";
    //on retourne a la page precedente
    echo "<a href=\"list_manip.php\">Suite</a><br />";
  }
}

?>
<?php pied_page() ?>
