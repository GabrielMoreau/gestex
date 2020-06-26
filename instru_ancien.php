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
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")<br />";
?>
Liste des appareils pour lesquels la maintenance est enregistrée régulièrement :<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	<a href="http://intranet.legi.grenoble-inp.fr">Retour a<br />l'intranet</a>
	<br /></td>
<?php if ( $user_level >=2 ) {	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_app2.php">Ajout<br />d'un appareil</a>
	<br /></td>
 <td style="vertical-align: top; text-align: center;">
	<a href="list_fourn.php">Liste<br />des fournisseurs</a>
	<br /></td>

 <td style="vertical-align: top; text-align: center;">
	<a href="add_fourn.php">Ajout<br />d'un fournisseur</a>
	<br /></td>

<?php }	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php?variable=instru">Quitter</a>
	<br /></td> </tr></tbody>
</table>
<br />
<i>Cliquer sur le nom d'un appareil pour connaitre son modèle, sa date d'achat, ses accessoires...</i><br />

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">

 <th style="vertical-align: top; text-align: center;">
	<a href ="instru.php?tri=categorie">Catégorie<br />
      </th>

      <th style="vertical-align: top; text-align: center;">
	Nom<br />
      </th>
     

 <th style="vertical-align: top; text-align: center;">
	Gamme<br />
      </th>

      <th style="vertical-align: top; text-align: center;">
	Equipe<br />
      </th>
     
      <th style="vertical-align: top; text-align: center;">
	Fournisseur<br />
      </th>

  

<th style="vertical-align: top; text-align: center;">
	Responsable<br />
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
	$querry = "SELECT * FROM Listing order by $tri";
	list($qh,$num) = query_db($querry);
	
	$last_id=0;

while ($data = result_db($qh)) {

	// remplit le tableau

     echo"<tr><td style=\"vertical-align: top;\">";

$querry = "SELECT id, nom FROM categorie WHERE id='$data[categorie]'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);

      		echo $equip[nom];
       echo"</td><td style=\"vertical-align: top;\">";

 echo"</td><td style=\"vertical-align: top;\">";
	echo "<a href =\"fiche_vie.php?id=".$data[id]."\">". $data[nom]."</a>";
    
       echo"</td><td style=\"vertical-align: top;\">";

echo $data[gamme];

       echo"</td><td style=\"vertical-align: top;\">";

	// recupere la nom d'equipe

	$querry = "SELECT id, nom FROM equipe WHERE id='$data[equipe]'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);

      		echo $equip[nom];
       echo"</td><td style=\"vertical-align: top;\">";
	
	// recupere la nom du fournisseur
	$querry = "SELECT id, nom FROM fournisseurs WHERE id='$data[fournisseur]'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);
      		echo $equip[nom];
      echo"</td><td style=\"vertical-align: top;\">";

// recupere le nom du tech
	$querry = "SELECT id, nom FROM users WHERE id='$data[responsable]'";
	list($qheq,$numeq) = query_db($querry);

		$resp = result_db($qheq);
      		echo $resp[nom];
   

 if ( $user_level >=2 ) {	
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_app2.php?id=".$data[id]."\"<img src=\"images/edit.png\" nosave=\"\" title=\">Modifier\" /></a>";
      echo"</td>";
	}//end if
 if ( $user_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_app2.php?id=".$data[id]."\"><img src=\"images/edittrash.png\" nosave=\"\" title=\"Supprimer\" /></a>";
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
