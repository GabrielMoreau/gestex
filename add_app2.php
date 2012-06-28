<?php
/// add_app2.php
	// Authenticate
	include("session_auth.php");

	if (!auth(3))
		Header("Location: login.php");

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);

//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

$cat=$_GET[categorie];
//echo "$cat";
//recupère la catégorie de la page categorie
$app_id = $_GET[id];


if (empty($app_id)){

	// recupere la liste de appareils
if ( $connex = connect_db() ){


$querry = "SELECT * FROM categorie where id='$cat'" ;
	list($qh,$num) = query_db($querry);
	$last_id=0;
$datax = result_db($qh);}

	//->nouvel appareil
	$mode ="ajouter";
	$action="valid_app2.php?categorie=".$datax[id]."";
//transmet la valeur de la categorie à la page valid appareil
}
else{


	//->modif appareil
	$mode ="modifier";
	$action="modif_app2.php?categorie=".$datax[id]."";

}

require("mise_en_page.php");
if ( $connex = connect_db() ){
if ($mode=="ajouter"){
	en_tete("Voila un formulaire pour ajouter un appareil");

}
else if ($mode=="modifier"){
	en_tete("Voila un formulaire pour modifier les caracteristiques d'un appareil");

	// recupere le appareil selectionné
	$querry = "SELECT * FROM Listing WHERE id='$app_id'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);


	
}
?>

 <td style="vertical-align: top; text-align: center;">
	<a href="add_categorie.php?">Ajout<br>d'une categorie</a>
	<br></td>




<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >

 

  <tr>
      <td style="vertical-align: top;">Categorie<br>
      </td>
      
  <td style="vertical-align: top;">




<select name="categorie">
//listing des categories
<?php
	$querry = "SELECT id, nom FROM categorie order by nom ";
	list($qheq,$numeq) = query_db($querry);
		while ($chef = result_db($qheq)){
			echo "<option value=\"".$chef[id]."\"";
			if ($mode=="modifier" && $chef[id] == $data[categorie]) {
				echo " selected";	}
if ($mode=="ajouter" && $chef[id] == $cat) {
				echo " selected";	}

//si on choisit ajouter, le listing présélectionne la categorie

			echo ">".$chef[nom]."</option>";
		}//end while


	?>
</select>
<br>
      </td>
    </tr>



    <tr>
    
      <td style="vertical-align: top;">Nom *<br>
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="nom" size="30"  value="<?php echo $data[nom] ?>" ><br>
      </td>
    </tr>

<tr>
      <td style="vertical-align: top;">Modele *<br>
      </td>
      <td style="vertical-align: top;">
      <input type="text"name="modele" size="30" value="<?php echo $data[modele] ?>"<br>
	</td>
    </tr>  
<tr>
 <td style="vertical-align: top;">Gamme *<br>
      </td>
      <td style="vertical-align: top;">
     	<input type="text" name="gamme" size="10" maxlength="30" value="<?php echo $data[gamme] ?>" ><br>

	</td>
    </tr>  
 

    <tr>
      <td style="vertical-align: top;">Equipe *<br>
      </td>
      <td style="vertical-align: top;">
	<select name="equipe">
	<?php 
	// recupere la liste des equipes
	$querry = "SELECT id, nom FROM equipe order by nom";
	list($qheq,$numeq) = query_db($querry);
		while ($chef = result_db($qheq)){
			echo "<option value=\"".$chef[id]."\"";
			if ($mode=="modifier" && $chef[id] == $data[equipe]) {
				echo " selected";	}
			echo ">".$chef[nom]."</option>";
		}//end while
		 ?>
	</select><br>
      </td>
    </tr>

  <tr>
      <td style="vertical-align: top;">Fournisseur *<br>
      </td>
      <td style="vertical-align: top;">
	<select name="fourn">
	<?php 
	// recupere la liste des fournisseurs
	$querry = "SELECT id, nom FROM fournisseurs order by nom ";
	list($qheq,$numeq) = query_db($querry);
		while ($chef = result_db($qheq)){
			echo "<option value=\"".$chef[id]."\"";
			if ($mode=="modifier" && $chef[id] == $data[fournisseur]) {
				echo " selected";	}
			echo ">".$chef[nom]."</option>";
		}//end while
		 ?>
	</select><br>
      </td>
    </tr>

    
  <tr>
    
      <td style="vertical-align: top;">Date achat *<i>format YYYY-MM-DD</i><br>
      </td>
      <th style="vertical-align: top;">
	<input type="text" name="achat" size="10" maxlength="10" value="<?php
 if ($mode =="modifier")
			echo $data[achat];
		else 
			echo date('Y-m-d', time() );
	?>" ><br>
      </td>
    </tr><tr>

<td style="vertical-align: top;">Responsable *<br>
      </td>
      <td style="vertical-align: top;">

     <select name="tech">
	<?php 
	// recupere la liste des tech
	$querry = "SELECT id, nom FROM users WHERE level >1";
	list($qheq,$numeq) = query_db($querry);
		while ($chef = result_db($qheq)){
			echo "<option value=\"".$chef[id]."\"";
			if ($mode=="modifier" && $chef[id] == $data[responsable]) {
				echo " selected";	}
			echo ">".$chef[nom]."</option>";
		}//end while
		 ?>
	</select><br>
      </td>
    </tr>

  <tr>
    
      <td style="vertical-align: top;">Reparation *<br>
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="reparation" size="30" maxlength="30" value="<?php echo $data[reparation] ?>" ><br>
      </td>
    </tr>
<tr>
 <td style="vertical-align: top;">Accessoires *<br>
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="accessoires" size="30" maxlength="30" value="<?php echo $data[accessoires] ?>" ><br>
      </td>
    </tr>

<tr>
 <td style="vertical-align: top;">Inventaire (facultatif)<br>
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="inventaire" size="30" maxlength="30" value="<?php echo $data[inventaire] ?>" ><br>
      </td>
    </tr>

<tr>
 <td style="vertical-align: top;">Notice (facultatif)<br>
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="notice" size="30" maxlength="30" value="<?php echo $data[notice] ?>" ><br>
      </td>
    </tr>



    <tr>
   <td style="vertical-align: top;">les champs avec * sont &agrave;
remplir obligatoirement, les autres sont optionnels.<br>
      </td>
      <td style="vertical-align: top;" align="right">
<input type="submit" name="Login" value="<?php echo $mode ?>">
      </td>
    </tr></form>
  </tbody>
 <tbody>

	<form action="instru.php"method="POST" name="annulForm">
 	<tr >   <td colspan="2" style="vertical-align: top; text-align: right;">
	<input type="submit" name="annul" value="Annuler">
	 </td>    </tr> 
	</form>  
</tbody>
</table>
<br>


<?php }
	else 
	{	Header("Location :instru.php");	}	?>
<br>
</div>
<?php pied_page() ?>
</body>
</html>
