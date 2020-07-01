<?php
//list_intapp.php

// Authenticate
include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

if (empty($_GET[id]))
	Header("Location: list_app.php");
else
	$id_app=$_GET[id];

	//interrogation base de donnees

if ( $connex = connect_db() ){
	// recupere la liste de appareils
	$querry = "SELECT nom FROM appareils WHERE id=$id_app";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);
	$last_id=0;

en_tete("Liste des interventions sur l'appareil :<b>".$data['nom']."</b>");

//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="date";

echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")<br />";
?>

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	<td style="vertical-align: top; text-align: center;">
	<a href="accueil.php">Retour a<br />l'accueil</a>
	<br /></td>
	 <td style="vertical-align: top; text-align: center;">
	<a href="list_app.php">Retour a<br />liste des Appareils</a>
	<br /></td>
<?php if ( $user_level >=3 ) {	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_intapp.php?app=<?php echo $id_app; ?>">Ajout d'une<br />intervention</a>
	<br /></td>
<?php }	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="rapport_int.php?id=<?php echo $id_app; ?>">Rapport des<br />interventions</a>
	<br /></td>
	 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php">Quitter</a>
	<br /></td></tr></tbody>
</table>
<br />

<?php
echo "L'appareil <b>".$data['nom']."</b> a deja subi les interventions suivantes :<br />";
?>

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
      <th style="vertical-align: top; text-align: center;">
	Description<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_intapp.php?tri=tech">Tech</a><br />
      </th>
     <th style="vertical-align: top; text-align: center;">
	<a href ="list_intapp.php?tri=fournisseur">Fournisseur</a><br />
      </th>
    <th style="vertical-align: top; text-align: center;">
	<a href ="list_intapp.php?tri=date">date</a><br /><br />
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
<?php

	// recupere la liste de appareils
	$querry = "SELECT * FROM intervention WHERE appareil=$id_app ORDER by $tri";
	list($qh,$num) = query_db($querry);
	$last_id=0;

while ($data = result_db($qh)) {

	// remplit le tableau
 echo"<tr><td style=\"vertical-align: top;\">";
	echo $data['descr'];
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
	// date
		echo $data['date'];
      echo"</td><td style=\"vertical-align: top;\">";
	// facture
		echo $data['facture'];
      echo"</td>";

 if ( $user_level >=2 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_intapp.php?app=".$id_app."&id=".$data['id']."\"<img src=\"images/edit.png\" nosave=\"\" title=\">Modifier\"></a>";
      echo"</td>";
	}//end if
 if ( $user_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_intapp.php?id=".$data['id']."\"><img src=\"images/edittrash.png\" nosave=\"\" title=\"Supprimer\"></a>";
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
