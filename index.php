<?php
// index.php
$web_page = true;

require_once('module/auth-functions.php');
require_once('module/html-functions.php');

session_start();
if(empty($_SESSION['logged_user'])){
	$log = false;
}else{
	$logged_id    = $_SESSION['logged_id'];
	$logged_user  = strtolower($_SESSION['logged_user']);
	$logged_level = $_SESSION['logged_level'];
	$log          = true;
}
?>

<?php en_tete('Gestion des plateformes exp&eacute;rimentales'); ?>

<div class="index">
<p>GestEx est une application web developp&eacute;e au LEGI (collaboration entre les services technique / instrumentation / informatique)
destin&eacute;e &agrave; g&eacute;rer l'historique des montages et le suivi de l'instrumentation des plateformes exp&eacute;rimentales du laboratoire.</p>

<center>
	<p><a href="category-list.php">Inventaire des mat&eacute;riels</a> sous sa responsabilit&eacute;.</p>
	<p><a href="user-loan.php">Liste de vos emprunts d'appareils</a></p>
	<p>Il faut &ecirc;tre un utilisateur r&eacute;f&eacute;renc&eacute; pour pouvoir acc&eacute;der &agrave; certaines parties.</p>
	<p><a href="user-edit.php">Demander son inscription</a></p>
</center>
</div>

<?php pied_page() ?>
