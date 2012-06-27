<?php
//ind_fourn.php

// Authenticate
include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");


$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

en_tete("Rechercher un fournisseur:");


//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="nom";

unset($find_nom); unset($find_descr);//recupere les criteres de recherhe
if (isset($_POST[nom]))
//	$find_nom ="";
//else
	$find_nom = $_POST[nom];

if (isset($_POST[descr]))
//	$find_descr ="";
//else
	$find_descr = $_POST[descr];

echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")";
?>
<br>
<table cellpadding="2" cellspacing="2" border="0"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	<a href="accueil.php">Retour a l'accueil</a>
	<br></td>
 <td style="vertical-align: top; text-align: center;">
	<a href="list_fourn.php">Liste des fournisseurs</a>
	<br></td> 
<?php if ( $user_level >=2 ) {	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_fourn.php">Ajout d'un fournisseur</a>
	<br></td>
<?php }	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php">Quitter</a>
	<br></td> </tr></tbody>
</table>
<br>

<!--------  table criteres de recherche ----->
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
      <th style="vertical-align: top; text-align: center;">
	le Nom contient <br>
      </th>
     <th style="vertical-align: top; text-align: center;">
	la description contient <br>
      </th> <th>   </th></tr>
	<form action="find_fourn.php" method="POST" name="findForm">
	<tr><td>
	<input type="text" name="nom" size="50" maxlength="50" value="<?php echo $find_nom; ?>">
	</td><td>
	<input type="text" name="descr" size="50" maxlength="50" value="<?php echo $find_descr; ?>"> 
	</td><td>
	<input type="submit" name="find" value="Rechercher">
	
	</td></tr></form>  
</tbody>
</table>
<br>  

<?php
if (isset($find_nom) || isset($find_descr)){
	echo "Resultat de la recherche pour";
	if ( !empty($find_nom))
		echo " nom :".$find_nom; 
	if ( !empty($find_descr) )
		echo " description :".$find_descr; 
	}	
?>
<br>
<!--------  table resultats ----->
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_fourn.php?tri=nom">Nom</a><br>
      </th>
      <th style="vertical-align: top; text-align: center;">
	Adresse<br>
      </th>
      <th style="vertical-align: top; text-align: center;">
	Telephone<br>
      </th>
      </th>
      <th style="vertical-align: top; text-align: center;">
	Fax<br>
      </th>
      <th style="vertical-align: top; text-align: center;">
	Email <br>
      </th>
      <th style="vertical-align: top; text-align: center;">
	WWW <br>
      </th>
      <th style="vertical-align: top; text-align: center;">
	Contacts <br>
      </th>
      <th style="vertical-align: top; text-align: center;">
	Description <br>
      </th>
<?php if ( $user_level >=2 ) {	?>
	</th><th></th><th>
<?php	 }  ?>
    </tr>
<?php	//interrogation base de données

if ( $connex = connect_db() ){
	// recupere la liste de fournisseurs repondant aux criteres de recherche
	if (isset($find_nom) && isset($find_descr))
		{ // criteres non vides
	$querry = "SELECT * FROM fournisseurs WHERE";
	if (isset($find_nom) && !empty($find_nom))
		$querry .=" nom LIKE '%$find_nom%'";
	if (isset($find_nom)&& !empty($find_nom) && isset($find_descr) && !empty($find_descr))
		$querry .=" AND ";
 	if (isset($find_descr)&& !empty($find_descr))
		$querry .=" descr LIKE '%$find_descr%'";
	$querry .=";";
	list($qh,$num) = query_db($querry);
	
	$last_id=0;

while ($data = result_db($qh)) {

	// remplit le tableau
 echo"<tr><td style=\"vertical-align: top;\">";
	echo $data[nom];
      echo"</td><td style=\"vertical-align: top;\">";
	echo $data[adresse];
       echo"</td><td style=\"vertical-align: top;\" nowrap>";
      echo $data[tel];
       echo"</td><td style=\"vertical-align: top;\" nowrap>";
      echo $data[fax];
     echo"</td><td style=\"vertical-align: top;\">";
         echo "<a href=\"mailto:".$data[mail]."\"> <img src=\"images/mail_generic.png\" nosave ></a>";

      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"http://".$data[www]."\" target=\"_fournView\"><img src=\"images/html.png\" nosave width=\"22\"> </a>";
      echo"</td><td style=\"vertical-align: top;\">";
      echo  $data[contact];
      echo"</td><td style=\"vertical-align: top;\">";
      echo $data[descr];
      echo"</td>";
 if ( $user_level >=2 && $data[nom]!="aucun" ) {	
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_fourn.php?id=".$data[id]."\"><img src=\"images/edit.png\" nosave title=\">Modifier\"></a>";
      echo"</td>";
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_fourn.php?id=".$data[id]."\"><img src=\"images/edittrash.png\" nosave title=\"Supprimer\"></a>";
      echo"</td>";
	
	}//end if
      echo"</tr>";
	}//end while
  }//end if  non vides
}//end if connect
?>
  </tbody>
</table>
<br>
</div>
<?php pied_page() ?>
</body>
</html>
