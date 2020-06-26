<?php

// Authenticate

include("session_auth.php");



if (!auth(1))
	Header("Location: login.php");



$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");


en_tete("Liste des demandes en cours:");


//recuper la methode de tri
if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];


//recup�re la categorie
// $cat=$_GET['categorie'];
if (empty($_GET['categorie']))
	$cat ="";
else
	$cat = $_GET['categorie'];
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
	T�che<br />
      </th>

      <th style="vertical-align: top; text-align: center;">
	Nom du demandeur<br />
      </th>
     <th style="vertical-align: top; text-align: center;">
	D�tails<br />
      </th>


 <th style="vertical-align: top; text-align: center;">
	Date de demande<br />
      </th>

      <th style="vertical-align: top; text-align: center;">
	Avancement<br />
      </th>
     
     
<th style="vertical-align: top; text-align: center;">
	Termin�?<br />
      </th>

  
<th style="vertical-align: top; text-align: center;">
	Pi�ces jointes<br />
      </th>


<?php if ( $user_level >=2 ) 	
		echo "</th><th>";
	if ( $user_level >=3 ) 	
		echo "</th><th>";
	  ?>
    </tr>
<?php	//interrogation base de donn�es

if ( $pdo = connect_db() ){
	


	$sql = 'SELECT * FROM demandes where termine="non";';
	// list($qh,$num) = query_db($querry);
	
	// $last_id=0;
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// $data = result_db($qh);


// echo "<tr>";
//  echo"</td><td style=\"vertical-align: top;\">";
// 	echo $demandes[0]['tache'];
    
//        echo"</td><td style=\"vertical-align: top;\">";
// echo $demandes[0]['nomdemandeur'];

//   echo"</td><td style=\"vertical-align: top;\">";

// echo $demandes[0]['details'];
      
//   echo"</td><td style=\"vertical-align: top;\">";
// echo $demandes[0]['achat'];
//  echo"</td><td style=\"vertical-align: top;\">";
// echo $demandes[0]['avancement'];
//  echo"</td><td style=\"vertical-align: top;\">";
// echo $demandes[0]['termine'];
//  echo"</td><td style=\"vertical-align: top;\">";
// echo $demandes[0]['piecesjointes'];

	
	
// 	 if ( $user_level >=2) {	
//       echo"</td><td style=\"vertical-align: top;\">";
//       echo "<a href=\"add_demandes.php?id=$demandes[0][id]\"><img src=\"images/edit.png\" nosave=\"\" title=\">Modifier\" /></a>";
//       echo"</td>";
// 	}//end if
//  if ( $user_level >=3 ) {
//       echo"</td><td style=\"vertical-align: top;\">";
//       echo "<a href=\"del_demandes.php?id=$demandes[0][id]\"><img src=\"images/edittrash.png\" nosave=\"\" title=\"Supprimer\" /></a>";
//       echo"</td>";
	
// }
// echo"</tr>";



// while ($data = result_db($qh)) {
	foreach($demandes as $data){

	// remplit le tableau

     echo"<tr><td style=\"vertical-align: top;\">";
echo $data['tache'];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data['nomdemandeur'];
echo"</td><td style=\"vertical-align: top;\">";
echo $data['details'];
echo"</td><td style=\"vertical-align: top;\">";

echo $data['achat'];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data['avancement'];
 echo"</td><td style=\"vertical-align: top;\">";
echo $data['termine'];

 echo"</td><td style=\"vertical-align: top;\">";
echo $data['piecesjointes'];

	
	///bouton lien vers la doc
	$dossier_proj ="data/instru/demandes/".$data['tache'];
	

	//remplace les espaces par des underscore
	$dossier_proj = str_replace(" ", "_", $dossier_proj);
	// cherche l'existence de ce dossier
	//echo $dossier_proj;
	/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
	if (@opendir($dossier_proj) != FALSE){
		//si trouv� ajoute un bouton
		echo "Voir : <a href =\"jointdemandes.php?id=". $data['id']."\">".$data['tache']."<img src=\"images/filefind.png\" nosave=\"\" title =\"Voir ce projet\" /></a><br />";
    
	}



       
   

 if ( $user_level >=2) {	
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_demandes.php?id=$data[id]\"><img src=\"images/edit.png\" nosave=\"\" title=\"Modifier\" /></a>";
      echo"</td>";
	}//end if
 if ( $user_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_demandes.php?id=$data[id]\"><img src=\"images/edittrash.png\" nosave=\"\" title=\"Supprimer\" /></a>";
      echo"</td>";
	
}
echo"</tr>";
	


	



      


	}//end foreach





?>

   
  </tbody>
</table>
<br />
</div>
<?php pied_page() ?>
</body>
</html>
