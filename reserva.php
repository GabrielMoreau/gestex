<?php

// Authenticate

//include("db_functions.php");
include("session_auth.php");



//if (!auth(1))
	//Header("Location: login.php");



$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];
echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")<br />";
require("mise_en_page.php");


en_tete("Liste des prets:");


//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

$use = $_GET[user];

//recupere l'équipe
$eq=$_GET[equipe];

?>
<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	<a href="http://intranet.legi.grenoble-inp.fr/spip.php?article=16">Retour a<br />la page du service</a>
	<br /></td>



<td style="vertical-align: top; text-align: center;">
<?php if ( $use >=3 ) 	{?>
	<a href="pret1.php">Retour<br />à la liste des prets</a>
	<br /></td>
<?php }
else{	?>
<a href="pret.php">Retour<br />à la liste des prets</a>
<?php }?>
</tr></tbody>
</table>





<br />
Liste des prets : <br /><br /><br />



<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">



 

      <th style="vertical-align: top; text-align: center;">
	Nom<br />
      </th>
    
 

      <th style="vertical-align: top; text-align: center;">
	Equipe<br />
      </th>
     
       <th style="vertical-align: top; text-align: center;">
	Date<br />
      </th>
 <th style="vertical-align: top; text-align: center;">
	Retour<br />
      </th>
  
 <th style="vertical-align: top; text-align: center;">
	Commentaire<br />
      </th>
	  <th style="vertical-align: top; text-align: center;">
	Numéro de l'appareil<br />
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




$querry = "SELECT * FROM pret";
	list($qh,$num) = query_db($querry);
	
	$last_id=0;

$data = result_db($qh);





echo "<tr>";


      		
 echo"</td><td style=\"vertical-align: top;\">";


	$querry = "SELECT id, nom FROM Listing WHERE id='$data[nom]'";
	list($qheeq,$numeeq) = query_db($querry);
		$nom = result_db($qheeq);

      		echo $nom[nom];
    
 
  echo"</td><td style=\"vertical-align: top;\">";


	// recupere la nom d'equipe

	$querry = "SELECT id, nom FROM equipe WHERE id='$data[equipe]'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);

      		echo $equip[nom];
       echo"</td><td style=\"vertical-align: top;\">";
	
	
echo $data[emprunt];

	 echo"</td><td style=\"vertical-align: top;\">";
	
echo $data[retour];
	
 echo"</td><td style=\"vertical-align: top;\">";
	
echo $data[commentaire];
 echo"</td><td style=\"vertical-align: top;\">";


      		echo $nom[id];

	


	
	if ( $use >=3 ) 	{
 
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del-pret.php?id=$data[id]\"><img src=\"images/edittrash.png\" nosave=\"\" title=\"Supprimer\"></a>";
      echo"</td>";}
	

echo"</tr>";



while ($data = result_db($qh)){

	// remplit le tableau



 echo"</td><td style=\"vertical-align: top;\">";
	
   $querry = "SELECT id, nom FROM Listing WHERE id='$data[nom]'";
	list($qheeq,$numeeq) = query_db($querry);
		$nom = result_db($qheeq);

      		echo $nom[nom];

  


       echo"</td><td style=\"vertical-align: top;\">";

	// recupere la nom d'equipe

	$querry = "SELECT id, nom FROM equipe WHERE id='$data[equipe]'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);

      		echo $equip[nom];
       echo"</td><td style=\"vertical-align: top;\">";
	
echo $data[emprunt];

	 echo"</td><td style=\"vertical-align: top;\">";
	
echo $data[retour];
	
 echo"</td><td style=\"vertical-align: top;\">";
	
echo $data[commentaire];
echo"</td><td style=\"vertical-align: top;\">";
	
      		echo $nom[id];

   

 
 if ( $use >=3 ) 	{
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del-pret.php?id=$data[id]\"><img src=\"images/edittrash.png\" nosave=\"\" title=\"Supprimer\"></a>";
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
</body>
</html>
