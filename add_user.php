<?php
/// inscription.php
// Authenticate
	include("session_auth.php");

///on peut se logger autrement qu'en admin!
/// pour une demande d'inscription
	auth(3);

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);
	$user_level= $_SESSION['level'];

$user2ch_id = $_GET[id];
if (empty($user2ch_id)){
	//->nouvelle inscription
	$mode ="ajouter";
	$action="valid_inscr.php";
}
else{
	//->modif coordonnées
	
	$mode ="modif";
	$action="modif_inscr.php";

}

require("html_functions.php");
	if ( $connex = connect_db() ){
if ($mode=="ajouter"){
	en_tete("Voila un formulaire pour inscrire un utilisateur");

}
else if ($mode=="modif"){
	en_tete("Voila un formulaire pour modifier vos coordonn&eacute;es ");

		// recupere la liste des users
		$querry = "SELECT * FROM users WHERE id='$user2ch_id'";
		list($qh,$num) = query_db($querry);
		$data = result_db($qh);

}

echo "id:".$user_id ." : ".$logged_in_user." lvl:".$user_level
?>

<form action="<?php echo $action ?>" method="POST" name="inscrForm">
	<input type="hidden" name="user2ch_id"  value="<?php echo $user2ch_id ?>" >
<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">
  <tbody>
    <tr>
      <td style="vertical-align: top;">Nom de loggin *<br />
      </td>
      <td style="vertical-align: top;">
	<?php if ($mode=="ajouter"){ ?>
	<input type="text" name="loggin" size="25" maxlength="25" value="" ><br />
	<?php } 
		else {
			// on ne change pas le loggin
			echo $logged_in_user."<br />";
 	}	?>
      </td>
    </tr>
    <?php if ($mode=="ajouter"){ ?>
	<tr>
      <td style="vertical-align: top;">mot de passe *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="password" name="password" size="25" maxlength="25" value="<?php echo $data['password'] ?>" ><br />
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">mot de passe (pour confirmer) *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="password" name="password2" size="25" maxlength="25" value="<?php echo $data['password'] ?>" ><br />
      </td>
    </tr>
 	<?php } //end if mode ?>
 <tr>
      <td style="vertical-align: top;">Nom *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="nom" size="25" maxlength="25" value="<?php echo $data['nom'] ?>" ><br />
      </td>
    </tr><tr>
      <td style="vertical-align: top;">Prenom<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="prenom" size="25" maxlength="25" value="<?php echo $data['prenom'] ?>" ><br />
      </td>
    </tr>  
    <tr>
      <td style="vertical-align: top;">Adresse mail *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="addr_mail" size="25" maxlength="50" value="<?php echo $data['email'] ?>" ><br />
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Telephone<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="phone" size="10" maxlength="10" value="<?php echo $data['tel'] ?>" ><br />
      </td>
    </tr>
     <tr>
      <td style="vertical-align: top;">Equipe<br />
      </td>
	<?php // recupere la liste des equipes
		$querry = "SELECT id,nom FROM equipe";
		list($qheq,$numeq) = query_db($querry);
		?>
      <td style="vertical-align: top;">
		<select  name="equipe">
		<?php 	
		while(	$equipes = result_db($qheq)	){

			echo "<option value=\"".$equipes['id']."\"";
			///selectionne la bonne equipe
			if ( $equipes['id'] == $data['equipe'])
				echo " selected ";
			echo ">".$equipes['nom']."</option>";
		}//end while	?>

		</select><br />
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Qualit&eacute;<br />
      </td>
      <td style="vertical-align: top;">
	<?php  if ($user_level==3 || !isset($user_level) ){ //admin loggé ou premiere inscription: modif possible
	 ?>
	<input type="radio" name="level" value="0" <?php if ($data['level']==0) echo "checked=\"checked\"" ?> >Etudiant<br />
	<input type="radio" name="level" value="1" <?php if ($data['level']==1) echo "checked=\"checked\"" ?> >Chercheur<br />
	<input type="radio" name="level" value="2" <?php if ($data['level']==2) echo "checked=\"checked\"" ?> >ITA<br />
	<?php }	
	if (isset($user_level) && $user_level==3) { ?>
		<input type="radio" name="level" value="3" <?php if ($data['level']==3) echo "checked=\"checked\"" ?> >Admin<br />
	<?php }
	if ( isset($user_level) && ($user_level < 3 )){ ///consultation seulement
		switch($data['level']){
			case 0: echo "Etudiant<br />";break;
			case 1: echo "Chercheur<br />";break;
			case 2: echo "ITA<br />";break;
			case 3: echo "Admin<br />";
		}
	} ?>
      </td>
    </tr>
    <tr>
   <td style="vertical-align: top;">les champs avec * sont &agrave;
remplir obligatoirement, les autres sont optionnels.<br />
      </td>
      <td style="vertical-align: top;" align="right">
<input type="submit" name="Login" value="<?php echo $mode; ?>" >
      </td>
    </tr>
  </tbody>
</table></form>	<?php	}
			else //if connect_db
		{	Header("Location :list_users.php");exit(0);	}	?>
<br />
<br />
</div>
<?php pied_page() ?>
