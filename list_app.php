<?php
//list_app.php

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
$tri = $_GET[tri];
if (empty($tri))
	$tri ="nom";

echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")<br />";
?>
Liste des appareils pour lesquels la maintenance est enregistrée régulièrement :<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	<a href="accueil.php">Retour a<br />l'accueil</a>
	<br /></td>
<?php if ( $user_level >=3 ) {	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_app.php">Ajout<br />d'un appareil</a>
	<br /></td>
<?php }	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php">Quitter</a>
	<br /></td> </tr></tbody>
</table>
<br />
<i>Cliquer sur le nom d'un appareil pour voir la liste des interventions qu'il a subi...</i><br />

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_app.php?tri=nom">Nom</a><br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Description<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_app.php?tri=equipe">Equipe</a><br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_app.php?tri=tech">Responsable</a><br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_app.php?tri=fournisseur">Fournisseur</a><br />
      </th>
    <th style="vertical-align: top; text-align: center;">
	date AChat<br />
      </th>
    <th style="vertical-align: top; text-align: center;">
	facture<br />
      </th>
<?php if ( $user_level >=2 ) 	
		echo "</th><th>";
	if ( $user_level >=3 ) 	
		echo "</th><th>";
	  ?>
    </tr>
<?php	//interrogation base de données

if ( $connex = connect_db() ){
	// recupere la liste de appareils
	$querry = "SELECT * FROM appareils order by $tri";
	list($qh,$num) = query_db($querry);
	
	$last_id=0;

while ($data = result_db($qh)) {

	// remplit le tableau
 echo"<tr><td style=\"vertical-align: top;\">";
	echo "<a href =\"list_intapp.php?id=".$data['id']."\">". $data['nom']."</a>";
      echo"</td><td style=\"vertical-align: top;\">";
	echo $data['descr'];
       echo"</td><td style=\"vertical-align: top;\">";
	// recupere la nom d'equipe
	$querry = "SELECT id, nom FROM equipe WHERE id=".$data['equipe'];
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);
      		echo $equip[nom];
       echo"</td><td style=\"vertical-align: top;\">";
	// recupere la nom du tech
	$querry = "SELECT id, nom FROM users WHERE id=".$data['tech'];
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);
      		echo $equip[nom];
       echo"</td><td style=\"vertical-align: top;\">";
	// recupere la nom du fournisseur
	$querry = "SELECT id, nom FROM fournisseurs WHERE id=".$data['fournisseur'];
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);
      		echo $equip[nom];
      echo"</td><td style=\"vertical-align: top;\">";
	// date achat
		echo $data['achat'];
      echo"</td><td style=\"vertical-align: top;\">";
	// facture
		echo $data['facture'];
      echo"</td>";

 if ( $user_level >=2 ) {	
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_app.php?id=".$data['id']."\"<img src=\"images/edit.png\" nosave=\"\" title=\">Modifier\"></a>";
      echo"</td>";
	}//end if
 if ( $user_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_app.php?id=".$data['id']."\"><img src=\"images/edittrash.png\" nosave=\"\" title=\"Supprimer\"></a>";
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
