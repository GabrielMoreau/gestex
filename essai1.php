
<?php
//essai1.php
// Authenticate

include("db_functions.php");


//if (!auth(1))
	//Header("Location: instru.php");



$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("mise_en_page.php");



en_tete("Liste des appareils:");


//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";

echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")<br>";
?>
<br>
<table cellpadding="2" cellspacing="2" border="1"
 style="width: 70%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr>
	 <td style="vertical-align: top; text-align: center;">
	 <a href="http://intranet.legi.grenoble-inp.fr/spip.php?article16">Retour à<br>la page du service</a>
		<br></td>
 <td style="vertical-align: top; text-align: center;">
	<a href="list_fourn1.php">Liste<br>des fournisseurs</a>
	<br></td>
 <td style="vertical-align: top; text-align: center;">
	<a href="login.php?variable=instru">Accès<br>restreint</a>
	<br></td>


</tr></tbody>
</table>





<br>
Liste des appareils : <br>
<i>Cliquer sur une categorie pour voir la liste...</i><br>
<br><table cellpadding="20" cellspacing="4" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
	
	 <th style="vertical-align: top; text-align: center;">

	<a href ="instru1.php?categorie= ">Liste globale</a><br>
      </th>

 <th style="vertical-align: top; text-align: center;">

	<a href ="instru1.php?equipe= 15">Appareils en prêt au service instru</a><br>
      </th>
	  </tr></tbody>
	  </table>
	  <br>
Liste des appareils par catégorie : <br>

<i>Cliquer sur une categorie pour voir la liste...</i><br>
<br><table cellpadding="10" cellspacing="2" border="1"
 style="width: 70%; text-align: center; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
<?php

$tri = $_GET[tri];
if (empty($tri))
	$tri ="nom";

if ( $connex = connect_db() ){

	// recupere les refs du user



	$querry = "SELECT * FROM categorie order by '$tri'" ;
	list($qh,$num) = query_db($querry);
	$last_id=0;
$data = result_db($qh);
 echo"<td style=\"vertical-align: top;\">";
echo "<a href =\"instru1.php?categorie=".$data[id]."\">". $data[nom]."</a>";
echo "</td>";
	

while ($data = result_db($qh))

  {     echo"<td style=\"vertical-align: top;\">";

	echo "<a href =\"instru1.php?categorie=".$data[id]."\">". $data[nom]."</a>";
echo "</td>";

}

      echo"</tr>";


}
?>
</tbody>
</table>

<br>
</div>
<?php pied_page() ?>
</body>
</html>


