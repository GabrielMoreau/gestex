<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
  <title>Gestion de Projets LEGI</title>
  <link href="pool_project.css" rel="stylesheet" type="text/css" />
</head>
<body>
<br />
<div width="100%" height="100%" align="center" valign="center">
<?php require("html_functions.php"); en_tete("Gestion des plateformes exp&eacute;rimentales du LEGI");?>
<br />

<p>GestEx est une application web developp&eacute;e au LEGI (collaboration entre les services technique / instrumentation / informatique)
destin&eacute;e &agrave; g&eacute;rer l'historique des montages exp&eacute;rimentaux du laboratoire.</p>
<p>Il faut &ecirc;tre un utilisateur r&eacute;f&eacute;renc&eacute; pour pouvoir acc&eacute;der &agrave; ce syst&egrave;me.</p>

<br />

<table cellpadding="2" cellspacing="2" border="0" style="text-align: center; width: 75%;" align="center">
  <tbody>
    <tr>
      <td style="vertical-align: top;">
        <a href="add_user.php">Demander son inscription</a>
      </td>
      <td style="vertical-align: top;">
        <a href="login.php?variable=projet">Acc&eacute;der au gestionnaire de Projets</a>
      </td>
    </tr>
  </tbody>
</table>
<br />
<br />
</div>
<?php pied_page() ?>
</body>
</html>
