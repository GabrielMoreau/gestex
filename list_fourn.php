<?php
//list_fourn.php

// Authenticate
include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

en_tete("Liste de tous les fournisseurs:");

//recuper la methode de tri
$tri = $_GET['tri'];
if (empty($tri))
	$tri ="nom";
?>

<br />
<table cellpadding="2" cellspacing="2" border="0"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	<a href="accueil.php?tri=nom">Retour a l'accueil</a>
	<br /></td>
<?php if ( $user_level >=2 ) {	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_fourn.php">Ajout d'un fournisseur</a>
	<br /></td>
<?php }	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="find_fourn.php">Rechercher</a>
	<br /></td>
 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php">Quitter</a>
	<br /></td> </tr></tbody>
</table>
<br />

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_fourn.php?tri=nom">Nom</a><br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Adresse<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	T&eacute;l&eacute;phone<br />
      </th>
      <th style="vertical-align: top; text-align: center; " >
	Fax<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Courriel<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	WWW<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Contacts<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Description<br />
      </th>
<?php if ( $user_level >=2 )
		echo"</th><th>";
	if ( $user_level >=3 )
		echo"</th><th>";
	  ?>
    </tr>
<?php	//interrogation base de donnees

if ( $pdo = connect_db() ){
	// recupere la liste de fournisseurs
// 	$querry = "SELECT * FROM fournisseurs order by $tri";
// 	list($qh,$num) = query_db($querry);
      // 	$last_id=0;
      $sql = 'SELECT * from fournisseurs ORDER by ?';
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($tri));
      $fournisseur = $stmt->fetchAll(PDO::FETCH_ASSOC);
      // var_dump($fournisseur);
// while ($data = result_db($qh)) {
      foreach( $fournisseur as $data){

	// remplit le tableau
 echo"<tr><td style=\"vertical-align: top;\">";
	echo $data['nom'];
      echo"</td><td style=\"vertical-align: top;\">";
	echo $data['adresse'];
       echo"</td><td style=\"vertical-align: top;\" nowrap>";
      echo $data['tel'];
       echo"</td><td style=\"vertical-align: top;\" nowrap>";
      echo $data['fax'];
      echo"</td><td style=\"vertical-align: top;\">";
	if (!empty($data['mail']))
     	 echo "<a href=\"mailto:".$data['mail']."\"><img src=\"images/mail_generic.png\" nosave=\"\" ></a>";
      echo"</td><td style=\"vertical-align: top;\">";
	if (!empty($data['www']))
 		 echo "<a href=\"http://".$data['www']."\" target=\"_fournView\"><img src=\"images/html.png\" nosave=\"\" width=\"22\"></a>";

      echo"</td><td style=\"vertical-align: top;\">";
         echo  $data['contact'];

      echo"</td><td style=\"vertical-align: top;\">";
      echo $data['descr'];
      echo"</td>";
 if ( $user_level >=2 && $data['nom']!="aucun" ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_fourn.php?id=".$data['id']."\"><img src=\"images/pen.svg\" nosave=\"\" title=\">Modifier\"></a>";
      echo"</td>";
	}//end if
 if ( $user_level >=3 && $data['nom']!="aucun" ) {
      echo '</td><td style="vertical-align: top;">';
      echo '<a href="del_fourn.php?id='.$data['id'].'"><img src="images/trash.svg" nosave="" title="Supprimer"></a>';
      echo '</td>';

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
