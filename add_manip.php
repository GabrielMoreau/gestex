<?php
/// add_manip.php
//ajout/modif manip
	// Authenticate
	require_once('auth-functions.php');

	if (!auth(2))
		Header("Location: login.php");

	$logged_id = $_SESSION['logged_id'];
	$logged_user = strtolower($_SESSION['logged_user']);

if (empty($_GET['id'])){
	//->nouvelle manip
	$mode ="ajouter";
	$action="valid_manip.php";
}else{
	//->modif manip
	$manip_id = $_GET['id'];
	$mode ="modifier";
	$action="modif_manip.php";

}

require_once('html-functions.php');

if ( $pdo = connect_db() ){

if ($mode=="ajouter"){
	en_tete('Ajouter une nouvelle manip');

}
else if ($mode=="modifier"){
	en_tete('Modifier les caracteristiques d\'une manip');

	// recupere la manip selectionnee
	$sql = 'SELECT * FROM manip WHERE id = ?;';
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($manip_id));
	$manip = $stmt->fetchAll(PDO::FETCH_ASSOC);

	}
}//end if connex
	else
		Header("Location: list_manip.php");
?>

<table cellpadding="2" cellspacing="2" border="1" style="text-align: left; width: 75%;" align="center">

  <tbody>
 <form action="<?php echo $action ?>" method="POST" name="inscrForm">
		<input type="hidden" name="id_manip" value="<?php  if($mode=='modifier'){ echo $manip_id; } ?>" >
   <tr>

      <td style="vertical-align: top;">Nom *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="nom" size="20" maxlength="20" value="<?php if($mode=='modifier'){ echo $manip[0]['nom']; } ?>" ><br />
      </td>
    </tr><tr>
      <td style="vertical-align: top;">Description<br />
      </td>
      <td style="vertical-align: top;">
	<textarea name="descr" cols="50" rows="5"> <?php if($mode=='modifier'){ echo $manip[0]['descr']; } ?>
	</textarea>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Local *<br />
      </td>
      <td style="vertical-align: top;">
	<input type="text" name="local" size="5" maxlength="10" value="<?php if($mode=='modifier'){ echo $manip[0]['local'];} ?>" ><br />
      </td>
    </tr>
    <tr>
	<?php // recupere la liste des equipes
		$sql = 'SELECT id, nom FROM equipe ORDER BY nom;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// list($qheq,$numeq) = query_db($querry);
		?>
      <td style="vertical-align: top;">&Eacute;quipe <i> qui utilisera la manip</i><br />
      </td>
      <td style="vertical-align: top;">
	<select name="equipe"   >
		<?php 	
		// while(	$equipes = result_db($qheq)	){
			foreach($equipe as $equipes){
			echo "<option value=\"".$equipes['id']."\"";
			if($mode=='modifier'){
				if ($equipes['id'] == $manip[0]['equipe']){
					echo " selected";
					}
			}
			echo ">".$equipes['nom']."</option>";
		}//end while	?>
	</select>
      </td>
    </tr>
    <tr>
	<?php // recupere la liste des chercheurs
		$sql = 'SELECT id, nom FROM users WHERE level = 1 AND valid = 1 ORDER BY nom;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// list($qheq,$numeq) = query_db($querry);
		?>
      <td style="vertical-align: top;">Chercheur <i>(qui commande la manip)</i><br />
      </td>
      <td style="vertical-align: top;">
	<select name="cherch"   >
		<?php 	
		// while(	$equipes = result_db($qheq)	){
			foreach($equipe as $equipes){
			echo "<option value=\"".$equipes['id']."\"";
			if($mode=='modifier'){
				if ($equipes['id'] == $manip[0]['chercheur']){
					echo " selected";
				}
			}
			echo ">".$equipes['nom']."</option>";
		}//end while	?>
	</select>
      </td>
    </tr><tr>
	<?php // recupere la liste des chercheurs
		$sql = 'SELECT id, nom FROM users WHERE level <= 2 AND valid = 1 ORDER BY nom;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
		// list($qheq,$numeq) = query_db($querry);
		?>
	<td style="vertical-align: top;">Chercheur/&Eacute;tudiant <i>(qui utilisera la manip)</i><br />
      </td>
      <td style="vertical-align: top;">
	<select name="cherch_bis"   >
		<?php 	
		// while(	$equipes = result_db($qheq)	){
			foreach($equipe as $equipes){
			echo "<option value=\"".$equipes['id']."\"";
			if($mode=='modifier'){
				if ($equipes['id'] == $manip[0]['chercheur_bis']){
					echo " selected";
				}
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
					echo $manip[0]['date'];
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
	<form action="list_manip.php" method="POST" name="annulForm">
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
