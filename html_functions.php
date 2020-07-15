<?php

// -------------------------------------------------------------

define('ICON_PERSON_OK',     '<span class="check-ok"><svg width="1.2em" height="1.2em" fill="currentColor"><title>Valid&eacute;</title><use xlink:href="images/bootstrap-icons.svg#person-check"/></svg></span>');
define('ICON_PERSON_BAD',    '<span class="check-bad"><svg width="1.2em" height="1.2em" fill="currentColor"><title>Non Valid&eacute;</title><use xlink:href="images/bootstrap-icons.svg#person-dash"/></svg></span>');
define('ICON_PERSON_PROFIL', '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Modifier le profil</title><use xlink:href="images/bootstrap-icons.svg#gear"/></svg></span>');
define('ICON_PERSON_PASWD',  '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Changer le mot de passe</title><use xlink:href="images/bootstrap-icons.svg#key"/></svg></span>');
define('ICON_TRASH',         '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Supprimer</title><use xlink:href="images/bootstrap-icons.svg#trash"/></svg></span>');
define('ICON_MAIL',          '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Courriel</title><use xlink:href="images/bootstrap-icons.svg#envelope"/></svg></span>');
define('ICON_LOGIN',         '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Se d&eacute;connecter</title><use xlink:href="images/bootstrap-icons.svg#power"/></svg></span>');
define('ICON_RETURN',        '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Retour</title><use xlink:href="images/bootstrap-icons.svg#box-arrow-in-down"/></svg></span>');
define('ICON_BOOKING',       '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Emprunts</title><use xlink:href="images/bootstrap-icons.svg#box-arrow-up"/></svg></span>');
define('ICON_EDIT',          '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Modifier</title><use xlink:href="images/bootstrap-icons.svg#pen"/></svg></span>');
define('ICON_SEE_DOC',       '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Voir le document</title><use xlink:href="images/bootstrap-icons.svg#eye"/></svg></span>');
define('ICON_URL',           '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Lien web</title><use xlink:href="images/bootstrap-icons.svg#link"/></svg></span>');
define('ICON_ADD_DOC',       '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter un document</title><use xlink:href="images/bootstrap-icons.svg#paperclip"/></svg></span>');
define('ICON_ADD_TASK',      '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter une tache</title><use xlink:href="images/bootstrap-icons.svg#plus-square"/></svg></span>');
define('ICON_ADD_TIME',      '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter du temps</title><use xlink:href="images/bootstrap-icons.svg#clock"/></svg></span>');
define('ICON_ADD_CAT',       '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter un cat&eacute;gorie</title><use xlink:href="images/bootstrap-icons.svg#plus-circle"/></svg></span>');
define('ICON_ADD_FOURN',     '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter un fournisseur</title><use xlink:href="images/bootstrap-icons.svg#plus-circle"/></svg></span>');
define('ICON_ADD_EQUIP',     '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter une &eacute;quipe</title><use xlink:href="images/bootstrap-icons.svg#plus-circle"/></svg></span>');
define('ICON_ADD_USER',      '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter un utilisateur</title><use xlink:href="images/bootstrap-icons.svg#plus-circle"/></svg></span>');
define('ICON_MARK_RIGHT',    '<span><svg width="1.2em" height="1.2em" fill="currentColor"><use xlink:href="images/bootstrap-icons.svg#arrow-right-square"/></svg></span>');

// -------------------------------------------------------------

function en_tete($titre) {
   // <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<link rel="icon" sizes="192x126" href="images/logo-gestex-192.png">
	<title>GestEx - <?php echo $titre ?></title>
	<link href="pool_project.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header">
	<div class="header-logo">
		<a href="./"><img src="images/logo-gestex.png" alt="" height="100px"></a>
	</div>
	<div class="header-title">
		<h1><a href="./">GestEx</a> - Gestion des plateformes Exp&eacute;rimentales</h1>
		<h2><?php echo $titre ?></h2>
	</div>
	<br>
</div>

<?php
if(!empty($_SESSION)){
	$pdo            = connect_db();
	$logged_in_user = $_SESSION['logged_in_user'];
	$sql            = 'SELECT nom, prenom FROM users WHERE loggin = ?;';
	$stmt           = $pdo->prepare($sql);
	$stmt->execute(array($logged_in_user));
	$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
	nav_bar($user[0]['prenom'], $user[0]['nom'], $_SESSION['level'], $_SESSION['user_id']);
} else {
	nav_bar('', '',0,0);
}
}

// -------------------------------------------------------------

