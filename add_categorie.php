<?php
/// add_categorie.php
	// Authenticate
	include("session_auth.php");

	if (!auth(3))
		Header("Location: login.php");

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);

$app_id = $_GET[id];
if (empty($app_id)){
	//->nouvelle categorie
	$mode ="ajouter";
	$action="valid_categorie.php";
}

require("mise_en_page.php");
if ( $connex = connect_db() ){
if ($mode=="ajouter"){
	en_tete("Voila un formulaire pour ajouter une categorie");

}

	// recupere le appareil selectionné
	$querry = "SELECT * FROM categorie WHERE id='$cat_id'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);


	
}
?>
 

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >

  <tr>
    
      <td style="vertical-align: top;">categorie *<br>
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="categorie" size="30"  value="<?php echo $data[categorie] ?>" ><br>
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
	<form action="instru.php" method="POST" name="annulForm">
 	<tr >   <td colspan="2" style="vertical-align: top; text-align: right;">
	<input type="submit" name="annul" value="Annuler">
	 </td>    </tr> 
	</form>  
</tbody>
</table>
<br>


</div>
<?php pied_page() ?>
</body>
</html>

