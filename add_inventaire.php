<?php
/// add_inventaire.php
	// Authenticate
	require_once('auth-functions.php');

	if (!auth(3))
		Header("Location: login.php");

	$user_id = $_SESSION['user_id'];
	$logged_user = strtolower($_SESSION['logged_user']);

if (empty($_GET['tri'])){
	$app_id = '';
}else{
	$app_id = $_GET['id'];

}
	en_tete('Ajouter un num&eacute;ro d\'inventaire');

?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >

  <tr>
      <td style="vertical-align: top;">Num&eacute;ro d'instrument<br />
      </td>
      <td style="vertical-align: top;">

	<?php

	echo $data['id'];

				 ?>
	</select><br />
      </td>
    </tr>

    <tr>

      <td style="vertical-align: top;">Nom *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="nom" size="30"  value="<?php echo $data['nom'] ?>" ><br />
      </td>
    </tr>

<tr>
      <td style="vertical-align: top;">Mod&egrave;le<br />
      </td>
      <td style="vertical-align: top;">
      <input type="text"name="modele" size="30" value="<?php echo $data['modele'] ?>"<br />
	</td>
    </tr>
<tr>
 <td style="vertical-align: top;">Gamme<br />
      </td>
      <td style="vertical-align: top;">
     	<input type="text" name="gamme" size="10" maxlength="30" value="<?php echo $data['gamme'] ?>" ><br />

	</td>
    </tr>

    <tr>
      <td style="vertical-align: top;">&Eacute;quipe<br />
      </td>
      <td style="vertical-align: top;">
	<select name="equipe">
	<?php
	// recupere la liste des equipes
	$querry = "SELECT id, nom FROM equipe";
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
      <td style="vertical-align: top;">Fournisseur<br />
      </td>
      <td style="vertical-align: top;">
	<select name="fourn">
	<?php
	// recupere la liste des fournisseurs
	$querry = "SELECT id, nom FROM fournisseurs ";
	list($qheq,$numeq) = query_db($querry);
		while ($chef = result_db($qheq)){
			echo "<option value=\"".$chef[id]."\"";
			if ($mode=="modifier" && $chef[id] == $data['fournisseur']) {
				echo " selected";	}
			echo ">".$chef[nom]."</option>";
		}//end while
		 ?>
	</select><br />
      </td>
    </tr>

  <tr>

      <td style="vertical-align: top;">Date achat (<i>format YYYY-MM-DD</i>)<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="achat" size="10" maxlength="10" value="
		<?php if ($mode =="modifier")
			echo $data['achat'];
		else
			echo date('Y-m-d', time() );
	?>" ><br />
      </td>
    </tr><tr>

<td style="vertical-align: top;">Responsable<br />
      </td>
      <td style="vertical-align: top;">

     <select name="tech">
	<?php
	// recupere la liste des tech
	$querry = "SELECT id, nom FROM users WHERE level >1";
	list($qheq,$numeq) = query_db($querry);
		while ($chef = result_db($qheq)){
			echo "<option value=\"".$chef[id]."\"";
			if ($mode=="modifier" && $chef[id] == $data['responsable']) {
				echo " selected";	}
			echo ">".$chef[nom]."</option>";
		}//end while
		 ?>
	</select><br />
      </td>
    </tr>

  <tr>

      <td style="vertical-align: top;">R&eacute;paration<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="reparation" size="30" maxlength="30" value="<?php echo $data['reparation'] ?>" ><br />
      </td>
    </tr>
<tr>
 <td style="vertical-align: top;">Accessoires<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="accessoires" size="30" maxlength="30" value="<?php echo $data['accessoires'] ?>" ><br />
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
	<form action="equipment-list.php" method="POST" name="annulForm">
 	<tr >   <td colspan="2" style="vertical-align: top; text-align: right;">
	<input type="submit" name="annul" value="Annuler">
	 </td>    </tr>
	</form>
</tbody>
</table>
<br />
<?php }
	else
	{	Header("Location: equipment-list.php");	}	?>
<br />
</div>
<?php pied_page() ?>
