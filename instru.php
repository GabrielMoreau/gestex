<?php

// Authenticate

include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

en_tete("Liste des appareils:");

//recuper la methode de tri

if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];
//recupere la categorie
// $cat=$_GET['categorie'];
//echo "$cat";
if (empty($_GET['categorie']))
	$cat = 0;
else
	$cat = $_GET['categorie'];

//recupere l'equipe

if (empty($_GET['equipe']))
	$eq = 0;
else
	$eq = $_GET['equipe'];

// $eq=$_GET['equipe'];
// echo "$eq";
echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")<br />";
?>
<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
<a href="<?php GESTEX_URL_SERVICE ?>">Retour &agrave;<br />la page du service</a>	

	<br /></td>
<?php if (empty($eq)) {	?>

 <td style="vertical-align: top; text-align: center;">

<?php

// if ( $pdo = connect_db() ){
	//recupere la categorie

// $sql = 'SELECT * FROM categorie where id = ?' ;
// 	list($qh,$num) = query_db($querry);
// 	$last_id=0;
// $data = result_db($qh);
// $stmt = $pdo->prepare($sql);
//         $stmt->execute(array($cat));
//         $categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
echo "<a href =\"add_app2.php?categorie=".$cat."\">Ajout<br />d'un appareil</a>";

// }
?>
	
	<br /></td>

 <td style="vertical-align: top; text-align: center;">
	<a href="list_fourn.php">Liste<br />des fournisseurs</a>
	<br /></td>

 <td style="vertical-align: top; text-align: center;">
	<a href="add_fourn.php">Ajout<br />d'un fournisseur</a>
	<br /></td>

<?php }	?>

<td style="vertical-align: top; text-align: center;">
	

	<a href="essai.php">Retour aux cat&eacute;gories</a>
<br /></td>

<?php if ( $user_level >=3 ) {	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_categorie.php">Ajout<br />d'une cat&eacute;gorie</a>
	<br /></td>

<?php }	?>

	<br /></td> </tr></tbody>
</table>

<br />
Liste des appareils : <br />
<i>Cliquer sur le nom d'un appareil pour connaitre son mod&egrave;le, sa date d'achat, ses accessoires...</i><br />

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
 <th style="vertical-align: top; text-align: center;">
	<a href ="instru.php?tri=categorie">Cat&eacute;gorie<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Nom<br />
      </th>
     <th style="vertical-align: top; text-align: center;">
	Mod&egrave;le<br />
      </th>
 <th style="vertical-align: top; text-align: center;">
	Gamme<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	&Eacute;quipe<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Fournisseur<br />
      </th>
<th style="vertical-align: top; text-align: center;">
	Notice<br />
      </th>

   
<?php if ( $user_level >=2 ) 	
		echo "</th><th>";
	if ( $user_level >=3 ) 	
		echo "</th><th>";
	  ?>
    </tr>
<?php	//interrogation base de donnees

