<?php
/// add_time.php

// Authenticate
include("session_auth.php");
 if (!auth(2))
// il faut etre au moins ITA (ou admin)
  Header("Location: login.php");

 $user_id = $_SESSION['user_id'];
 $logged_in_user = strtolower($_SESSION['logged_in_user']);
 $user_level= $_SESSION['level'];

$manip_id = $_GET[idm];
if (empty($manip_id))
 Header("Location: accueil.php");

$proj_id = $_GET[idp];
if (empty($proj_id))
 Header("Location: accueil.php");

$task_id = $_GET[idt];
if (empty($task_id))
  Header("Location: accueil.php");

require("html_functions.php");

if ( $connex = connect_db() ){
 // recupere la manip selectionnee
 $querry = "SELECT id,nom FROM manip WHERE id='$manip_id'";
 list($qh,$num) = query_db($querry);
 $manip = result_db($qh);
 // recupere la projet selectionne
 $querry = "SELECT id,nom FROM projet WHERE id='$proj_id'";
 list($qh,$num) = query_db($querry);
 $projet = result_db($qh);

$titre= $logged_in_user." (".$user_id.")<br />Voila un formulaire pour ajouter du temps &agrave; <ul>la tache :";

 // recupere la tache selectionnee
 $querry = "SELECT * FROM tache WHERE id='$task_id'";
 list($qh,$num) = query_db($querry);
 $data = result_db($qh);

 $titre.="<b>".$data['nom']. "</b><ul> du projet <b>";

$titre.=$projet[nom]. "</b><ul> de la manip <b>".$manip[nom]."</b></ul></ul></ul>";
en_tete('Ajout de temps');

echo $titre;
}//end if connex
 else
  Header("Location: accueil.php");
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">
  <tbody>

<form action="valid_time.php" method="POST" name="validTime">
  <input type="hidden" name="id_manip" value="<?php echo $manip_id ?>" >
  <input type="hidden" name="id_proj" value="<?php echo $proj_id ?>" >
  <input type="hidden" name="id_task" value="<?php echo $task_id ?>" >
  <input type="hidden" name="id_user" value="<?php echo $user_id ?>" >
    <tr>

      <td style="vertical-align: top;">utilisateur :<br />
      </td>
      <td style="vertical-align: top;" colspan ="2">
 <?php if ($user_level ==2)
   echo $logged_in_user.":".$user_id ;
 else if ($user_level ==3){ //admin
  //selection du user
  // recupere la liste des users possibles
  $querry = "SELECT id,nom FROM users WHERE level>=2 AND valid=1 ";
  list($qheq,$numeq) = query_db($querry);
 echo "<select name=\"user\" size=\"5\" >";

  while( $utilisateurs = result_db($qheq) ){

   echo "<option value=\"".$utilisateurs ['id']."\" ";
   echo ">".$utilisateurs ['nom'];echo "</option>";
  }//end while
 echo "</select>";
  } ?>
  </td>
    </tr>
    <tr>

      <td style="vertical-align: top;">date :<br />
      </td>
      <td style="vertical-align: top;" colspan ="2">
 <input type="text" name="date" value="<?php echo date('Y-m-d', time() ); ?>" >
  </td>
    </tr><tr>
      <td style="vertical-align: top;">Temps pass&eacute; :<br />
      </td>
      <td style="vertical-align: top;">
 <input type="text" name="temps" size="25" maxlength="30" value="1" ><br />
      </td>
      <td style="vertical-align: top;"> heures
      </td>
    </tr>
    <tr>

      <td style="vertical-align: top;">Remarques :<br />
      </td>
      <td style="vertical-align: top;" colspan ="2">
  <textarea name="remark" cols="50" rows=5"> </textarea>
  </td>
    </tr><tr>
      <td colspan="3" style="vertical-align: top;" align="right">
<input type="submit" name="valid" value="Valider">
      </td>
    </tr>
</form>
  </tbody>
<tbody>
 <form action="manip_maint.php?id=<?php echo $manip_id ?>" method="POST" name="annulForm">
  <tr >   <td colspan="3" style="vertical-align: top; text-align: right;">
 <input type="submit" name="annul" value="Annuler">
  </td>    </tr>
 </form>
</tbody>
</table>
<br />
<br />
</div>
<?php pied_page() ?>
