<?php
/// rapport.php

// Authenticate
include("session_auth.php");
	if (!auth(2))
		Header("Location: login.php");

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);

 if (!empty($_GET[ide]))
	$equip_id = $_GET[ide];
else
	$equip_id =0;
 if (!empty($_GET[idm]))
	$manip_id = $_GET[idm];
else
	$manip_id =0;

 if (!empty($_GET[idp]))
	$projet_id = $_GET[idp];
else
	$projet_id =0;

	$tache_id =0;

require("html_functions.php");

if ( $connex = connect_db() ){
	// recupere l'equip selectionnée
	$querry = "SELECT id,nom FROM equipe";
	list($qhe,$num) = query_db($querry);

	if ($equip_id!=0){

	// recupere la manip selectionnée
	$querry = "SELECT id,nom FROM manip WHERE equipe='$equip_id'";
	list($qhm,$num) = query_db($querry);

	if ($manip_id!=0){
		// recupere les projet selectionné
		$querry = "SELECT id,nom FROM projet WHERE manip='$manip_id'";
		list($qhp,$num) = query_db($querry);
		//$projet_list = result_db($qh);

		if ($projet_id!=0){
			// recupere la tache selectionnée
			$querry = "SELECT id,nom FROM tache WHERE projet='$projet_id'";
			list($qht,$num) = query_db($querry);
			//$tache_list = result_db($qh);
		}
	}
}
en_tete("Création de rapport");

$texte = $logged_in_user." (".$user_id.") Voila un formulaire pour créér un rapport<br />";
echo $texte;

}//end if connex
	else
		Header("Location :accueil.php");
?>

<br />
<table cellpadding="2" cellspacing="2" border="0"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr class=menu>
	 <td style="vertical-align: top; text-align: center;">
	<a href="accueil.php">Retour a l'accueil</a>
	<br /></td>

 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php?variable=projet">Quitter</a>
	<br /></td> </tr></tbody>
</table>
<br />

<table cellpadding="2" cellspacing="2" border="0" style="text-align: left; width: 75%;" align="center">
  <tbody>

<form action="print_rapport.php" method="POST" name="rappForm">
    <tr>

      <td style="vertical-align: top;">pour l'<b>&Eacute;quipe</b><br /><i>choisissez dans la liste</i><br />
   </td>
      <td style="vertical-align: top;">
	<select name="equipe"  size="5" onchange="location.href='rapport.php?ide=' + this.options[this.selectedIndex].value;" >
  <?php 	///
		echo "<option value=\"0\"";
			if ($equip_id == 0)	echo " selected ";
		echo ">Toutes</option>";

		while(	$equip_list= result_db($qhe)	){
			///remplissage avec liste des equips
			echo "<option value=\"".$equip_list['id']."\" ";
				if ($equip_list['id'] == $equip_id )
						echo " selected ";
			echo ">".$equip_list['nom'];echo "</option>";
		}//end while	?>
    </td>    </tr>
    <tr>

      <td style="vertical-align: top;">pour la <b>manip</b> de cette equipe<br />
   </td>
      <td style="vertical-align: top;">
	<select name="manip"  size="5" onchange="location.href='rapport.php?ide=' + equipe.options[equipe.selectedIndex].value + '&idm=' + this.options[this.selectedIndex].value;" >
  <?php 	///
		echo "<option value=\"0\"";
			if ($manip_id == 0)	echo " selected ";
		echo ">Toutes</option>";
	if ($equip_id != 0)

		while(	$manip_list= result_db($qhm)	){
			///remplissage avec liste des manips
			echo "<option value=\"".$manip_list['id']."\" ";
				if ($manip_list['id'] == $manip_id )
						echo " selected ";
			echo ">".$manip_list['nom'];echo "</option>";
		}//end while
	?>
    </td>    </tr>
	<tr>
      <td style="vertical-align: top;">pour le <b>Projet</b> de cette manip<br />
      </td>
      <td style="vertical-align: top;">
	<select name="projet"  size="5" onchange="location.href='rapport.php?ide=' + equipe.options[equipe.selectedIndex].value + '&idm=' + manip.options[manip.selectedIndex].value + '&idp=' + this.options[this.selectedIndex].value;" >
  <?php
		echo "<option value=\"0\"";
			if ($projet_id == 0)	echo " selected ";
		echo">Tous</option>";
	if ($manip_id != 0)
		while(	$projet_list= result_db($qhp)	){
			///remplissage avec liste des projets
			echo "<option value=\"".$projet_list['id']."\" ";
				if ($projet_list['id'] == $projet_id )
						echo " selected ";
			echo ">".$projet_list['nom'];echo "</option>";
		}//end while	?>
  </td>    </tr>

	<tr>
      <td style="vertical-align: top;">pour la <b>Tachet</b> de ce projet<br />
      </td>
      <td style="vertical-align: top;">
	<select name="tache"  size="5" >
  <?php
		echo "<option value=\"0\"";
			if ($tache_id == 0)	echo " selected ";
		echo ">Toutes</option>";
	if ($projet_id != 0)
		while(	$tache_list= result_db($qht)	){
			///remplissage avec liste des taches
			echo "<option value=\"".$tache_list['id']."\" ";
				if ($tache_list['id'] == $tache_id )
						echo " selected ";
			echo ">".$tache_list['nom'];echo "</option>";
		}//end while	?>
  </td>    </tr>

    <tr>
      <td style="vertical-align: top;">Depuis la Date<br /><i>format:YYYY-MM-JJ<br />0 : depuis toujours...</i>
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="date" size="10" maxlength="10" value="<?php
				  //ajout->aujourd'hui par defaut
					echo date('Y-m-d', time() );
						?> " ><br />
      </td>
    </tr>

      <td colspan="2" style="vertical-align: top;" align="right">
<input type="submit" name="valid" value="Valider">
      </td>
    </tr></form>
  </tbody>
<tbody>
	<form action="accueil.php" method="POST" name="annulForm">
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