if ( $pdo = connect_db() ){
	// recupere la liste de appareils

// if ((!empty($cat))||(!empty($eq)))
if($cat == 0 && $eq != 0)

{
$sql = 'SELECT * FROM Listing where equipe= ? order by nom ASC;';
	// list($qh,$num) = query_db($querry);
	
	// $last_id=0;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($eq));

}else if($eq == 0 && $cat != 0){
	$sql = 'SELECT * FROM Listing where categorie= ? order by nom ASC;';
	// list($qh,$num) = query_db($querry);
	
	// $last_id=0;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($cat));
}

if ($cat == 0 && $eq == 0)
{
	$sql = 'SELECT * FROM Listing order by ?;';
	// list($qh,$num) = query_db($querry);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	// $last_id=0;
}

// $data = result_db($qh);
$listing =  $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo "<tr>";
//      echo"<td style=\"vertical-align: top;\">";
// $sql = 'SELECT id, nom FROM categorie WHERE id = ?;';
// 	// list($qheq,$numeq) = query_db($querry);
// 		// $equip = result_db($qheq);
// 		$stmt = $pdo->prepare($sql);
// 		$stmt->execute(array($listing[0]['categorie']));
// 		$categorie =  $stmt->fetchAll(PDO::FETCH_ASSOC);
//       	echo $categorie[0]['nom'];
//  echo"</td><td style=\"vertical-align: top;\">";
// 	echo "<a href =\"fiche_vie.php?id=".$listing[0]['id']."\">". $listing[0]['nom']."</a>";
    
//        echo"</td><td style=\"vertical-align: top;\">";
// echo $listing[0]['modele'];

//   echo"</td><td style=\"vertical-align: top;\">";

// echo $listing[0]['gamme'];

//        echo"</td><td style=\"vertical-align: top;\">";

// 	// recupere la nom d'equipe

// 	$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
// 	// list($qheq,$numeq) = query_db($querry);
// 	// 	$equip = result_db($qheq);
// 	$stmt = $pdo->prepare($sql);
// 	$stmt->execute(array($listing[0]['equipe']));
// 	$equipe =  $stmt->fetchAll(PDO::FETCH_ASSOC);
// 	  echo $equipe[0]['nom'];
//        echo"</td><td style=\"vertical-align: top;\">";
	
// 	// recupere la nom du fournisseur
// 	$sql = 'SELECT id, nom FROM fournisseurs WHERE id = ?';
// 	// list($qheq,$numeq) = query_db($querry);
// 	// 	$equip = result_db($qheq);
// 	$stmt = $pdo->prepare($sql);
// 	$stmt->execute(array($listing[0]['fournisseur']));
// 	$fournisseur =  $stmt->fetchAll(PDO::FETCH_ASSOC);

//       		echo $fournisseur[0]['nom'];
      

//   echo"</td><td style=\"vertical-align: top;\">";

	
// 	///bouton lien vers la doc
// 	$dossier_proj ="data/instru/".$listing[0]['nom'];
	

// 	//remplace les espaces par des underscore
// 	$dossier_proj = str_replace(" ", "_", $dossier_proj);
// 	// cherche l'existence de ce dossier
// 	//echo $dossier_proj;
// 	/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
// 	if (@opendir($dossier_proj) != FALSE){
// 		//si trouve ajoute un bouton
// 		echo "Voir : <a href =\"notice.php?id=". $data['id']."\">".$data['nom']."<img src=\"images/filefind.png\" nosave=\"\" title =\"Voir ce projet\" /></a><br />";
    
// 	}
// 	if (( $user_level >=2)&&($eq=="15 pret=15")) {
// echo"</td><td style=\"vertical-align: top;\">";
//       echo '<a href="add-pret.php?id=',$listing[0]['id'],'><img src="images/edit.png" nosave="" title="Demande de pret" /></a>';
//      echo"</td>"; }
// 	 if (( $user_level >=2)&&($eq!="15 pret=15")) {	
//       echo"</td><td style=\"vertical-align: top;\">";
//       echo '<a href="add_app2.php?id=',$listing[0]['id'],'"><img src="images/edit.png" nosave="" title="Modifier" /></a>';
//       echo"</td>";
// 	}//end if
//  if (( $user_level >=3 )&&($eq!="15 pret=15")) {
//       echo"</td><td style=\"vertical-align: top;\">";
//       echo '<a href="del_app2.php?id=',$listing[0][id],'"><img src="images/edittrash.png" nosave="" title="Supprimer" /></a>';
//       echo"</td>";
	
// }
// echo"</tr>";

// while ($data = result_db($qh)) {
foreach($listing as $data){
	// remplit le tableau

     echo"<tr><td style=\"vertical-align: top;\">";
	$sql = 'SELECT id, nom FROM categorie WHERE id = ?;';
	// list($qheq,$numeq) = query_db($querry);
	// 	$equip = result_db($qheq);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($data['categorie']));
	$categorie =  $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo $categorie[0]['nom'];

 echo"</td><td style=\"vertical-align: top;\">";
	echo "<a href =\"fiche_vie.php?id=".$data['id']."\">". $data['nom']."</a>";
    
       echo"</td><td style=\"vertical-align: top;\">";

echo $data['modele'];

  echo"</td><td style=\"vertical-align: top;\">";

echo $data['gamme'];

       echo"</td><td style=\"vertical-align: top;\">";

	// recupere la nom d'equipe

	$sql = 'SELECT id, nom FROM equipe WHERE id = ?';
	// list($qheq,$numeq) = query_db($querry);
	// 	$equip = result_db($qheq);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($listing[0]['equipe']));
		$equipe =  $stmt->fetchAll(PDO::FETCH_ASSOC);
				  echo $equipe[0]['nom'];
      		// echo $equip['nom'];
       echo"</td><td style=\"vertical-align: top;\">";
	
	// recupere la nom du fournisseur
	$sql = 'SELECT id, nom FROM fournisseurs WHERE id = ?;';
	// list($qheq,$numeq) = query_db($querry);
	// 	$equip = result_db($qheq);
    //   		echo $equip['nom'];
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($listing[0]['fournisseur']));
	$fournisseur =  $stmt->fetchAll(PDO::FETCH_ASSOC);
      		echo $fournisseur[0]['nom'];

  echo"</td><td style=\"vertical-align: top;\">";
$sql = 'SELECT id, nom FROM categorie WHERE id = ?';
	// list($qheq,$numeq) = query_db($querry);
		// $cat = result_db($qheq);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($listing[0]['categorie']));
		$categorie =  $stmt->fetchAll(PDO::FETCH_ASSOC);
      		//echo $cat[nom];

	///bouton lien vers la doc
	$dossier_proj ="data/instru/".$data['nom'];

	//remplace les espaces par des underscore
	$dossier_proj = str_replace(" ", "_", $dossier_proj);
	// cherche l'existence de ce dossier
	//echo $dossier_proj;
	/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
	if (@opendir($dossier_proj) != FALSE){
		//si trouve ajoute un bouton
		echo 'Voir : <a href ="notice.php?id=', $data['id'],'\">',$data['nom'],'<img src="images/filefind.png" nosave="" title ="Voir ce projet" /></a><br />';
	}

  if (( $user_level >=2)&&($eq=="15 pret=15")) {
echo '</td><td style="vertical-align: top;">';
      echo '<a href="add-pret.php?id=',$data['id'],'"><img src="images/edit.png" nosave="" title="Demande de pret" /></a>';
     echo"</td>"; 
}
 if (( $user_level >=2)&&($eq!="15 pret=15")) {	
      echo"</td><td style=\"vertical-align: top;\">";
      echo '<a href="add_app2.php?id=',$data['id'],'"><img src="images/edit.png" nosave="" title="Modifier" /></a>';
      echo"</td>";
	}//end if
 if (( $user_level >=3 )&&($eq!="15 pret=15")) {
      echo '</td><td style=\"vertical-align: top;\">';
      echo '<a href="del_app2.php?id=',$data['id'],'"><img src="images/edittrash.png" nosave="" title="Supprimer" /></a>';
      echo"</td>";
	
}
echo"</tr>";

	}//end while

}//end if

?>
  </tbody>
</table>
<br />
</div>
<?php pied_page() ?>
