<?php
//ind_fourn.php

// Authenticate
include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

en_tete('Rechercher un fournisseur');

//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="nom";

unset($find_nom); unset($find_descr);//recupere les criteres de recherhe
if (isset($_POST[nom]))
//	$find_nom ="";
//else
	$find_nom = $_POST[nom];

if (isset($_POST[descr]))
//	$find_descr ="";
//else
	$find_descr = $_POST[descr];
?>

<!-- table criteres de recherche -->
<div class="catalog">
<form action="find_fourn.php" method="POST" name="findForm">
<table>
	<tbody>
		<tr>
			<th>
				Le Nom contient
			</th>
			<th>
				La description contient
			</th>
			<th>
			</th>
		</tr>
		<tr>
			<td>
				<input type="text" name="nom" size="50" maxlength="50" value="<?php echo $find_nom; ?>">
			</td>
			<td>
				<input type="text" name="descr" size="50" maxlength="50" value="<?php echo $find_descr; ?>">
			</td>
			<td>
				<input type="submit" name="find" value="Rechercher">
			</td>
		</tr>
	</tbody>
</table>
</form>
</div>

<?php
if (isset($find_nom) || isset($find_descr)) {
	echo 'Resultat de la recherche pour';
	if ( !empty($find_nom))
		echo ' nom :'.$find_nom;
	if ( !empty($find_descr) )
		echo ' description :'.$find_descr;
	}
?>

<!-- table resultats -->
<div class="catalog">
<table>
	<tbody>
		<tr>
			<th>
				<a href ="list_fourn.php?tri=nom">Nom</a>
			</th>
			<th>
				Adresse
			</th>
			<th>
				T&eacute;l&eacute;phone
			</th>
			<th>
				Fax
			</th>
			<th>
				Courriel
			</th>
			<th>
				WWW
			</th>
			<th>
				Contacts
			</th>
			<th>
				Description
			</th>
			<?php if ($user_level >= 2) { ?>
			<th class="sorttable_nosort" colspan="2">
			</th>
			<?php } ?>
		</tr>

<?php	//interrogation base de donnees
if ( $connex = connect_db() ){
	// recupere la liste de fournisseurs repondant aux criteres de recherche
	if (isset($find_nom) && isset($find_descr))
		{ // criteres non vides
	$querry = "SELECT * FROM fournisseurs WHERE";
	if (isset($find_nom) && !empty($find_nom))
		$querry .=" nom LIKE '%$find_nom%'";
	if (isset($find_nom)&& !empty($find_nom) && isset($find_descr) && !empty($find_descr))
		$querry .=" AND ";
 	if (isset($find_descr)&& !empty($find_descr))
		$querry .=" descr LIKE '%$find_descr%'";
	$querry .=";";
	list($qh,$num) = query_db($querry);

	$last_id=0;

while ($data = result_db($qh)) {

	// remplit le tableau
 echo"<tr><td style=\"vertical-align: top;\">";
	echo $data['nom'];
      echo"</td><td style=\"vertical-align: top;\">";
	echo $data['adresse'];
       echo"</td><td style=\"vertical-align: top;\" nowrap>";
      echo $data['tel'];
       echo"</td><td style=\"vertical-align: top;\" nowrap>";
      echo $data['fax'];
     echo"</td><td style=\"vertical-align: top;\">";
         echo "<a href=\"mailto:".$data['mail']."\">".ICON_MAIL."</a>";

      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"http://".$data['www']."\" target=\"_fournView\">".ICON_URL."</a>";
      echo"</td><td style=\"vertical-align: top;\">";
      echo  $data['contact'];
      echo"</td><td style=\"vertical-align: top;\">";
      echo $data['descr'];
      echo"</td>";
 if ( $user_level >=2 && $data['nom']!="aucun" ) {
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"add_fourn.php?id=".$data['id']."\">".ICON_EDIT."</a>";
      echo"</td>";
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"del_fourn.php?id=".$data['id']."\">".ICON_TRASH."</a>";
      echo"</td>";

	}//end if
      echo"</tr>";
	}//end while
  }//end if  non vides
}//end if connect
?>

	</tbody>
</table>
</div>

<?php pied_page() ?>
