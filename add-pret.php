<?php
/// add_pret.php
	// Authenticate
	include("session_auth.php");

	//if (!auth(3))
		//Header("Location: login.php");

	//$user_id = $_SESSION['user_id'];
	//$logged_in_user = strtolower($_SESSION['logged_in_user']);

//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

$pret=$_GET[id];

	//->nouvel appareil
	$mode ="ajouter";
	$action="valid-pret.php";
//transmet la valeur de la categorie à la page valid appareil

require("html_functions.php");
if ( $connex = connect_db() ){
if ($mode=="ajouter"){
	en_tete("Voila un formulaire pour ajouter un pret");

}
else if ($mode=="modifier"){
	en_tete("Voila un formulaire pour modifier les prets d'un appareil");

	// recupere le appareil selectionné
	$querry = "SELECT * FROM Listing WHERE id='$app_id'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

}
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >

  <tr>
      <td style="vertical-align: top;">Nom<br />
      </td>

  <td style="vertical-align: top;">

<select name="nom">
//listing des appareils
<?php
	$querry = "SELECT id, nom FROM Listing where (equipe=15 && id='$pret') ";
	list($qheq,$numeq) = query_db($querry);
		while ($chef = result_db($qheq)){
			echo "<option value=\"".$chef[id]."\"";

if ($mode=="ajouter" )

			echo ">".$chef[nom]."</option>";
		}//end while

	?>
</select>
<br />
      </td>
    </tr>

    <tr>
      <td style="vertical-align: top;">&Eacute;quipe *<br />
      </td>
      <td style="vertical-align: top;">
	<select name="equipe">
	<?php
	// recupere la liste des equipes
	$querry = "SELECT id, nom FROM equipe order by nom";
	list($qheq,$numeq) = query_db($querry);
		while ($chef = result_db($qheq)){
			echo "<option value=\"".$chef[id]."\"";
			if ($mode=="modifier" && $chef[id] == $data['equipe']) {
				echo " selected";	}
			echo ">".$chef[nom]."</option>";
		}//end while
		 ?>
	</select><br />
      </td>
    </tr>

  <tr>

      <td style="vertical-align: top;">Date demande pret *<i>format YYYY-MM-DD</i><br />
      </td>
      <th style="vertical-align: top;">
	<input type="text" name="emprunt" size="10" maxlength="10" value="<?php
 if ($mode =="modifier")
			echo $data['emprunt'];
		else
			echo date('Y-m-d', time() );
	?>" ><br />
      </td>
    </tr>

  <tr>

      <td style="vertical-align: top;">Date de retour estimée *<i>format YYYY-MM-DD</i><br />
 </td>
      <td style="vertical-align: top;">
<input type="text" name="retour" size="10" maxlength="10" value="<?php
 if ($mode =="modifier")
			echo $data['retour'];
		else
			echo date('Y-m-d', time() );
	?>" ><br />

      </td>

    </tr>
<tr>
 <td style="vertical-align: top;">Commentaire<br />
      </td>
<td style="vertical-align: top;">
<input type="text" name="commentaire" size="30" maxlength="30" value="<?php echo $data['commentaire'] ?>" ><br />

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

	<form action="essai1.php"method="POST" name="annulForm">
 	<tr >   <td colspan="2" style="vertical-align: top; text-align: right;">
	<input type="submit" name="annul" value="Annuler">
	 </td>    </tr>
	</form>
</tbody>
</table>
<br />

<?php }
	else
	{	Header("Location :instru.php");	}	?>
<br />
</div>
<?php pied_page() ?>
