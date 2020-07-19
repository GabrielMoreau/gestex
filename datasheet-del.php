<?php
// datasheet-del.php

// Authenticate
include("session_auth.php");

if (!auth(3))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['id']))
	Header("Location: equipment-list.php");
else
	$id_notice = $_GET['id'];
	
if(empty($_GET['ok'])){ // On recupere une variable ok qui sert a verifier que la personne est bien sur de supprimer la categorie choisi
	$valid ='no';	// s'il n'y a pas d'id, on met 'no' dans $valid
}else if($_GET['ok']=='yes'){// si ok dans l'url est 'yes', on valide la suppression
    $valid = 'yes';
} else{	// si c'est n'importe quoi d'autre, on ne valide pas la suppression
	$valid = 'no'; 
}
if (!isset($valid) || empty($valid) || $valid=="no"){
echo "Sur de supprimer la notice ".$id_notice." ?<br />";
echo "<a href=\"".$_SERVER['PHP_SELF']."?id=".$id_notice."&ok=yes\">OUI</a><br />";
echo "<a href=\"".$_SERVER['HTTP_REFERER']."\">NON</a><br />";
}
else{
	if ($pdo = connect_db()){

		// on supprime la notice
		$sql = 'SELECT chemin_notice FROM notice WHERE id = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_notice));
        $notice = $stmt->fetchAll(PDO::FETCH_ASSOC);  // on recupere le chemin de la notice a supprimer
        var_dump($notice);
        if (!$notice){ // si ca n'a pas marche
            echo "<br />erreur dans la r&eacute;cup&eacute;ration du chemin de la notice : ".$id_notice;
        }else{
            if( file_exists ( $notice[0]['chemin_notice'])){
                $result = unlink( $notice[0]['chemin_notice'] );
                if (!$result){ // si ca n'a pas marche
                    echo "<br />erreur dans la suppression du fichier avec la notice : ".$id_notice;
                }else{
                    $sql = 'DELETE LOW_PRIORITY FROM notice WHERE id = ? LIMIT 1;';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array($id_notice));
                        echo "Notice ".$id_notice." supprim&eacute;!<br />";
                }
            }else{
                echo "la notice a supprimer n'existe pas";
            }
        }
    }
    // on retourne a la page d'accueil
	Header("Location: equipment-list.php");
}
?>
