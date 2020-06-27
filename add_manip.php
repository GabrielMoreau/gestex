<?php
/// add_manip.php
//ajout/modif manip
	// Authenticate
	include("session_auth.php");

	if (!auth(2))
		Header("Location: login.php");

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);

$manip_id = $_GET[id];
if (empty($manip_id)){
	//->nouvelle manip
	$mode ="ajouter";
	$action="valid_manip.php";
}
else{
	//->modif manip

	$mode ="modifier";
	$action="modif_manip.php";

}

require("html_functions.php");

if ( $connex = connect_db() ){

if ($mode=="ajouter"){
	en_tete("Voila un formulaire pour inscrire une nouvelle manip");

}
else if ($mode=="modifier"){
	en_tete("Voila un formulaire pour modifier les caracteristiques d'une manip");

	// recupere la manip selectionnée
	$querry = "SELECT * FROM manip WHERE id='$manip_id'";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);

	}
}//end if connex
	else 
		Header("Location :accueil.php");
?>
 

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
 <form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_manip" value="<?php echo $manip_id ?>" >
   <tr>
    
      <td style="vertical-align: top;">Nom *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="nom" size="20" maxlength="20" value="<?php echo $data['nom'] ?>" ><br />
      </td>
    </tr><tr>
      <td style="vertical-align: top;">Description<br />
      </td>
      <td style="vertical-align: top;">
	<textarea name="descr" cols="50" rows="5"> <?php echo $data['descr'] ?>
	</textarea>
      </td>
    </tr>  
    <tr>
      <td style="vertical-align: top;">Local *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="local" size="5" maxlength="10" value="<?php echo $data['local'] ?>" ><br />
      </td>
    </tr>
    <tr>
	<?php // recupere la liste des equipes
		$querry = "SELECT id,nom FROM equipe order by nom";
		list($qheq,$numeq) = query_db($querry);
		?>
      <td style="vertical-align: top;">&Eacute;quipe <i> qui utilisera la manip</i><br />
      </td>
      <td style="vertical-align: top;">
	<select name="equipe"   >
		<?php 	
		while(	$equipes = result_db($qheq)	){

			echo "<option value=\"".$equipes['id']."\"";
			if ($equipes['id'] == $data['equipe']){
				echo " selected";
				}
			echo ">".$equipes['nom']."</option>";
		}//end while	?>
	</select>
      </td>
    </tr>
    <tr>
	<?php // recupere la liste des chercheurs
		$querry = "SELECT id,nom FROM users WHERE level=1 AND valid=1 order by nom";
		list($qheq,$numeq) = query_db($querry);
		?>
      <td style="vertical-align: top;">Chercheur <i>( qui commande la manip)</i><br />
      </td>
      <td style="vertical-align: top;">
	<select name="cherch"   >
		<?php 	
		while(	$equipes = result_db($qheq)	){

			echo "<option value=\"".$equipes['id']."\"";
			if ($equipes['id'] == $data['chercheur']){
				echo " selected";
				}
			echo ">".$equipes['nom']."</option>";
		}//end while	?>
	</select>
      </td>
    </tr><tr>
	<?php // recupere la liste des chercheurs
		$querry = "SELECT id,nom FROM users WHERE level<=2 AND valid=1 order by nom ";
		list($qheq,$numeq) = query_db($querry);
		?>
	<td style="vertical-align: top;">Chercheur/Etudiant <i>( qui utilisera la manip)</i><br />
      </td>
      <td style="vertical-align: top;">
	<select name="cherch_bis"   >
		<?php 	
		while(	$equipes = result_db($qheq)	){

			echo "<option value=\"".$equipes['id']."\"";
			if ($equipes['id'] == $data['chercheur_bis']){
				echo " selected";
				}
			echo ">".$equipes['nom']."</option>";
		}//end while	?>
	</select>
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
   <td style="vertical-align: top;">les champs avec * sont &agrave;
remplir obligatoirement, les autres sont optionnels.<br />
      </td>
      <td style="vertical-align: top;" align="right">
<input type="submit" name="Login" value="<?php echo $mode ?>">
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
