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
en_tete("Gestion des plateformes exp&eacute;rimentales");
?>

<br />

<p>GestEx est une application web developp&eacute;e au LEGI (collaboration entre les services technique / instrumentation / informatique)
destin&eacute;e &agrave; g&eacute;rer l'historique des montages et le suivi de l'instrumentation des plateformes exp&eacute;rimentales du laboratoire.</p>

<br />

<h2>Gestion instrumentation</h2>
<ul>
	<li><a href="essai.php">Inventaire des mat&eacute;riels</a> sous sa responsabilit&eacute;.</li>
	<li><a href="pret1.php">Gestion des pr&ecirc;ts des appareils</a> du service instrumentation</li>
	<li><a href="labview.php">Liste des programmes Labview d&eacute;velopp&eacute;s</a></li>
</ul>

<h2>Gestion des projets techniques</h2>
<p>Il faut &ecirc;tre un utilisateur r&eacute;f&eacute;renc&eacute; pour pouvoir acc&eacute;der &agrave; cette partie.</p>
<ul>
	<li><a href="add_user.php">Demander son inscription</a></li>
	<li><a href="login.php?variable=projet">Acc&eacute;der au gestionnaire de Projets</a></li>
</ul>

<br />
<br />
</div>
<?php pied_page() ?>
