<?php
// supplier-del.php
$web_page = true;

// Authenticate
require_once('auth-functions.php');
require_once('html-functions.php');

if (!auth(3))
  Header("Location: login.php");

en_tete('Suppression Fournisseur');
$user_id = $_SESSION['user_id'];
$logged_user = strtolower($_SESSION['logged_user']);

if (empty($_GET['id']))
  Header("Location: supplier-list.php");
else
  $id_fourn = $_GET['id'];
  
if(empty($_GET['ok'])) // On recupere une variable ok qui sert a verifier que la personne est bien sur de supprimer la categorie choisi
  $valid ='no';	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
  $valid = 'yes';
else	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
  $valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid=="no"){
  echo "Sur de supprimer le Fournisseur ".$id_fourn." ?<br />";
  echo "<a href=\"".$_SERVER['PHP_SELF']."?id=".$id_fourn."&ok=yes\">OUI</a><br />";
  echo "<a href=\"".$_SERVER['HTTP_REFERER']."\">NON</a><br />";
}
else{
if ( $pdo = connect_db() ){
  // on supprime le fournisseur
  $sql = 'DELETE LOW_PRIORITY FROM fournisseurs WHERE id = ? LIMIT 1';
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_fourn));
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}//end if connect
//on retourne a la page de la liste des fournisseur
Header("Location: supplier-list.php");
} //else end

?>
<?php pied_page() ?>