function nav_bar($prenom, $nom, $level, $user_id) {
?>
<div class="navbar">
	<link rel="stylesheet" type="text/css" href="nav_bar.css"> 
	<ul>
	<?php if (empty($level)) { ?>
		<li><a href="list_fourn.php">Liste des fournisseurs</a></li>
		<li><a href="list_user.php">Liste des utilisateurs</a></li>
		<li><a href="list_equip.php">Liste des &eacute;quipes</a></li>
		<li><a href="list_pret.php">Liste des appareils en pr&ecirc;t</a></li>
		<li class="dropdown">
			<a class="dropbtn">Liste des appareils</a>
			<div class="dropdown-content">
				<a href="list_categorie.php">Cat&eacute;gories</a>
				<a href="list_appareil.php">Global</a>
				<a href="list_appareil.php?equipe=15">au service <br />instrumentation</a>
			</div>
		</li>  
		<li class="right"><a href="login.php">Se connecter</a></li>
	<?php } else { ?>
		<li><a href="list_fourn.php">Liste des fournisseurs</a></li>
		<li><a href="list_user.php">Liste des utilisateurs</a></li>
		<li><a href="list_equip.php">Liste des &eacute;quipes</a></li>
		<li><a href="list_pret.php">Liste des appareils en pr&ecirc;t</a></li>
		<li class="dropdown">
			<a class="dropbtn">Liste des appareils</a>
			<div class="dropdown-content">
				<a href="list_categorie.php">Cat&eacute;gories</a>
				<a href="list_appareil.php">Global</a>
				<a href="list_appareil.php?equipe=15">au service <br />instrumentation</a>
			</div>
		</li>

	<?php if ($level == 2) { ?>
		<li class="dropdown">
			<a class="dropbtn">Ajouter</a>
			<div class="dropdown-content">
				<a href="add_manip.php">Manip</a>
				<a href="add_fourn.php">Fournisseur</a>
				<a href="list_appareil.php?equipe=15 pret=15">Pr&ecirc;t</a>
				<a href="add_time.php">Temps</a>
				<a href="add_task.php">Task</a>
				<a href="add_labviews.php">Labview</a>
			</div>
		</li>
	<?php } else if ($level >= 3) { ?>
		<li class="dropdown">
			<a class="dropbtn">Ajouter</a>
			<div class="dropdown-content">
				<a href="add_appareil.php">Appareil</a>
				<a href="add_categorie.php">Cat&eacute;gorie</a>
				<a href="add_equip.php">&Eacute;quipe</a>
				<a href="add_fourn.php">Fournisseur</a>
				
				<a href="add_user.php">Utilisateur</a>
			</div>
		</li>
	<?php } ?>

	<?php if ($level >= 4) { ?>
		<li class="dropdown">
			<a class="dropbtn">Bonus</a>
			<div class="dropdown-content">
				<a href="add_time.php">Temps</a>
				<a href="add_task.php">Task</a>
				<a href="add_demande.php">Demande</a>
				<a href="add_labviews.php">Labview</a>
				<a href="add_intapp.php?app=3">Intervention</a>
				<a href="add_machine.php">Machine</a>
				<a href="add_manip.php">Manip</a>
				<a href="list_manip.php">Liste des manips</a>

			</div>
		</li>
	<?php } ?>

	<li class="dropdown right">
		<a class="dropbtn"><?php echo "$nom",   "  $prenom ";?></a>
		<div class="dropdown-content">
			<a href="logout.php"><?php echo ICON_LOGIN;?> Se d&eacute;connecter</a>
			<a href="add_user.php?id=<?php echo $user_id ?>"><?php echo ICON_PERSON_PROFIL;?> Modifier le profil</a>
			<a href="user_changepwd.php?id=<?php echo $user_id ?>"><?php echo ICON_PERSON_PASWD;?> Changer le<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mot de passe</a>
			<a href="user_pret.php?id=<?php echo $user_id ?>"><?php echo ICON_BOOKING;?> Mes emprunts</a>
		</div>
	</li>
	<?php } ?>
</ul>
</div>
<?php
}

// -------------------------------------------------------------

function pied_page() {
   //ne garde que le nom de fichier
   $filetmp = explode('/',$_SERVER['PHP_SELF']);
   $file = $filetmp[count($filetmp) - 1];
   ///mise a jour de ce fichier
   $last_update = strftime('%Y-%m-%d', filemtime($file));
?>

<div class="footer">
<center>
	<img src="images/striped.gif" nosave="" border="0" height="13" width="532" align="bottom">
	<address><a href="mailto:<?php echo GESTEX_ADMIN_MAIL ?>?Subject=GestEx%20to%20WebMaster">GestEx WebMaster</a></address>
	<br>
	<i>
		Derni&egrave;re mise &agrave; jour :
		<?php echo $last_update ?>
	</i>
</center>
</div>
</body>
</html>
<?php
}

// -------------------------------------------------------------

function check_mail($mail) {
   $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]';    // allowed characters for part before "at" character
   $domain = '([a-z]([-a-z0-9]*[a-z0-9]+)?)'; // allowed characters for part after "at" character

   $regex = '^' . $atom . '+' .        // One or more atom characters.
      '(\.' . $atom . '+)*'.              // Followed by zero or more dot separated sets of one or more atom characters.
      '@'.                                // Followed by an "at" character.
      '(' . $domain . '{1,63}\.)+'.        // Followed by one or max 63 domain characters (dot separated).
      $domain . '{2,63}'.                  // Must be followed by one set consisting a period of two
      '$';                                // or max 63 domain characters.

   $erreur = 0;

   if (strlen($mail) == 0):
      //echo '&nbsp;<br />';
      $erreur = 1;
   else:
      if (eregi($regex, $mail)):
         // echo $mail . ' matched<br />';
         $erreur = 0;
      else:
         // echo '<strong>'. $mail . ' not matched</strong><br />';
         $erreur = 2;
      endif;
   endif;
   return $erreur;
   }

// -------------------------------------------------------------

function redirect($link='index.php') {
	Header('Location: '.$link);
	exit();
}

?>
