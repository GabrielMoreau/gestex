<?php
/// assoc_proj.php
//association d'un projet annexe a une  manip
 // Authenticate
 include("session_auth.php");

 if (!auth(2))
  Header("Location: login.php");

 $user_id = $_SESSION['user_id'];
 $logged_in_user = strtolower($_SESSION['logged_in_user']);

$manip_id = $_GET[id];
if (empty($manip_id)){
 
  Header("Location :accueil.php");
}

require("html_functions.php");

if ( $connex = connect_db() ){

 en_tete("Voila un formulaire pour associer un projet parallele à une manip");

 // recupere la manip selectionnée
 $querry = "SELECT * FROM manip WHERE id='$manip_id'";
 list($qh,$num) = query_db($querry);
 $data = result_db($qh);

///tableau des projets associes
$assoc = explode(',' , $data[assoc_proj]);
echo "Projets deja associés :".$data[assoc_proj];
 
}//end if connex
 else 
  Header("Location :accueil.php");
?>
 

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
 <form action="valid_assoc.php" method="POST" name="inscrForm">
  <input type="hidden" name="id_manip" value="<?php echo $manip_id ?>" >
   <tr>
    
      <td style="vertical-align: top;">Voici la liste des projets n'appartenant pas a cette manip :<br />
 <i>vous pouvez selectionner plusieurs projets associés</i>
      </td>
     
 <?php // recupere la liste des projets n'appartennant pas a cette manip
  $querry = "SELECT id,nom,manip FROM projet WHERE manip!=".$manip_id. " ORDER BY manip";
  list($qh1,$num1) = query_db($querry);

  

  ?>
      <td style="vertical-align: top;">
 <select name="assoc_p"  multiple size=10 >
  <?php  
  while( $projets = result_db($qh1) ){
  

   echo "<option value=\"".$projets['id']."\"";
   if (!empty($assoc)){
    foreach ($assoc as $a){
     if ($projets['id'] == $a)
      echo " selected";
     }//end foreach
    }

     $querry = "SELECT nom FROM manip WHERE id=".$projets[manip];
        list($qh2,$num2) = query_db($querry);
       $manips =  result_db($qh2) ;

   echo ">[".$manips[nom]."].".$projets['nom']."</option>";
  ////echo ">".$projets['nom']."</option>";
  }//end while ?>
 </select>
      </td>
    </tr>
   
    <tr>
   <td style="vertical-align: top;"><br />
      </td>
      <td style="vertical-align: top;" align="right">
<input type="submit" name="Login" value="Associer">
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
