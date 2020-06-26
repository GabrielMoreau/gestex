<?php
// add_demandes.php
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
	$action="valid_demandes.php";
//transmet la valeur de la tache à la page valid appareil
}
else{

	//->modif appareil
	$mode ="modifier";
	$action="modif_demandes.php";

}

require("html_functions.php");
if ( $connex = connect_db() ){
if ($mode=="ajouter"){
	en_tete("Voila un formulaire pour ajouter une demande");

}
else if ($mode=="modifier"){
	en_tete("Voila un formulaire pour modifier les demandes");

	// recupere le appareil selectionné
	$querry = "SELECT * FROM demandes WHERE id='$app_id'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

	
}
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >

 

 <tr>
    
      <td style="vertical-align: top;">Tâche *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="tache" size="30"  value="<?php echo $data[tache] ?>" ><br />
      </td>
    </tr>

    <tr>
    
      <td style="vertical-align: top;">Nom du demandeur *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="nomdemandeur" size="30"  value="<?php echo $data[nomdemandeur] ?>" ><br />
      </td>
    </tr>

<tr>
  <td style="vertical-align: top;">Details<br />
      </td>
      <td style="vertical-align: top;">
	<textarea name="details" cols="100" rows="5"> <?php echo $data[details] ?>
	</textarea>
</tr>
    
  <tr>
    
      <td style="vertical-align: top;">Date  *<i>format YYYY-MM-DD</i><br />
      </td>
      <th style="vertical-align: top;">
	<input type="text" name="achat" size="10" maxlength="10" value="<?php
 if ($mode =="modifier")
			echo $data[achat];
		else 
			echo date('Y-m-d', time() );
	?>" ><br />
      </td>
    </tr>

  <tr>
    
      <td style="vertical-align: top;">Avancement*<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="avancement" size="70"  value="<?php echo $data[avancement] ?>" ><br />
      </td>
    </tr>

 <tr>
  

      <td style="vertical-align: top;">Terminé?<br />
      </td>
      <td style="vertical-align: top;">

<SELECT NAME="termine" multiple size=2>
 <OPTION VALUE="oui">Oui
 <OPTION VALUE="non">Non
</SELECT> <P>
	
      </td>
    </tr>

  <tr>
    
      <td style="vertical-align: top;">Pièces Jointes<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="piecesjointes" size="30" maxlength="30" value="<?php echo $data[piecesjointes] ?>" ><br />
      </td>
    </tr>

    <tr>
   <td style="vertical-align: top;">les champs avec * sont &agrave;
remplir obligatoirement, les autres sont optionnels.<br />
      </td>
      <td style="vertical-align: top;" align="right">
<input type="submit" name="Login" value="<?php echo $mode ?>">
      </td>
    </tr></form>
  </tbody>
 <tbody>

	<form action="demandes.php"method="POST" name="annulForm">
 	<tr >   <td colspan="2" style="vertical-align: top; text-align: right;">
	<input type="submit" name="annul" value="Annuler">
	 </td>    </tr> 
	</form>  
</tbody>
</table>
<br />

<?php }
	else 
	{	Header("Location :demandes.php");	}	?>
<br />
</div>
<?php pied_page() ?>
