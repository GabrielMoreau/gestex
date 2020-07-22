<?php
//list_machine.php

// Authenticate
require_once('auth-functions.php');

if (!auth(1))
	Header("Location: login.php");

$logged_id = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);
$logged_level= $_SESSION['logged_level'];

require_once('html-functions.php');

en_tete('Liste des appareils');

//recuper la methode de tri
if (empty($_GET['tri']))
	$tri ="nom";
else
	$tri = $_GET['tri'];
?>

Liste des appareils pour lesquels la maintenance est enregistr&eacute;e r&eacute;guli&egrave;rement :<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	<a href="list_manip.php">Retour a<br />l'accueil</a>
	<br /></td>
<?php if ( $logged_level >=3 ) {	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_machine.php">Ajout<br />d'un appareil</a>
	<br /></td>
<?php }	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php">Quitter</a>
	<br /></td> </tr></tbody>
</table>
<br />
<i>Cliquer sur le nom d'un appareil pour voir la liste des interventions qu'il a subi...</i><br />

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_machine.php?tri=nom">Nom</a><br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Description<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_machine.php?tri=equipe">&Eacute;quipe</a><br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_machine.php?tri=tech">Responsable</a><br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_machine.php?tri=fournisseur">Fournisseur</a><br />
      </th>
    <th style="vertical-align: top; text-align: center;">
	Date Achat<br />
      </th>
    <th style="vertical-align: top; text-align: center;">
	Facture<br />
      </th>
<?php if ( $logged_level >=2 )
		echo "</th><th>";
	if ( $logged_level >=3 )
		echo "</th><th>";
	  ?>
    </tr>
<?php	//interrogation base de donnees

if ( $pdo = connect_db() ){
	// recupere la liste de appareils
	$sql = 'SELECT * FROM appareils ORDER BY ?;';
	// list($qh,$num) = query_db($querry);
	
	// $last_id=0;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	$appareils = $stmt->fetchAll(PDO::FETCH_ASSOC);
// while ($data = result_db($qh)) {
	foreach($appareils as $data){

	// remplit le tableau
 echo"<tr><td style=\"vertical-align: top;\">";
	echo "<a href =\"list_intapp.php?id=".$data['id']."\">". $data['nom']."</a>";
      echo"</td><td style=\"vertical-align: top;\">";
	echo $data['descr'];
	   echo"</td><td style=\"vertical-align: top;\">";
	   
	// recupere le nom d'equipe
	$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
	// list($qheq,$numeq) = query_db($querry);
	// 	$equip = result_db($qheq);
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($data['equipe']));
	$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
	// var_dump($equipe);
      		echo $equipe[0]['nom'];
	   echo"</td><td style=\"vertical-align: top;\">";
	   
	// recupere le nom du tech
	$sql = 'SELECT id, nom FROM users WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($data['tech']));
    $equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
	// list($qheq,$numeq) = query_db($querry);
	// 	$equip = result_db($qheq);
	echo $equipe[0]['nom'];
	echo"</td><td style=\"vertical-align: top;\">";
	   
	// recupere le nom du fournisseur
	$sql = 'SELECT id, nom FROM fournisseurs WHERE id = ?';;
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($data['fournisseur']));
    $equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
	// list($qheq,$numeq) = query_db($querry);
	// 	$equip = result_db($qheq);
	echo $equipe[0]['nom'];
	echo"</td><td style=\"vertical-align: top;\">";
	// date achat
		echo $data['achat'];
      echo"</td><td style=\"vertical-align: top;\">";
	// facture
		echo $data['facture'];
      echo"</td>";

 if ( $logged_level >=2 ) {
      echo '</td><td style="vertical-align: top;">';
      echo '<a href="add_machine.php?id="'.$data['id'].'">'.ICON_EDIT.'</a>';
      echo '</td>';
	}//end if
 if ( $logged_level >=3 ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_machine.php?id=".$data['id']."\">".ICON_TRASH.'</a>';
      echo"</td>";

	}//end if
      echo"</tr>";
	}//end while

}//end if
?>
  </tbody>
</table>
<br />
</div>
<?php pied_page() ?>
