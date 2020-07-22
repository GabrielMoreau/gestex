<?php
/// add_task.php

// Authenticate
require_once('auth-functions.php');
 if (!auth(2))
  Header("Location: login.php");

 $user_id = $_SESSION['user_id'];
 $logged_user = strtolower($_SESSION['logged_user']);

$manip_id = $_GET[idm];
if (empty($manip_id))
 Header("Location: list_manip.php");

$proj_id = $_GET[idp];
if (empty($proj_id))
 Header("Location: list_manip.php");

$task_id = $_GET[idt];
if (empty($task_id)){

 //->nouvelle tache
 $mode ="ajouter";
 $action="valid_task.php";
}
else{

 //->modif coordonnees
 $mode ="modifier";
 $action="modif_task.php";

}

require_once('html-functions.php');

if ( $connex = connect_db() ){
 // recupere la manip selectionnee
 $querry = "SELECT id,nom FROM manip WHERE id='$manip_id'";
 list($qh,$num) = query_db($querry);
 $manip = result_db($qh);
 // recupere la projet selectionne
 $querry = "SELECT id,nom FROM projet WHERE id='$proj_id'";
 list($qh,$num) = query_db($querry);
 $projet = result_db($qh);

$texte = $logged_user." (".$user_id.") Voila un formulaire pour ".$mode;

if ($mode=="ajouter"){
 $titre.="inscrire une nouvelle tache au projet ";
 $texte.= " une nouvelle tache au projet <b>";
}
else if ($mode=="modifier"){
 $titre.="modifier les caracteristiques de la tache ";
 $texte.= " la tache <b>";

 // recupere la tache selectionnee
 $querry = "SELECT * FROM tache WHERE id='$task_id'";
 list($qh,$num) = query_db($querry);
 $data = result_db($qh);

 $texte.=$data['nom']. "</b> du projet <b>";
 }

en_tete($titre);

$texte.=$projet[nom]. "</b> de la manip <b>".$manip[nom]."</b>";

echo $texte;

}//end if connex
 else
  Header("Location: list_manip.php");
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">
  <tbody>

<form action="<?php echo $action ?>" method="POST" name="inscrForm">
  <input type="hidden" name="id_manip" value="<?php echo $manip_id ?>" >
  <input type="hidden" name="id_proj" value="<?php echo $proj_id ?>" >
  <input type="hidden" name="id_task" value="<?php echo $task_id ?>" >
  <input type="hidden" name="id_user" value="<?php echo $user_id ?>" >

    <tr>

      <td style="vertical-align: top;">Nom *<br />
      </td>
      <td colspan="2" style="vertical-align: top;">
 <input type="text" name="nom" size="25" maxlength="30" value="<?php echo $data['nom'] ?>" ><br />
      </td>
    </tr><tr>
      <td style="vertical-align: top;">Description<br />
      </td>
      <td colspan="2" style="vertical-align: top;">
 <textarea name="descr" cols="80" rows="10"><?php echo $data['descr'] ?></textarea>
      </td>
    </tr>
    <tr>
 <?php
 // chaine liste des fournisseurs -> tableau
 $liste_fourn = explode(",", $data['fourniss']);
 // recupere la liste des fournisseurs
  $querry = "SELECT id,nom FROM fournisseurs";
  list($qheq,$numeq) = query_db($querry);
  ?>
      <td style="vertical-align: top;">Fournisseurs <br />
      <i> plusieurs peuvent &ecirc;tre selectionn&eacute;s parmi les <?php echo $numeq ?> enregistr&eacute;s!</i>
 <!---- <?php echo $data['fourniss'].":".count($liste_fourn) ?> ----->
 </td>
      <td colspan="2" style="vertical-align: top;">
 <select name="fourn[]" multiple="yes" size="5" >
  <?php

  while( $fournis = result_db($qheq) ){

   echo "<option value=\"".$fournis['id']."\" ";
   if ($mode=="modifier"){
    foreach( $liste_fourn as $fourn){
     if ($fournis['id'] == $fourn )
      echo " selected ";
    }
   }
   else{ //ajouter
    if ($fourniss['id'] == 1) //aucun
     echo " selected ";
   }
   echo ">".$fournis['nom'];echo "</option>";
  }//end while ?>
 </select>
      </td>
    </tr>

    <tr>
      <td style="vertical-align: top;">Date * <i>format:YYYY-MM-JJ</i><br />
      </td>
      <td colspan="2" style="vertical-align: top;">
 <input type="text" name="date" size="10" maxlength="10" value="<?php
    if ($mode=="modifier")
     echo $data['date'];
    else  //ajout->aujourd'hui
     echo date('Y-m-d', time() );
      ?> " ><br />
      </td>
    </tr>
 <tr style="vertical-align: middle; text-align: center">
   <td ><i>Les champs avec * sont &agrave;
remplir obligatoirement, les autres sont optionnels.</i><br />
      </td>
      <td >
 <input type="submit" name="Login" value="<?php echo strtoupper($mode) ?>">
      </td>

</form>

 <form action="manip_maint.php?id=<?php echo $manip_id ?>" method="POST" name="annulForm">
    <td >
 <input type="submit" name="annul" value="Annuler">
  </td>    </tr>
 </form>
</tbody>
</table>
<br />
<br />
</div>
<?php pied_page() ?>
