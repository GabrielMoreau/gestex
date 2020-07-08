<?php
/// add_appareil.php
	// Authenticate
	include("session_auth.php");

	if (!auth(3))
		Header("Location: login.php");

	$user_id = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);

//recuper la methode de tri
if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];

if (empty($_GET['categorie']))
	$cat = "";
else
	$cat=$_GET['categorie'];
//echo "$cat";
//recupere la categorie de la page categorie
if (empty($_GET['id']))
	$app_id = "";
else
	$app_id = $_GET['id'];

	// recupere la liste de appareils
if ( $pdo = connect_db() ){

$sql = 'SELECT * FROM categorie WHERE id = ?;' ;
// 	list($qh,$num) = query_db($querry);
// 	$last_id=0;
// $datax = result_db($qh);}
$stmt = $pdo->prepare($sql);
$stmt->execute(array($cat));
$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (empty($app_id)){
	//->nouvel appareil
	$mode = 'ajouter';
	$action = 'valid_appareil.php?categorie='.$cat;
//transmet la valeur de la categorie a la page valid appareil
}
else{

	//->modif appareil
	$mode ="modifier";
	$action="modif_app2.php?categorie=".$cat;

}
}

require("html_functions.php");
if ( $pdo = connect_db() ){
if ($mode=="ajouter"){
	en_tete('Ajouter un appareil');

}
else if ($mode=="modifier"){
	en_tete('Modifier les caracteristiques d\'un appareil');

	// recupere l'appareil selectionne
	$sql = 'SELECT * FROM Listing WHERE id = ?;';
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($app_id));
	$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
?>

 <td style="vertical-align: top; text-align: center;">
	<a href="add_categorie.php?">Ajout<br />d'une categorie</a>
	<br /></td>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
<form action="<?php echo $action ?>" method="POST" name="inscrForm" enctype="multipart/form-data">
		<input type="hidden" name="id_app" value="<?php echo $app_id ?>" >

  <tr>
      <td style="vertical-align: top;">Categorie<br />
      </td>

  <td style="vertical-align: top;">

<select name="categorie">
//listing des categories
<?php
	$sql = 'SELECT id, nom FROM categorie ORDER BY nom;';
	// list($qheq,$numeq) = query_db($querry);
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// 	while ($chef = result_db($qheq)){
		foreach($categorie as $chef){
			echo "<option value=\"".$chef['id']."\"";
			if ($mode=="modifier" && $chef['id'] == $listing[0]['categorie']) {
				echo " selected";	}
if ($mode=="ajouter" && $chef['id'] == $cat) {
				echo " selected";	}

//si on choisit ajouter, le listing preselectionne la categorie

			echo ">".$chef['nom']."</option>";
		}//end while

	?>
</select>
<br />
      </td>
    </tr>

    <tr>

      <td style="vertical-align: top;">Nom *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="nom" size="30"  value="<?php if ($mode == "modifier"){ echo $listing[0]['nom'];} ?>" ><br />
      </td>
    </tr>

<tr>
      <td style="vertical-align: top;">Modele *<br />
      </td>
      <td style="vertical-align: top;">
      <input type="text"name="modele" size="30" value="<?php if ($mode == "modifier"){ echo $listing[0]['modele'] ;}?>"<br />
	</td>
    </tr>
<tr>
 <td style="vertical-align: top;">Gamme *<br />
      </td>
      <td style="vertical-align: top;">
     	<input type="text" name="gamme" size="10" maxlength="30" value="<?php if ($mode == "modifier"){echo $listing[0]['gamme'] ;}?>" ><br />

	</td>
    </tr>

    <tr>
      <td style="vertical-align: top;">&Eacute;quipe *<br />
      </td>
      <td style="vertical-align: top;">
	<select name="equipe">
	<?php
	// recupere la liste des equipes
	$sql = 'SELECT id, nom FROM equipe ORDER BY nom;';
	// list($qheq,$numeq) = query_db($querry);
	// 	while ($chef = result_db($qheq)){
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($equipe as $chef){
			echo "<option value=\"".$chef['id']."\"";
			if ($mode=="modifier" && $chef['id'] == $listing[0]['equipe']) {
				echo " selected";	}
			echo ">".$chef['nom']."</option>";
		}//end while
		 ?>
	</select><br />
      </td>
    </tr>

  <tr>
      <td style="vertical-align: top;">Fournisseur *<br />
      </td>
      <td style="vertical-align: top;">
	<select name="fourn">
	<?php
	// recupere la liste des fournisseurs
	$sql = 'SELECT id, nom FROM fournisseurs ORDER BY nom;';
	// list($qheq,$numeq) = query_db($querry);
	// 	while ($chef = result_db($qheq)){
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$fournisseur = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($fournisseur as $chef){
			echo "<option value=\"".$chef['id']."\"";
			if ($mode=="modifier" && $chef['id'] == $listing[0]['fournisseur']) {
				echo " selected";	}
			echo ">".$chef['nom']."</option>";
		}//end while
		 ?>
	</select><br />
      </td>
    </tr>

  <tr>

      <td style="vertical-align: top;">Date achat * (<i>format YYYY-MM-DD</i>)<br />
      </td>
      <th style="vertical-align: top;">
	<input type="text" name="achat" size="10" maxlength="10" value="<?php
 if ($mode =="modifier")
			echo $listing[0]['achat'];
		else
			echo date('Y-m-d', time() );
	?>" ><br />
      </td>
    </tr><tr>

<td style="vertical-align: top;">Responsable *<br />
      </td>
      <td style="vertical-align: top;">

     <select name="tech">
	<?php
	// recupere la liste des tech
	$sql = 'SELECT id, nom FROM users WHERE level > 1;';
	// list($qheq,$numeq) = query_db($querry);
	// 	while ($chef = result_db($qheq)){
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($user as $chef){

			echo "<option value=\"".$chef['id']."\"";
			if ($mode=="modifier" && $chef['id'] == $listing[0]['responsable']) {
				echo " selected";	}
			echo ">".$chef['nom']."</option>";
		}//end while
		 ?>
	</select><br />
      </td>
    </tr>

  <tr>

      <td style="vertical-align: top;">R&eacute;paration *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="reparation" size="30" maxlength="30" value="<?php if ($mode == "modifier"){ echo $listing[0]['reparation'];} ?>" ><br />
      </td>
    </tr>
<tr>
 <td style="vertical-align: top;">Accessoires *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="accessoires" size="30" maxlength="30" value="<?php if ($mode == "modifier"){echo $listing[0]['accessoires'];} ?>" ><br />
      </td>
    </tr>

<tr>
 <td style="vertical-align: top;">Inventaire (facultatif)<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="inventaire" size="30" maxlength="30" value="<?php if ($mode == "modifier"){echo $listing[0]['inventaire'];} ?>" ><br />
      </td>
    </tr>

<tr>
 <td style="vertical-align: top;">Notice (facultatif)<br />
      </td>
      <td style="vertical-align: top;">
	<input type="file" name="notice" value="<?php if ($mode == "modifier"){echo $listing[0]['notice'];} ?>" ><br />
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

	<form action="list_appareil.php"method="POST" name="annulForm">
 	<tr >   <td colspan="2" style="vertical-align: top; text-align: right;">
	<input type="submit" name="annul" value="Annuler">
	 </td>    </tr>
	</form>
</tbody>
</table>
<br />

<?php } else { Header("Location: list_appareil.php"); } ?>
<br />
</div>
<?php pied_page() ?>
