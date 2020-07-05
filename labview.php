<?php

include("session_auth.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

en_tete("Liste des programmes Labview");

//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";
?>

<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	<a href="<?php GESTEX_URL_SERVICE ?></a>">Retour &agrave;<br />la page du service</a>
	<br /></td>

 <td style="vertical-align: top; text-align: center;">
	<a href="add_labview.php">Ajouter<br />une manip Labview</a>
	<br /></td>

<?php if ( $user_level >=2 ) {
?>

 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php?variable=instru">Quitter</a>
<?php }	?>

	<br /></td> </tr></tbody>
</table>

<br />
Liste des manip labview en cours : <br />

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">

 <th style="vertical-align: top; text-align: center;">
	Manip + chercheur<br />
      </th>

<th style="vertical-align: top; text-align: center;">
	Developpeur<br />
      </th>

      <th style="vertical-align: top; text-align: center;">
	 Salle de la manip<br />
      </th>
     <th style="vertical-align: top; text-align: center;">
	Mat&eacute;riel d'acquisition ou de commande<br />
      </th>

 <th style="vertical-align: top; text-align: center;">
	Descriptif du code<br />
      </th>

      <th style="vertical-align: top; text-align: center;">
	Driver d'instrument<br />
      </th>

<th style="vertical-align: top; text-align: center;">
	Module sp&eacute;cifique Labview<br />
      </th>

<th style="vertical-align: top; text-align: center;">
	Impression &eacute;cran+doc pdf manip<br />
      </th>

<?php if ( $user_level >=2 )
		echo "</th><th>";
	if ( $user_level >=3 )
		echo "</th><th>";
	  ?>
    </tr>

<?php	//interrogation base de donnees

if ( $connex = connect_db() ){

	$querry = "SELECT * FROM labview order by '$tri'";
	list($qh,$num) = query_db($querry);

	$last_id=0;
}

$data = result_db($qh);

echo "<tr>";
 echo"</td><td style=\"vertical-align: top;\">";
	echo $data['manipch'];

       echo"</td><td style=\"vertical-align: top;\">";
echo $data['technicien'];

  echo"</td><td style=\"vertical-align: top;\">";

echo $data['localisation'];

  echo"</td><td style=\"vertical-align: top;\">";
echo $data['matos'];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data['code'];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data['driver'];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data['module'];
 echo"</td><td style=\"vertical-align: top;\">";

$dossier_lab ="data/labview/".$data['manipch'];

	//remplace les espaces par des underscore
	$dossier_lab = str_replace(" ", "_", $dossier_lab);
	// cherche l'existence de ce dossier
	//echo $dossier_lab;
	/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
	if (@opendir($dossier_lab) != FALSE){
		//si trouve ajoute un bouton
		echo "Voir : <a href =\"doclabview.php?id=". $data['id']."\">".$data['manipch']."<img src=\"images/eye.svg\" nosave=\"\" title =\"Voir la face avant du programme\"></a><br />";
    }

      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_labview.php?id=$data['id']\"><img src=\"images/pen.svg\" nosave=\"\" title=\">Modifier\"></a>";
      echo"</td>";

      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_labview.php?id=$data['id']\"><img src=\"images/trash.svg\" nosave=\"\" title=\"Supprimer\"></a>";
      echo"</td>";

echo"</tr>";

while ($data = result_db($qh)) {

	// remplit le tableau

     echo"<tr><td style=\"vertical-align: top;\">";
echo $data['manipch'];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data['technicien'];
echo"</td><td style=\"vertical-align: top;\">";
echo $data['localisation'];
echo"</td><td style=\"vertical-align: top;\">";

echo $data['matos'];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data['code'];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data['driver'];

 echo"</td><td style=\"vertical-align: top;\">";
echo $data['module'];

 echo"</td><td style=\"vertical-align: top;\">";

///bouton lien vers la doc
	$dossier_lab ="data/labview/".$data['manipch'];

	//remplace les espaces par des underscore
	$dossier_lab = str_replace(" ", "_", $dossier_lab);
	// cherche l'existence de ce dossier
	//echo $dossier_lab;
	/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
	if (@opendir($dossier_lab) != FALSE){
		//si trouve ajoute un bouton
		echo "Voir : <a href =\"doclabview.php?id=". $data['id']."\">".$data['manipch']."<img src=\"images/eye.svg\" nosave=\"\" title =\"Voir ce projet\"></a><br />";
    }
//echo $data['ecran'];

      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_labview.php?id=$data['id']\"><img src=\"images/pen.svg\" nosave=\"\" title=\">Modifier\"></a>";
      echo"</td>";

      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_labview.php?id=$data['id']\"><img src=\"images/trash.svg\" nosave=\"\" title=\"Supprimer\"></a>";
      echo"</td>";

echo"</tr>";

	}//end while

?>

  </tbody>
</table>
<br />
</div>
<?php pied_page() ?>
