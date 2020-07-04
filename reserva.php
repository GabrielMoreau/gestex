<?php

// Authenticate

//include("db_functions.php");
include("session_auth.php");
session_start();
//if (!auth(1))
	//Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];
require("html_functions.php");

en_tete("Liste des prets:");

//recuper la methode de tri
if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];

// recupere le user
if (empty($_GET['user']))
	$use ="";
else
	$use = $_GET['user'];

	//recupere l'equipe

if (empty($_GET['equipe']))
	$eq ="";
else
	$eq = $_GET['equipe'];

?>
<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	<a href="<?php GESTEX_URL_SERVICE ?></a>">Retour &agrave;<br />la page du service</a>
	<br /></td>

<td style="vertical-align: top; text-align: center;">
<?php if ( $use >=3 ) 	{?>
	<a href="pret1.php">Retour<br />&agrave; la liste des pr&ecirc;ts</a>
	<br /></td>
<?php }
else{	?>
<a href="pret.php">Retour<br />&agrave; la liste des pr&ecirc;ts</a>
<?php }?>
</tr></tbody>
</table>

<br />
Liste des pr&ecirc;ts : <br /><br /><br />

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">

      <th style="vertical-align: top; text-align: center;">
	Nom<br />
      </th>

      <th style="vertical-align: top; text-align: center;">
	&Eacute;quipe<br />
      </th>

       <th style="vertical-align: top; text-align: center;">
	Date<br />
      </th>
 <th style="vertical-align: top; text-align: center;">
	Retour<br />
      </th>

 <th style="vertical-align: top; text-align: center;">
 Emprunteur<br />
      </th>
	  <th style="vertical-align: top; text-align: center;">
	Num&eacute;ro de l'appareil<br />
      </th>

<?php if ( $user_level >=2 )
		echo "</th><th>";
	if ( $user_level >=3 )
		echo "</th><th>";
	  ?>
    </tr>
<?php	// interrogation base de donnees

if ( $pdo = connect_db() ){
	// recupere la liste de appareils

$sql = 'SELECT * FROM pret order by emprunt DESC;';
// 	list($qh,$num) = query_db($querry);

// 	$last_id=0;

// $data = result_db($qh);
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pret = $stmt->fetchAll(PDO::FETCH_ASSOC);
// echo "<tr>";

//  echo"</td><td style=\"vertical-align: top;\">";

// 	$sql = 'SELECT id, nom FROM Listing WHERE id = ?;';
// 	// list($qheeq,$numeeq) = query_db($querry);
// 	// 	$nom = result_db($qheeq);
// 	$stmt = $pdo->prepare($sql);
// 	$stmt->execute(array($pret[0]['nom']));
// 	$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);
// 	// if(!emty($listing))
//     echo $listing[0]['nom'];

//   echo"</td><td style=\"vertical-align: top;\">";

// 	// recupere le nom d'equipe

// 	$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
// 	// list($qheq,$numeq) = query_db($querry);
// 	// 	$equip = result_db($qheq);

// 	$stmt = $pdo->prepare($sql);
// 	$stmt->execute(array($pret[0]['equipe']));
// 	$equip = $stmt->fetchAll(PDO::FETCH_ASSOC);

//       		echo $equip[0]['nom'];
//        echo"</td><td style=\"vertical-align: top;\">";

// echo $pret[0]['emprunt'];

// 	 echo"</td><td style=\"vertical-align: top;\">";

// echo $pret[0]['retour'];

//  echo"</td><td style=\"vertical-align: top;\">";

// echo $pret[0]['commentaire'];
//  echo"</td><td style=\"vertical-align: top;\">";

//       		echo $listing[0]['id'];

// 	if ( $use >=3 ) 	{

//       echo"</td><td style=\"vertical-align: top;\">";
//       echo '<a href="del-pret.php?id=',$pret[0]['id'],'"><img src="images/trash.svg" nosave="" title="Supprimer"></a>';
//       echo"</td>";}

// echo"</tr>";
foreach($pret as $data){
// while ($data = result_db($qh)){

	// remplit le tableau

	echo"</td><td style=\"vertical-align: top;\">";
	$sql = 'SELECT id, nom FROM Listing WHERE id = ?;';
	// list($qheeq,$numeeq) = query_db($querry);
	// 	$nom = result_db($qheeq);
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($data['nom']));
	$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);
      		echo $listing[0]['nom'];

       echo"</td><td style=\"vertical-align: top;\">";

	// recupere le nom d'equipe

	$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
	// list($qheq,$numeq) = query_db($querry);
	// 	$equip = result_db($qheq);

	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($data['equipe']));
	$equip = $stmt->fetchAll(PDO::FETCH_ASSOC);

      		echo $equip[0]['nom'];
       echo"</td><td style=\"vertical-align: top;\">";

echo $data['emprunt'];

	 echo"</td><td style=\"vertical-align: top;\">";

echo $data['retour'];

 echo"</td><td style=\"vertical-align: top;\">";

echo $data['commentaire'];
echo"</td><td style=\"vertical-align: top;\">";

      		echo $listing[0]['id'];

 if ( $use >=3 ) 	{
      echo"</td><td style=\"vertical-align: top;\">";
      echo '<a href="del-pret.php?id=',$data['id'],'"><img src="images/trash.svg" nosave="" title="Supprimer"></a>';
      echo"</td>";
	}

echo"</tr>";

	}//end while

}//end if

?>
  </tbody>
</table>
<br />
</div>
<?php pied_page() ?>
