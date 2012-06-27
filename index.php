<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="content-type"
 content="text/html; charset=ISO-8859-1">
  <title>Gestion de Projets Legi</title>
<link href="pool_project.css" rel="stylesheet" type="text/css">
</head>
<body>
<br>
<div width="100%" height="100%" align="center" valign="center">
<?php require("html_functions.php"); en_tete("gestion des manips du LEGI");?>
  <br>

PoolProject est un ensemble de scripts PHP/MySQL developp&eacute;s au Legi (Pool Technique du Bat. G)
destin&eacute; &agrave; gerer l'historique des montages exerimentaux du labo.<br>
Il faut &ecirc;tre utilisateur referenc&eacute; pour pouvoir acceder &agrave; ce systeme.
<br>
<table cellpadding="2" cellspacing="2" border="0"
 style="text-align: center; width: 75%;" align="center">
  <tbody>
    <tr>
      <td style="vertical-align: top;">
	<a href="add_user.php">Demander son inscription</a><br>
      </td>
      <td style="vertical-align: top;">
	<a href="login.php?variable=projet">Acceder au gestionnaire de Projets</a><br>
      </td>
    </tr>
 </tbody>
</table>
  <br>
<br>
</div>
<?php pied_page() ?>
</body>
</html>
