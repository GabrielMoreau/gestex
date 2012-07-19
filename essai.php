<?php
//essai.php
// Authenticate

include("session_auth.php");


if (!auth(1))
	Header("Location: instru.php");



$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("mise_en_page.php");



en_tete("Liste des appareils:");


//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")<br />";
?>
<br />
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	<a href="http://intranet.legi.grenoble-inp.fr/spip.php?article16">Retour à<br />la page du service</a>
	<br /></td>
<?php if ( $user_level >=2 ) {	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_app2.php">Ajout<br />d'un appareil</a>
	<br /></td>
 <td style="vertical-align: top; text-align: center;">
	<a href="list_fourn.php">Liste<br />des fournisseurs</a>
	<br /></td>


 <td style="vertical-align: top; text-align: center;">
	<a href="add_fourn.php">Ajout<br />d'un fournisseur</a>
	<br /></td>

<?php }	?>

<?php if ( $user_level >=3 ) {	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_categorie.php">Ajout<br />d'une categorie</a>
	<br /></td>

<?php }	?>



</tr></tbody>
</table>





<br />
Liste des appareils : <br />
<i>Affichage de la liste globale ou bien des appareils en prêt au service instrumentation...</i><br />
<br /><table cellpadding="20" cellspacing="4" border="1"
 style="width: 70%; text-align: left; margin-left: auto; margin-right: auto;">

 <tbody>
    <tr bgcolor="#f7d709">
	
	 <th style="vertical-align: top; text-align: center;">

	<a href ="instru.php?categorie= ">Liste globale</a><br />
      </th>

 <th style="vertical-align: top; text-align: center;">

	<a href ="instru.php?equipe=15">Appareils au service instru</a><br />
      </th>
</tr></tbody>

</table>
<br />
Liste des appareils par catégorie : <br />

<i>Cliquer sur une categorie pour voir la liste...</i><br />
<br /><table cellpadding="10" cellspacing="2" border="1"
 style="width: 70%; text-align: center; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
	
	

<?php

$tri = $_GET[tri];
if (empty($tri))
	$tri ="nom";

if ( $connex = connect_db() ){

	// recupere les refs du user



	$querry = "SELECT * FROM categorie order by nom ASC" ;
	list($qh,$num) = query_db($querry);
	$last_id=0;
$data = result_db($qh);
 echo"<td style=\"vertical-align: top;\">";
echo "<a href =\"instru.php?categorie=".$data[id]."\">". $data[nom]."</a>";
echo "<br />";
	

while ($data = result_db($qh))

  {   //  echo"<td style=\"vertical-align: top;\">";

	echo "<a href =\"instru.php?categorie=".$data[id]."\">". $data[nom]."</a>";
echo "<br />";

}

      echo"</tr>";


}
?>
</tbody>
</table>

<?php
 if (( $user_id ==33)|| ( $user_id ==2)) 
{?>
<br /><br /><br />
 <td style="vertical-align: top; text-align: center;">
	<a href="demandes.php">Demandes en cours</a>
<td style="vertical-align: top; text-align: center;">
	<a href="prets.html">Prets en cours</a>
<?php } ?>

<br /><br /><br />
</div>
<?php pied_page() ?>
</body>
</html>

