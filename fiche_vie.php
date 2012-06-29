<?php
//fiche_vie.php

// Authenticate
include("session_auth.php");

//if (!auth(1))
	//Header("Location: login.php");


$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("mise_en_page.php");

if (empty($_GET[id]))
	Header("Location: instru.php");
else 
	$id_app=$_GET[id];

	//interrogation base de données

if ( $connex = connect_db() ){
	// recupere la liste de appareils
	$querry = "SELECT nom FROM Listing WHERE id=$id_app";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);
	$last_id=0;


en_tete("Caractéristiques de l'appareil :<b>".$data[nom]."</b>");


//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")<br />";
?>


<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr> 
	<td style="vertical-align: top; text-align: center;">
<? php//permet de retourner à la page précedente?>
	<a href="<?php echo $_SERVER['HTTP_REFERER']?>">Retour à la liste</a>

	<br /></td>

	
<?php if ( $user_level >=2 ) {	?>

 
	 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php?variable=instru">Quitter</a>

<?php } ?>
	<br /></td></tr></tbody>
</table>
<br />

<?php
echo "L'appareil <b>".$data[nom]."</b> a les caractéristiques suivantes :<br />";
?>

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
      <th style="vertical-align: top; text-align: center;">
		Nom<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Modele<br />
      </th>
     <th style="vertical-align: top; text-align: center;">
	Achat<br />
      </th>
    <th style="vertical-align: top; text-align: center;">
	Accessoires<br /><br />
      </th>

  <th style="vertical-align: top; text-align: center;">
	Réparation / Etalonnages<br /><br />
      </th>

<th style="vertical-align: top; text-align: center;">
	Responsable<br />
      </th>


<th style="vertical-align: top; text-align: center;">
	Numéro d'instrument<br />
      </th>
<th style="vertical-align: top; text-align: center;">
	Inventaire<br />
      </th>



    
<?php if ( $user_level >=2 ) 	
		echo "</th><th>";
	if ( $user_level >=3 ) 	
		echo "</th><th>";
	  ?>
    </tr>
<?php

	// recupere la liste de appareils
	$querry = "SELECT * FROM Listing where id=$id_app ";
	list($qh,$num) = query_db($querry);
	$last_id=0;

while ($data = result_db($qh)) {

	// remplit le tableau
 echo"<tr><td style=\"vertical-align: top;\">";
	echo $data[nom];
         echo"</td><td style=\"vertical-align: top;\">";
	echo $data[modele];
   echo"</td><td style=\"vertical-align: top;\">";
	echo $data[achat];
echo"</td><td style=\"vertical-align: top;\">";
	echo $data[accessoires];
echo"</td><td style=\"vertical-align: top;\">";
	echo $data[reparation];

 echo"</td><td style=\"vertical-align: top;\">";

// recupere le nom du tech
	$querry = "SELECT id, nom FROM users WHERE id='$data[responsable]'";
	list($qheq,$numeq) = query_db($querry);

		$resp = result_db($qheq);
      		echo $resp[nom];

 

  echo"</td><td style=\"vertical-align: top;\">";
echo $data[id];

  echo"</td><td style=\"vertical-align: top;\">";
echo $data[inventaire];




 if ( $user_level >=2 ) {	
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_app2.php?app=".$id_app."&id=".$data[id]."\"<img src=\"images/edit.png\" nosave title=\">Modifier\"></a>";
      echo"</td>";
	}//end if
 if ( $user_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_intapp.php?id=".$data[id]."\"><img src=\"images/edittrash.png\" nosave title=\"Supprimer\"></a>";
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
</body>
</html>
