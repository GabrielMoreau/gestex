<?php
/// add_fourn.php
	// Authenticate
	include("session_auth.php");

	if (!auth(2))
		Header("Location: login.php");

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);

$fourn_id = $_GET[id];
if (empty($fourn_id)){
	//on vient depuis index.html
	//->nouvelle inscription
	$mode ="ajouter";
	$action="valid_fourn.php";
}
else{
	//on vient depuis accueil.php
	//->modif coordonnées

	$mode ="modifier";
	$action="modif_fourn.php";

}

require("html_functions.php");
if ($mode=="ajouter"){
	en_tete("Voila un formulaire pour inscrire un fournisseur");

}
else if ($mode=="modifier"){
	en_tete("Voila un formulaire pour modifier les coordonn&eacute;es d'un fournisseur");

if ( $connex = connect_db() ){
	// recupere le fournisseur selectionné
	$querry = "SELECT * FROM fournisseurs WHERE id='$fourn_id'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

	}
	else
		Header("Location :accueil.php");
}
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_fourn" value="<?php echo $fourn_id ?>" >
    <tr>

      <td style="vertical-align: top;">Nom *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="nom" size="50" maxlength="50" value="<?php echo $data['nom'] ?>" ><br />
      </td>
    </tr><tr>
      <td style="vertical-align: top;">Adresse<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="adresse" size="50" maxlength="50" value="<?php echo $data['adresse'] ?>" ><br />
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Adresse courriel *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="addr_mail" size="50" maxlength="50" value="<?php echo $data['mail'] ?>" ><br />
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">T&eacute;l&eacute;phone<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="phone" size="15" maxlength="15" value="<?php echo $data['tel'] ?>" ><br />
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Fax<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="fax" size="15" maxlength="15" value="<?php echo $data['fax'] ?>" ><br />
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">URL<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="www" size="50" maxlength="50" value="<?php echo $data['www'] ?>" ><br />
      </td>
    </tr>
     <tr>
      <td style="vertical-align: top;">Contact(s)<br />
      nom, fonction, telephone....</td>
      <td style="vertical-align: top;">
	<textarea name="contact" cols="50" rows="5"><?php echo $data['contact'] ?> </textarea>
      </td>
    </tr>
     <tr>
      <td style="vertical-align: top;">Description<br />
	pour faciliter la recherche de fournisseurs, il serait bon d'utiliser des mots stanadards (capteurs, moteur, profilés...)
      </td>
      <td style="vertical-align: top;">
	<textarea name="descr" cols="50" rows="5"> <?php echo $data['descr'] ?></textarea>
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
	<form action="list_fourn.php" method="POST" name="annulForm">
 	<tr >   <td colspan="2" style="vertical-align: top; text-align: right;">
	<input type="submit" name="annul" value="Annuler">
	 </td>    </tr>
	</form>
</tbody>
</table>
<br />
<br />

</div>
<?php pied_page() ?>
</body>

</html>
