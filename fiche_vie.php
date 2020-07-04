<?php
//fiche_vie.php

// Authenticate
include("session_auth.php");
session_start();
//if (!auth(1))
	//Header("Location: login.php");
	if(empty($_SESSION['logged_in_user'])){
		$log = false;
		echo "log =false";
	}else{
		$user_id = $_SESSION['user_id'];
		$logged_in_user = strtolower($_SESSION['logged_in_user']);
		$user_level= $_SESSION['level'];
		$log=true;
		echo "log =true";
	
	}
require("html_functions.php");

if (empty($_GET['id']))
	Header("Location: instru.php");
else
	$id_app=$_GET['id'];

	//interrogation base de donnees

if ( $pdo = connect_db() ){
	// recupere la liste de appareils
	$sql = 'SELECT * FROM Listing WHERE id = ?;';
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	// $last_id=0;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_app));
	$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);

en_tete("Caract&eacute;ristiques de l'appareil :<b>".$listing[0]['nom']."</b>");
//recupere la methode de tri
if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];
?>

<!-- <table cellpadding="2" cellspacing="2" border="1" -->
 <!-- style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;"> -->
  <tbody>
    <!-- <tr> -->
	<!-- <td style="vertical-align: top; text-align: center;"> -->
<? php//permet de retourner a la page pr&eacute;cedente?>
	<!-- <a href="<?php //echo $_SERVER['HTTP_REFERER']?>">Retour &agrave; la liste</a> -->

	<!-- <br /></td> -->

<?php //if ( $user_level >=2 ) {	?>

	 <!-- <td style="vertical-align: top; text-align: center;"> -->
	<!-- <a href="logout.php?variable=instru">Quitter</a> -->

<?php // } ?>
	<!-- <br /></td></tr> -->
</tbody>
<!-- </table> -->
<br />

<?php
echo "L'appareil <b>".$listing[0]['nom']."</b> a les caract&eacute;ristiques suivantes :<br />";
?>

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
      <th style="vertical-align: top; text-align: center;">
		Nom<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Mod&egrave;le<br />
      </th>
     <th style="vertical-align: top; text-align: center;">
	Achat<br />
      </th>
    <th style="vertical-align: top; text-align: center;">
	Accessoires<br /><br />
      </th>

  <th style="vertical-align: top; text-align: center;">
	R&eacute;paration / &Eacute;talonnages<br /><br />
      </th>

<th style="vertical-align: top; text-align: center;">
	Responsable<br />
      </th>

<th style="vertical-align: top; text-align: center;">
	Num&eacute;ro d'instrument<br />
      </th>
<th style="vertical-align: top; text-align: center;">
	Inventaire<br />
      </th>

<?php if ($log == true && $user_level >=2 )
		echo "</th><th>";
	if ( $log == true && $user_level >=3 )
		echo "</th><th>";
	  ?>
    </tr>
<?php

	// recupere la liste de appareils
	// $sql = 'SELECT * FROM Listing where id = ? ;';
	// list($qh,$num) = query_db($querry);
	// $last_id=0;
	// $stmt = $pdo->prepare($sql);
	// $stmt->execute(array($id_app));
	// $listing = $stmt->fetchAll(PDO::FETCH_ASSOC);
// while ($data = result_db($qh)) {
foreach($listing as $data){
	// remplit le tableau
 echo"<tr><td style=\"vertical-align: top;\">";
	echo $data['nom'];
         echo"</td><td style=\"vertical-align: top;\">";
	echo $data['modele'];
   echo"</td><td style=\"vertical-align: top;\">";
	echo $data['achat'];
echo"</td><td style=\"vertical-align: top;\">";
	echo $data['accessoires'];
echo"</td><td style=\"vertical-align: top;\">";
	echo $data['reparation'];

 echo"</td><td style=\"vertical-align: top;\">";

// recupere le nom du tech
	$sql = 'SELECT id, nom FROM users WHERE id = ?;';
	// list($qheq,$numeq) = query_db($querry);

	// 	$resp = result_db($qheq);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($data['responsable']));
	$resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
      		if(!empty($resp)){
				  echo $resp[0]['nom'];
			}

  echo"</td><td style=\"vertical-align: top;\">";
echo $data['id'];

  echo"</td><td style=\"vertical-align: top;\">";
echo $data['inventaire'];

 if ($log==true && $user_level >=2 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_app2.php?app=".$id_app."&id=".$data['id']."\"<img src=\"images/edit.png\" nosave=\"\" title=\">Modifier\" /></a>";
      echo"</td>";
	}//end if
 if ($log==true && $user_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_intapp.php?id=".$data['id']."\"><img src=\"images/trash.svg\" nosave=\"\" title=\"Supprimer\" /></a>";
      echo"</td>";

	}//end if
      echo"</tr>";
	}//end while

}//end if
?>
  </tbody>
</table>
<br />
</div>
<?php pied_page() ?>
