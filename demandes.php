<?php

// Authenticate

include("session_auth.php");



if (!auth(1))
	Header("Location: login.php");



$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("mise_en_page.php");


en_tete("Liste des demandes en cours:");


//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

//recupère la categorie
$cat=$_GET[categorie];
//echo "$cat";


echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")<br />";
?>
<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	<a href="http://intranet.legi.grenoble-inp.fr">Retour a<br />l'intranet</a>
	<br /></td>

 <td style="vertical-align: top; text-align: center;">
	<a href="add_demandes.php">Ajouter<br />une demande</a>
	<br /></td>

<td style="vertical-align: top; text-align: center;">
	<a href="historique_demandes.php">Historique<br />des demandes</a>
	<br /></td>


<?php if ( $user_level >=2 ) {	
?>

 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php?variable=instru">Quitter</a>
<?php }	?>

	<br /></td> </tr></tbody>
</table>





<br />
Liste des demandes en cours : <br />




<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">



 <th style="vertical-align: top; text-align: center;">
	Tâche<br />
      </th>

      <th style="vertical-align: top; text-align: center;">
	Nom du demandeur<br />
      </th>
     <th style="vertical-align: top; text-align: center;">
	Détails<br />
      </th>


 <th style="vertical-align: top; text-align: center;">
	Date de demande<br />
      </th>

      <th style="vertical-align: top; text-align: center;">
	Avancement<br />
      </th>
     
     
<th style="vertical-align: top; text-align: center;">
	Terminé?<br />
      </th>

  
<th style="vertical-align: top; text-align: center;">
	Pièces jointes<br />
      </th>


<?php if ( $user_level >=2 ) 	
		echo "</th><th>";
	if ( $user_level >=3 ) 	
		echo "</th><th>";
	  ?>
    </tr>
<?php	//interrogation base de données

if ( $connex = connect_db() ){
	


	$querry = "SELECT * FROM demandes where termine='non'";
	list($qh,$num) = query_db($querry);
	
	$last_id=0;
}


$data = result_db($qh);

echo "<tr>";
 echo"</td><td style=\"vertical-align: top;\">";
	echo $data[tache];
    
       echo"</td><td style=\"vertical-align: top;\">";
echo $data[nomdemandeur];

  echo"</td><td style=\"vertical-align: top;\">";

echo $data[details];
      
  echo"</td><td style=\"vertical-align: top;\">";
echo $data[achat];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data[avancement];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data[termine];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data[piecesjointes];

	
	
	 if ( $user_level >=2) {	
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_demandes.php?id=$data[id]\"><img src=\"images/edit.png\" nosave=\"\" title=\">Modifier\" /></a>";
      echo"</td>";
	}//end if
 if ( $user_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_demandes.php?id=$data[id]\"><img src=\"images/edittrash.png\" nosave=\"\" title=\"Supprimer\" /></a>";
      echo"</td>";
	
}
echo"</tr>";



while ($data = result_db($qh)) {

	// remplit le tableau

     echo"<tr><td style=\"vertical-align: top;\">";
echo $data[tache];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data[nomdemandeur];
echo"</td><td style=\"vertical-align: top;\">";
echo $data[details];
echo"</td><td style=\"vertical-align: top;\">";

echo $data[achat];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data[avancement];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data[termine];

 echo"</td><td style=\"vertical-align: top;\">";
echo $data[piecesjointes];

	
	///bouton lien vers la doc
	$dossier_proj ="data/instru/demandes/".$data[tache];
	

	//remplace les espaces par des underscore
	$dossier_proj = str_replace(" ", "_", $dossier_proj);
	// cherche l'existence de ce dossier
	//echo $dossier_proj;
	/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
	if (@opendir($dossier_proj) != FALSE){
		//si trouvé ajoute un bouton
		echo "Voir : <a href =\"jointdemandes.php?id=". $data[id]."\">".$data[tache]."<img src=\"images/filefind.png\" nosave=\"\" title =\"Voir ce projet\" /></a><br />";
    
	}



       
   

 if ( $user_level >=2) {	
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_demandes.php?id=$data[id]\"><img src=\"images/edit.png\" nosave=\"\" title=\">Modifier\" /></a>";
      echo"</td>";
	}//end if
 if ( $user_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_demandes.php?id=$data[id]\"><img src=\"images/edittrash.png\" nosave=\"\" title=\"Supprimer\" /></a>";
      echo"</td>";
	
}
echo"</tr>";
	


	



      


	}//end while





?>

   
  </tbody>
</table>
<br />
</div>
<?php pied_page() ?>
</body>
</html>
