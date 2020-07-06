<?php
//essai1.php
// Authenticate

include("db_functions.php");

// if (!auth(1))
	// Header("Location: instru.php");

require("html_functions.php");

// $user_id = $_SESSION['user_id'];
// $logged_in_user = strtolower($_SESSION['logged_in_user']);
// $user_level= $_SESSION['level'];

en_tete('Liste des appareils');

?>
<br />
<table cellpadding="2" cellspacing="2" border="1"
	style="width: 70%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr>
			<td style="vertical-align: top; text-align: center;">
				<a href="<?php GESTEX_URL_SERVICE ?>">Retour &agrave;<br /> la page du service</a>
				<br />
			</td>
			<td style="vertical-align: top; text-align: center;">
				<a href="list_fourn1.php">Liste<br />des fournisseurs</a>
				<br />
			</td>
			<td style="vertical-align: top; text-align: center;">
				<a href="login.php?variable=instru">Acc&egrave;s<br />restreint</a>
				<br />
			</td>
		</tr>
	</tbody>
</table>

<br />
Liste des appareils : <br />
<i>Cliquer sur une cat&eacute;gorie pour voir la liste...</i>
<br />
<br />
<table cellpadding="20" cellspacing="4" border="1"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
			<th style="vertical-align: top; text-align: center;">
				<a href ="instru1.php?list=">Liste globale</a><br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				<a href ="instru1.php?equipe= 15">Appareils en pr&ecirc;t au service instru</a><br />
			</th>
		</tr>
	</tbody>
</table>
<br />

Liste des appareils par cat&eacute;gorie : <br />

<i>Cliquer sur une cat&eacute;gorie pour voir la liste...</i><br />
<br />
<table cellpadding="10" cellspacing="2" border="1"
	style="width: 70%; text-align: center; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
<?php	
 // recuperation de la methode de tri et valeur par defaut mise a 'nom'
if (empty($_GET['tri']))
	$tri ="nom";
else
	$tri = $_GET['tri'];

if ( $pdo = connect_db() ){

	// recupere les refs du user

	$sql = 'SELECT id, nom FROM categorie ORDER BY ?;';
	$stmt =$pdo->prepare($sql);
	$stmt->execute(array($tri));
	$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
	// list($qh,$num) = query_db($querry);
	// $last_id=0;
// // $data = result_db($qh);
 echo"<td style=\"vertical-align: top;\">";
// echo "<a href =\"instru1.php?categorie=".$categorie[0]['id']."\">". $categorie[0]['nom']."</a>";
// echo "<br />";

// while ($data = result_db($qh))
foreach($categorie as $data){

	echo "<a href =\"instru1.php?categorie=".$data['id']."\">". $data['nom']."</a>";
	echo "<br />";
}

      echo"</tr>";

}
?>
</tbody>
</table>

<br />
</div>
<?php pied_page() ?>

