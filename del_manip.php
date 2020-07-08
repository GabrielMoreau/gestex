<?php

//del_manip.php

// Authenticate
include("session_auth.php");

if (!auth(3))
 Header("Location: login.php");

require("html_functions.php");

en_tete('Suppression Manip');

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

$valid= $_GET['ok'];

$id_manip = $_GET['id'];
if (empty($id_manip))
 Header("Location: list_manip.php");

echo "Manip:".$id_manip. " ok :".$valid."<br />";

if (!isset($valid) || empty($valid) || $valid=="no"){
 echo "Sur de supprimer la Manip ".$manip_id. " ainsi que tous ses projets et taches ?<br />";
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
    // list($qh,$num) = query_db($querry);
    // while ($data = result_db($qh) && $result) {
      foreach($result as $data){
    //pour chaque projet de la manip
      // on supprime toutes les taches liees a ce projet
      $sql = 'DELETE LOW_PRIORITY FROM tache WHERE projet = ?';
      $stmt = $pdo->prepare($sql);
        $stmt->execute(array($data['id']));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // $result = mysql_query($querry);
      //on supprime ce projet
      $sql = "DELETE LOW_PRIORITY FROM projet WHERE manip = ?";
      $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_manip));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // $result = mysql_query($querry);
    }//end while projet
    if ($result){
      //on supprime la manip
      $sql = 'DELETE LOW_PRIORITY FROM manip WHERE id = ?';

        // $result = mysql_query($querry);
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_manip));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
      if (!$result){
      //inscription !ok
      $erreur = mysql_error();
      echo "<br />erreur :".$erreur;

    }
    else
    echo "Manip ".$manip_id." supprim&eacute;e, ainsi que ses projets et taches!<br />";
    //on retourne a la page precedente
    echo "<a href=\"list_manip.php\">Suite</a><br />";
  }
}

?>
<?php pied_page() ?>
