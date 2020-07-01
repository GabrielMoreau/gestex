<?php
/// add_intapp.php
	// Authenticate
	include("session_auth.php");

	if (!auth(3))
		Header("Location: login.php");

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);

if (empty($_GET['app']))
	Header("Location: list_intapp.php");
else{
	$app_id = $_GET['app'];
}
if (empty($_GET['id'])){
	//->nouvel intervention
	$mode ="ajouter";
	$action="valid_intapp.php";
	$int_id = '';
}
else{
	//->modif intervention
	$mode ="modifier";
	$action="modif_intapp.php";
	$int_id = $_GET['id'];

}

require("html_functions.php");
if ( $pdo = connect_db() ){

// recupere l'appareil selectionne
	$sql = 'SELECT nom FROM appareils WHERE id = ?;';
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($app_id));
	$appareil = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($mode=="ajouter"){
	$titre= "Formulaire pour ajouter une intervention &agrave; ".$appareil[0]['nom'];

}
else if ($mode=="modifier"){
	$titre="Formulaire pour modifier les caracteristiques d'une intervention &agrave; ".$appareil[0]['nom'];

	// recupere l'intervention selectionnee
	$sql = 'SELECT * FROM intervention WHERE id = ?;';
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($int_id));
	$intervention = $stmt->fetchAll(PDO::FETCH_ASSOC);	
}
en_tete($titre);
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
<form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >
		<input type="hidden" name="id_int" value="<?php echo $int_id ?>" >
    <tr>
      <td style="vertical-align: top;">Description<br />
      </td>
      <td style="vertical-align: top;">
      <textarea name="descr" cols="50" rows="5"> <?php if($mode == 'modifier'){ echo $intervention[0]['descr']; } ?></textarea>
	</td>
    </tr>

    <tr>
      <td style="vertical-align: top;">Responsable<br />
      </td>
      <td style="vertical-align: top;">

	<select name="tech">
	<?php
	// recupere la liste des tech
	$sql = 'SELECT id, nom FROM users WHERE level >1;';
	// list($qheq,$numeq) = query_db($querry);
	// 	while ($chef = result_db($qheq)){
		$stmt = $pdo->prepare($sql);
        $stmt->execute();
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($user as $chef){
			echo "<option value=\"".$chef['id']."\"";
			if ($mode=="modifier" && $chef['id'] == $intervention[0]['tech']) {
				echo " selected";	}
			echo ">".$chef['nom']."</option>";
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
	$sql = 'SELECT id, nom FROM fournisseurs;';
	// list($qheq,$numeq) = query_db($querry);
	// 	while ($chef = result_db($qheq)){
		$stmt = $pdo->prepare($sql);
        $stmt->execute();
		$fournisseur = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($fournisseur as $fourn){
			echo "<option value=\"".$fourn['id']."\"";
			if ($mode=="modifier" && $fourn['id'] == $intervention[0]['fournisseur']) {
				echo " selected";	}
			echo ">".$fourn['nom']."</option>";
		}//end while
		 ?>
	</select><br />
      </td>
    </tr>
  <tr>

      <td style="vertical-align: top;">Date <i>format YYYY-MM-DD</i><br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="date" size="10" maxlength="10" value="
	<?php
	if ($mode == "modifier")
		echo $intervention[0]['date'] ;
	else
		echo date('Y-m-d', time() );
       ?>"><br /></td>
    </tr><tr>
  <tr>

      <td style="vertical-align: top;">Facture<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="facture" size="30" maxlength="30" value="<?php if($mode == 'modifier'){  echo $intervention[0]['facture']; } ?>" ><br />
      </td>
    </tr><tr>

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
	<form action="list_intapp.php" method="POST" name="annulForm">
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
