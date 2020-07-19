<?php
/// add_proj.php
	// Authenticate
	include("auth-functions.php");

	if (!auth(2))
		Header("Location: login.php");

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);

$manip_id = $_GET[idm];
if (empty($manip_id))
	Header("Location: accueil.ph");

$proj_id = $_GET[idp];
if (empty($proj_id)){
	//->nouveau projet
	$mode ="ajouter";
	$action="valid_proj.php";
}
else{
	//->modif projet
	$mode ="modifier";
	$action="modif_proj.php";

}

require_once('html-functions.php');

if ( $connex = connect_db() ){

	// recupere le manip selectionnee
	$querry = "SELECT nom FROM manip WHERE id='$manip_id'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

if ($mode=="ajouter"){
	$titre = "Voila un formulaire pour inscrire une nouveau projet dans la manip ".$data['nom'];
	en_tete($titre);

}
else if ($mode=="modifier"){
	$titre = "Voila un formulaire pour modifier les caracteristiques du projet".$data['nom'];
	// recupere le projet selectionne
	$querry = "SELECT * FROM projet WHERE id='$proj_id'";
	list($qh,$num) = query_db($querry);
	$projs = result_db($qh);

	$titre.=" de la manip ".$projs[nom];
	en_tete( $titre);

	}
	}//end if connex
	else
		Header("Location: list_manip.php");
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
  <form action="<?php echo $action ?>" method="POST" name="inscrForm">
 		<input type="hidden" name="id_manip" value="<?php echo $manip_id ?>" >
		<input type="hidden" name="id_proj" value="<?php echo $proj_id ?>" >
 <tr>

      <td style="vertical-align: top;">Nom *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="nom" size="20" maxlength="20" value="<?php echo $projs[nom] ?>" ><br />
      </td>
    </tr><tr>
      <td style="vertical-align: top;">Description<br />
      </td>
      <td style="vertical-align: top;">
	<textarea name="descr" cols="50" rows=5"><?php echo $projs[descr] ?> </textarea>
      </td>
    </tr>

    <tr>
      <td style="vertical-align: top;">Date <i>format:YYYY-MM-JJ</i><br />
      </td>
      <th style="vertical-align: top;">
	<input type="text" name="date" size="10" maxlength="10" value="<?php
				if ($mode=="modifier")
					echo $data['date'];
				else  //ajout->aujourd'hui
					echo date('Y-m-d', time() );
						?> " ><br />
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
	<form action="manip_maint.php?id=<?php echo $manip_id ?>" method="POST" name="annulForm">
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
