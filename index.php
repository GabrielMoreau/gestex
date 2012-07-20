<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
  <title>Portail GestEx</title>
  <link href="pool_project.css" rel="stylesheet" type="text/css" />
</head>
<body>
<br />
<div width="100%" height="100%" align="center" valign="center">
<?php require("html_functions.php"); en_tete("Gestion des plateformes exp&eacute;rimentales du LEGI");?>
<br />

<p>GestEx est une application web developp&eacute;e au LEGI (collaboration entre les services technique / instrumentation / informatique)
destin&eacute;e &agrave; g&eacute;rer l'historique des montages et le suivi de l'instrumentation des plateformes exp&eacute;rimentales du laboratoire.</p>

<br />

<h2>Gestion intrumention</h2>
<ul>
  <li><a href="essai1.php">inventaire des matériels</a> sous sa responsabilité.</li>
  <li><a href="pret.php">gestion des prêts des appareils</a> du service instrumentation</li>
  <li><a href="labview.php">liste des programmes Labview développés</a> au LEGI</li>
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
</body>
</html>
