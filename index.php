<?php
// index.php
$web_page = true;

require_once('module/auth-functions.php');
require_once('module/html-functions.php');

session_start();
if(empty($_SESSION['logged_user'])){
	$log = false;
}else{
	$logged_id    = (int)$_SESSION['logged_id'];
	$logged_user  = strtolower($_SESSION['logged_user']);
	$logged_level = $_SESSION['logged_level'];
	$log          = true;
}
?>

<?php en_tete('Gestion des plateformes expérimentales'); ?>

<div class="index">
<p>GestEx est une application web developpée au LEGI (collaboration entre les services technique / instrumentation / informatique)
destinée à gérer l'historique des montages et le suivi de l'instrumentation des plateformes expérimentales du laboratoire.</p>

<center>
	<p><a href="category-list.php">Inventaire des matériels</a> sous sa responsabilité.</p>
	<p><a href="user-loan.php">Liste de vos emprunts d'appareils</a></p>
	<p>Il faut être un utilisateur référencé pour pouvoir accéder à certaines parties.</p>
	<p><a href="user-edit.php">Demander son inscription</a></p>
</center>
</div>

<?php pied_page() ?>
