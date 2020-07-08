<?php

//del_equip.php

// Authenticate
include("session_auth.php");
require("html_functions.php");

if (!auth(3))
Header("Location: login.php");
en_tete('Suppression &eacute;quipe');

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);


if (empty($_GET['id']))
  Header("Location: list_equip.php");
else
  $id_equip = $_GET['id'];

if(empty($_GET['ok'])) // On récupère une variable ok qui sert a vérifier que la personne est bien sûr de supprimer la catégorie choisi
  $valide ='no'	// s'il n'y a pas d'id, on met 'no' dans $valid
else if($_GET['ok']=='yes') // si ok dans l'url est 'yes', on valide la suppression
  $valide = 'yes';
else	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
  $valid = 'no'; 

if (!isset($valid) || empty($valid) || $valid=="no"){ // on regarde ce qu'il y a dans $valid et si c'est NULL ou 'no', on pose la question
  echo "Sur de supprimer l'&eacute;quipe ".$id_equip. " ?<br />";
  echo "<a href=\"".$_SERVER[PHP_SELF]."?id=".$id_equip."&ok=yes\">OUI</a><br />"; // si la personne répond 'oui', on recharge la page en mettant ok=yes dans l'url 
  echo "<a href=\"".$_SERVER[HTTP_REFERER]."\">NON</a><br />"; // sinon, on retourne à la page précédente
}
else{
  if ( $pdo = connect_db() ){
    // on supprime l'équipe
    $sql = 'DELETE LOW_PRIORITY FROM equipe WHERE id = ? LIMIT 1';
    //  $result = mysql_query($querry);
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_equip));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$result){ // si ça n'a pas marché
			echo "<br />erreur dans la suppression de l'équipe : ".$id_equip;
		}else{
			echo "Equipe ".$id_equip." supprim&eacute;!<br />";
		}
  }
    //on retourne a la page precedente
    echo "<a href=\"list_equip.php\">Suite</a><br />";
}

?>
<?php pied_page() ?>
