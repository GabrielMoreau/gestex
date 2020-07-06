<?php

// Authenticate

include("db_functions.php");

//if (!auth(1))
	//Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

en_tete('Liste des appareils');

//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

//recupere la categorie
$cat=$_GET[categorie];
//echo "$cat";

//recupere l'equipe
$eq=$_GET[equipe];
echo "$eq";
?>

<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
 <a href="<?php echo GESTEX_URL_SERVICE ?>">Retour &agrave;<br />la page du service</a>
	<br /></td>
<?php
if (empty($eq))
{
?>
 <td style="vertical-align: top; text-align: center;">
	<a href="list_fourn1.php">Liste<br />des fournisseurs</a>
	<br /></td>

<?php
}
?>

<td style="vertical-align: top; text-align: center;">
	<a href="essai1.php">Retour<br />aux cat&eacute;gories</a>
	<br /></td>
<?php
if (empty($eq))
{
?>

<td style="vertical-align: top; text-align: center;">
	<a href="login.php?variable=instru">Acc&egrave;s<br />restreint</a>
	<br /></td>
<?php
}
?>

</tr></tbody>
</table>

<br />
Liste des appareils : <br />
<i>Cliquer sur le nom d'un appareil pour conna&icirc;tre son mod&egrave;le, sa date d'achat, ses accessoires...</i><br />

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">

<?php

?>

 <th style="vertical-align: top; text-align: center;">
	Cat&eacute;gorie<br />
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

if ( $connex = connect_db() ){
	// recupere la liste de appareils

if ((!empty($cat))||(!empty($eq)))

{

$querry = "SELECT * FROM Listing WHERE (equipe='$eq'||categorie='$cat') ORDER BY categorie;";
	list($qh,$num) = query_db($querry);

	$last_id=0;

}

if ((empty($cat))&&(empty($eq)))
{
	$querry = "SELECT * FROM Listing ORDER BY categorie ";
	list($qh,$num) = query_db($querry);

	$last_id=0;
}

$data = result_db($qh);

echo "<tr>";

     echo"<td style=\"vertical-align: top;\">";
$querry = "SELECT id, nom FROM categorie WHERE id='$data['categorie']'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);
      		echo $equip[nom];
 echo"</td><td style=\"vertical-align: top;\">";
	echo "<a href =\"fiche_vie.php?id=".$data['id']."\">". $data['nom']."</a>";

       echo"</td><td style=\"vertical-align: top;\">";
echo $data['modele'];

  echo"</td><td style=\"vertical-align: top;\">";

echo $data['gamme'];

       echo"</td><td style=\"vertical-align: top;\">";

	// recupere le nom d'equipe

	$querry = "SELECT id, nom FROM equipe WHERE id='$data['equipe']'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);

      		echo $equip[nom];
       echo"</td><td style=\"vertical-align: top;\">";

	// recupere le nom du fournisseur
	$querry = "SELECT id, nom FROM fournisseurs WHERE id='$data['fournisseur']'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);
      		echo $equip[nom];

  echo"</td><td style=\"vertical-align: top;\">";

	///bouton lien vers la doc
	$dossier_proj ="data/instru/".$data['nom'];

	//remplace les espaces par des underscore
	$dossier_proj = str_replace(" ", "_", $dossier_proj);
	// cherche l'existence de ce dossier
	//echo $dossier_proj;
	/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
	if (@opendir($dossier_proj) != FALSE){
		//si trouve ajoute un bouton
		echo "Voir : <a href =\"notice.php?id=". $data['id']."\">".$data['nom']."<img src=\"images/eye.svg\" nosave=\"\" title =\"Voir ce projet\" /></a><br />";

	}
if (!empty($eq))
{
 echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add-pret.php?id=$data['id']\"><img src=\"images/box-arrow-in-down.svg\" nosave=\"\" title=\">Demande de pr&ecirc;t\" /></a>";
      echo"</td>";
}
	 if ( $user_level >=2) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_app2.php?id=$data['id']\"><img src=\"images/pen.svg\" nosave=\"\" title=\">Modifier\" /></a>";
      echo"</td>";
	}//end if
 if ( $user_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_app2.php?id=$data['id']\"><img src=\"images/trash.svg\" nosave=\"\" title=\"Supprimer\" /></a>";
      echo"</td>";

}
echo"</tr>";

while (($data = result_db($qh))){

	// remplit le tableau

     echo"<tr><td style=\"vertical-align: top;\">";
$querry = "SELECT id, nom FROM categorie WHERE id='$data['categorie']'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);

      		echo $equip[nom];

 echo"</td><td style=\"vertical-align: top;\">";
	echo "<a href =\"fiche_vie.php?id=".$data['id']."\">". $data['nom']."</a>";

       echo"</td><td style=\"vertical-align: top;\">";

echo $data['modele'];

  echo"</td><td style=\"vertical-align: top;\">";

echo $data['gamme'];

       echo"</td><td style=\"vertical-align: top;\">";

	// recupere le nom d'equipe

	$querry = "SELECT id, nom FROM equipe WHERE id='$data['equipe']'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);

      		echo $equip[nom];
       echo"</td><td style=\"vertical-align: top;\">";

	// recupere le nom du fournisseur
	$querry = "SELECT id, nom FROM fournisseurs WHERE id='$data['fournisseur']'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);
      		echo $equip[nom];

  echo"</td><td style=\"vertical-align: top;\">";
$querry = "SELECT id, nom FROM categorie WHERE id='$data['categorie']'";
	list($qheq,$numeq) = query_db($querry);
		$cat = result_db($qheq);

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
		echo "Voir : <a href =\"notice.php?id=". $data['id']."\">".$data['nom']."<img src=\"images/eye.svg\" nosave=\"\" title =\"Voir ce projet\" /></a><br />";

	}

  if (!empty($eq))

{
echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add-pret.php?id=$data['id']\"><img src=\"images/box-arrow-in-down.svg\" nosave=\"\" title=\">Demande de pr&ecirc;t\" /></a>";
     echo"</td>";
}

 if ( $user_level >=2) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_app2.php?id=$data['id']\"><img src=\"images/pen.svg\" nosave=\"\" title=\">Modifier\" /></a>";
      echo"</td>";
	}//end if
 if ( $user_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_app2.php?id=$data['id']\"><img src=\"images/trash.svg\" nosave=\"\" title=\"Supprimer\" /></a>";
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
