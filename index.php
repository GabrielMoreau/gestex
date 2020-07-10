<?php
include("session_auth.php");
session_start();
if(empty($_SESSION['logged_in_user'])){
	$log = false;
}else{
	$user_id        = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);
	$user_level     = $_SESSION['level'];
	$log            = true;
}
require("html_functions.php");
en_tete('Gestion des plateformes exp&eacute;rimentales');
?>

<div class="index">
<p>GestEx est une application web developp&eacute;e au LEGI (collaboration entre les services technique / instrumentation / informatique)
destin&eacute;e &agrave; g&eacute;rer l'historique des montages et le suivi de l'instrumentation des plateformes exp&eacute;rimentales du laboratoire.</p>

<center>
<h2>Gestion instrumentation</h2>
<ul>
	<li><a href="list_categorie.php">Inventaire des mat&eacute;riels</a> sous sa responsabilit&eacute;.</li>
	<li><a href="user_pret.php">Liste de vos emprunts d'appareils</a></li>
	<li><a href="list_labview.php">Liste des programmes Labview d&eacute;velopp&eacute;s</a></li>
</ul>

<h2>Gestion des projets techniques</h2>
<p>Il faut &ecirc;tre un utilisateur r&eacute;f&eacute;renc&eacute; pour pouvoir acc&eacute;der &agrave; cette partie.</p>
<ul>
  <li><a href="add_user.php">Demander son inscription</a></li>
  <li><a href="list_manip.php">Acc&eacute;der au gestionnaire de Projets</a></li>
</ul>
</center>
</div>

<?php pied_page() ?>
