<?php
// add_labview.php
	// Authenticate
	include("session_auth.php");

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);

//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

$app_id = $_GET[id];

if (empty($app_id)){

	// recupere la liste de appareils

	//->nouvel appareil
	$mode ="ajouter";
	$action="valid_labview.php";
//transmet la valeur de la tache a la page valid appareil
}
else{

	//->modif appareil
	$mode ="modifier";
	$action="modif_labview.php";

}

require("html_functions.php");
if ( $connex = connect_db() ){
if ($mode=="ajouter"){
	en_tete('Ajouter une manip labview');

}
else if ($mode=="modifier"){
	en_tete('Modifier les manip labview');

	// recupere l'appareil selectionne
	$querry = "SELECT * FROM labview WHERE id='$app_id'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

}
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >

 <tr>

      <td style="vertical-align: top;">Manip+chercheur *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="manipch" size="30"  value="<?php echo $data['manipch'] ?>" ><br />
      </td>
    </tr>

    <tr>

      <td style="vertical-align: top;">D&eacute;veloppeur *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="technicien" size="30"  value="<?php echo $data['technicien'] ?>" ><br />
      </td>
    </tr>

<tr>
  <td style="vertical-align: top;">Salle de la manip<br />
      </td>
      <td style="vertical-align: top;">
<input type="text" name="localisation" size="30"  value="<?php echo $data['localisation'] ?>" ><br />
      </td>

</tr>

<tr>
  <td style="vertical-align: top;">Materiel d'acquisition ou de commande *<br />
      </td>
      <td style="vertical-align: top;">
<input type="text" name="matos" size="30"  value="<?php echo $data['matos'] ?>" ><br />
      </td>

</tr>

<tr>
  <td style="vertical-align: top;">Descriptif du code *<br />
      </td>
      <td style="vertical-align: top;">
	<textarea name="code" cols="50" rows="8" > <?php echo $data['code'] ?></textarea>

</tr>

<tr>

      <td style="vertical-align: top;">Driver d'instrument<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="driver" size="30"  value="<?php echo $data['driver'] ?>" ><br />
      </td>
    </tr>

<tr>

      <td style="vertical-align: top;">Module sp&eacute;cifique Labview<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="module" size="30"  value="<?php echo $data['module'] ?>" ><br />
      </td>
    </tr>

  <tr>

      <td style="vertical-align: top;">Impression &eacute;cran<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="ecran" size="30"  value="<?php echo $data['ecran'] ?>" ><br />
      </td>
    </tr>

<tr>

      <td style="vertical-align: top;">Doc pdf<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="pdf" size="30"  value="<?php echo $data['pdf'] ?>" ><br />
      </td>
    </tr>

    <tr>
   <td style="vertical-align: top;">Les champs avec * sont &agrave;
remplir obligatoirement, les autres sont optionnels.<br />
      </td>
      <td style="vertical-align: top;" align="right">
<input type="submit" name="Login" value="<?php echo $mode ?>">
      </td>
    </tr></form>
  </tbody>
 <tbody>

	<form action="list_labview.php" method="POST" name="annulForm">
 	<tr >   <td colspan="2" style="vertical-align: top; text-align: right;">
	<input type="submit" name="annul" value="Annuler">
	 </td>    </tr>
	</form>
</tbody>
</table>
<br />

<?php }
	else
	{	Header("Location: list_labview.php");	}	?>
<br />
</div>
<?php pied_page() ?>
