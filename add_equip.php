<?php
/// add_equip.php
	// Authenticate
	include("session_auth.php");

	if (!auth(3))
		Header("Location: login.php");

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);

$equip_id = $_GET[id];
if (empty($equip_id)){
	//->nouvelle inscription
	$mode ="ajouter";
	$action="valid_equip.php";
}
else{
	//->modif coordonnées
	$mode ="modifier";
	$action="modif_equip.php";

}

require("html_functions.php");
if ( $connex = connect_db() ){
if ($mode=="ajouter"){
	en_tete("Voila un formulaire pour inscrire une equipe");

}
else if ($mode=="modifier"){
	en_tete("Voila un formulaire pour modifier les coordonn&eacute;es d'une equipe");

	// recupere le fournisseur selectionné
	$querry = "SELECT * FROM equipe WHERE id='$equip_id'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

}
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_equip" value="<?php echo $equip_id ?>" >
    <tr>

      <td style="vertical-align: top;">Nom *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="nom" size="10" maxlength="10" value="<?php echo $data['nom'] ?>" ><br />
      </td>
    </tr><tr>
      <td style="vertical-align: top;">Description<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="descr" size="25" maxlength="255" value="<?php echo $data['descr'] ?>" ><br />
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Compte *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="compte" size="5" maxlength="5" value="<?php echo $data['compte'] ?>" ><br />
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Chef d'&Eacute;quipe<br />
      </td>
      <td style="vertical-align: top;">
<?php echo $data['chef']; ?>
	<select name="chef">
	<?php
	// recupere laliste des chercheurs
	$querry = "SELECT id, nom FROM users WHERE level =1";
	list($qheq,$numeq) = query_db($querry);
		while ($chef = result_db($qheq)){
			echo "<option value=\"".$chef[id]."\"";
			if ($mode=="modifier" && $chef[id] == $data['chef']) {
				echo " selected";	}
			echo ">".$chef[nom]."</option>";
		}//end while
		 ?>
	</select><br />
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
	<form action="list_equip.php" method="POST" name="annulForm">
 	<tr >   <td colspan="2" style="vertical-align: top; text-align: right;">
	<input type="submit" name="annul" value="Annuler">
	 </td>    </tr>
	</form>
</tbody>
</table>
<br />
<?php }
	else
	{	Header("Location :accueil.php");	}	?>
<br />
</div>
<?php pied_page() ?>
