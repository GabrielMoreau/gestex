<?php if (!$web_page) exit() ?>

<?php
require_once('db-functions.php');
require_once('base-functions.php');

// ---------------------------------------------------------------------

define('ICON_PERSON_OK',     '<span class="check-ok"><svg width="1.2em" height="1.2em" fill="currentColor"><title>Valid&eacute;</title><use xlink:href="images/bootstrap-icons.svg#person-check"/></svg></span>');
define('ICON_PERSON_BAD',    '<span class="check-bad"><svg width="1.2em" height="1.2em" fill="currentColor"><title>Non Valid&eacute;</title><use xlink:href="images/bootstrap-icons.svg#person-dash"/></svg></span>');
define('ICON_PERSON_PROFIL', '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Modifier le profil</title><use xlink:href="images/bootstrap-icons.svg#gear"/></svg></span>');
define('ICON_PERSON_PASWD',  '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Changer le mot de passe</title><use xlink:href="images/bootstrap-icons.svg#key"/></svg></span>');
define('ICON_TRASH',         '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Supprimer</title><use xlink:href="images/bootstrap-icons.svg#trash"/></svg></span>');
define('ICON_MAIL',          '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Courriel</title><use xlink:href="images/bootstrap-icons.svg#envelope"/></svg></span>');
define('ICON_PHONE',         '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>T&eacute;l&eacute;phone</title><use xlink:href="images/bootstrap-icons.svg#telephone-plus"/></svg></span>');
define('ICON_HOUSE',         '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>T&eacute;l&eacute;phone</title><use xlink:href="images/bootstrap-icons.svg#house"/></svg></span>');
define('ICON_LOGIN',         '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Se d&eacute;connecter</title><use xlink:href="images/bootstrap-icons.svg#power"/></svg></span>');
define('ICON_LOAN_RETURNED', '<span class="check-bad"><svg width="1.2em" height="1.2em" fill="currentColor"><title>Rendre / Retour d\'un appareil</title><use xlink:href="images/bootstrap-icons.svg#box-arrow-in-down"/></svg></span>');
define('ICON_LOAN_RESERVED', '<span class="check-warn"><svg width="1.2em" height="1.2em" fill="currentColor"><title>R&eacute;server un appareil</title><use xlink:href="images/bootstrap-icons.svg#box-arrow-up"/></svg></span>');
define('ICON_LOAN_BORROWED', '<span class="check-ok"><svg width="1.2em" height="1.2em" fill="currentColor"><title>Emprunter un appareil</title><use xlink:href="images/bootstrap-icons.svg#box-arrow-up"/></svg></span>');
define('ICON_EDIT',          '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Modifier</title><use xlink:href="images/bootstrap-icons.svg#pen"/></svg></span>');
define('ICON_LIST',          '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Liste</title><use xlink:href="images/bootstrap-icons.svg#card-list"/></svg></span>');
define('ICON_BARCODE',       '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Code barre</title><use xlink:href="images/bootstrap-icons.svg#upc-scan"/></svg></span>');
define('ICON_SEE_DOC',       '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Voir le document</title><use xlink:href="images/bootstrap-icons.svg#eye"/></svg></span>');
define('ICON_URL',           '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Lien web</title><use xlink:href="images/bootstrap-icons.svg#link"/></svg></span>');
define('ICON_ADD_DOC',       '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter un document</title><use xlink:href="images/bootstrap-icons.svg#paperclip"/></svg></span>');
define('ICON_ADD_TASK',      '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter une tache</title><use xlink:href="images/bootstrap-icons.svg#plus-square"/></svg></span>');
define('ICON_ADD_TIME',      '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter du temps</title><use xlink:href="images/bootstrap-icons.svg#clock"/></svg></span>');
define('ICON_ADD_CATEGORY',  '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter un cat&eacute;gorie</title><use xlink:href="images/bootstrap-icons.svg#plus-circle"/></svg></span>');
define('ICON_ADD_SUPPLIER',  '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter un fournisseur</title><use xlink:href="images/bootstrap-icons.svg#plus-circle"/></svg></span>');
define('ICON_ADD_TEAM',      '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter une &eacute;quipe</title><use xlink:href="images/bootstrap-icons.svg#plus-circle"/></svg></span>');
define('ICON_ADD_EQUIPMENT', '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter un appareil</title><use xlink:href="images/bootstrap-icons.svg#plus-circle"/></svg></span>');
define('ICON_ADD_USER',      '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter un utilisateur</title><use xlink:href="images/bootstrap-icons.svg#plus-circle"/></svg></span>');
define('ICON_MARK_RIGHT',    '<span><svg width="1.2em" height="1.2em" fill="currentColor"><use xlink:href="images/bootstrap-icons.svg#arrow-right-square"/></svg></span>');
define('ICON_ADMIN', 		 '<span><svg width="1.2em" height="1.2em" fill="currentColor"><use xlink:href="images/bootstrap-icons.svg#shield-lock"/></svg></span>');
define('ICON_INTERVENTION',	 '<span><svg width="1.2em" height="1.2em" fill="currentColor"><title>Ajouter une intervention</title><use xlink:href="images/bootstrap-icons.svg#hammer"/></svg></span>');

// ---------------------------------------------------------------------

function en_tete($titre, $find=false) {
	$pdo = connect_db_minimal();
	if (empty($_SESSION['logged_user']))
		$logged_level = 0;
	if (!empty($_SESSION)) {
		$logged_user  = $_SESSION['logged_user'];
		$actual_theme = $_SESSION['logged_theme'];
		$logged_level = $_SESSION['logged_level'];
		$user         = get_user_all_by_login($pdo, $logged_user);
	}
	else {
		if (empty($_COOKIE['GestEx-Theme'])) {
			$actual_theme = theme('random');
			setcookie('GestEx-Theme', "$actual_theme", time() + 7200); // 2 h
		} else {
			$actual_theme = $_COOKIE['GestEx-Theme'];
		}
	}
	$css_style = 'theme-'.$actual_theme.'.css';
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link rel="icon" sizes="192x126" href="images/logo-gestex-192.png">
	<title>GestEx - <?php echo filter_var($titre, FILTER_SANITIZE_STRING) ?></title>
	<link href="style/<?php echo $css_style ?>" rel ="stylesheet" type="text/css">
	<link href="style/common.css" rel ="stylesheet" type="text/css">
	<script src="script/sorttable-gestex.js"></script>
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
	if (!empty($_SESSION)) {
		nav_bar($pdo, $user['prenom'], $user['nom'], $logged_level, $_SESSION['logged_id'], $find);
	} else {
		nav_bar($pdo, '', '', 0, 0, $find);
	}
}

// ---------------------------------------------------------------------

function nav_bar($pdo, $prenom, $nom, $level, $logged_id, $find) {
?>
<div class="navbar">
<ul>
	<li><a href="supplier-list.php">Fournisseurs</a></li>
	<li><a href="user-list.php">Utilisateurs</a></li>
	<li><a href="team-list.php">&Eacute;quipes</a></li>
	<li><a href="loan-list.php">Appareils pr&ecirc;t&eacute;s</a></li>
	<li class="dropdown">
		<a class="dropbtn">Appareils</a>
		<div class="dropdown-content">
			<a href="category-list.php"><b>Cat&eacute;gories</b></a>
			<a href="equipment-list.php"><b>Global</b></a>
			<a href="equipment-list.php?loanable=yes"><b>Empruntable</b></a>
			<?php foreach (get_team_with_appareil($pdo) as $team): ?>
			<a href="equipment-list.php?equipe=<?= $team['id'] ?>">
				<b><?= htmlspecialchars($team['nom']) ?></b>
			</a>
			<?php endforeach; ?>
		</div>
	</li>

	<?php if ($level >= 2) { ?>
	<li class="dropdown">
		<a class="dropbtn">Ajouter</a>
		<div class="dropdown-content">
			<?php if ($level >= 3) { ?><a href="equipment-edit.php">Appareil</a><?php } ?>
			<?php if ($level >= 3) { ?><a href="category-edit.php">Cat&eacute;gorie</a><?php } ?>
			<?php if ($level >= 3) { ?><a href="team-edit.php">&Eacute;quipe</a><?php } ?>
			<a href="supplier-edit.php">Fournisseur</a>
			<?php if ($level >= 3) { ?><a href="user-edit.php">Utilisateur</a><?php } ?>
			<?php if ($level >= 3) { ?><a href="intervention-edit.php">Intervention</a><?php } ?>
		</div>
	</li>
	<?php } ?>

	<?php if ($level == 0) { ?>
	<li class="right"><a href="login.php">Se connecter</a></li>
	<?php } else { ?>
	<li class="dropdown right">
		<a class="dropbtn"><?php echo "$nom",   "  $prenom ";?></a>
		<div class="dropdown-content">
			<a href="logout.php"><?php echo ICON_LOGIN;?> Se d&eacute;connecter</a>
			<a href="user-edit.php?id=<?php echo $logged_id ?>"><?php echo ICON_PERSON_PROFIL;?> Modifier le profil</a>
			<a href="user-changepwd.php?id=<?php echo $logged_id ?>"><?php echo ICON_PERSON_PASWD;?> Changer le<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mot de passe</a><?php
			if ($level > 3) { echo '<a href="admin-panel.php">'.ICON_ADMIN.' Admin Panel</a>'; } ?>
			<a href="user-loan.php?id=<?php echo $logged_id ?>"><?php echo ICON_LOAN_BORROWED;?> Mes emprunts</a>
		</div>
	</li>
	<?php } ?>

	<?php if ($find) { ?>
	<li class="search">
		<form method="POST" name="search">
			<input type="search" name="find" size="10" maxlength="20" value="<?php if (!($find === true)) {echo $find;} ?>" placeholder="Rechercher" results>
			<input type="submit" hidden>
		</form>
	</li>
	<?php } ?>
</ul>
</div>
<?php
}

// ---------------------------------------------------------------------

function pied_page() {
   //ne garde que le nom de fichier
   $filetmp = explode('/',$_SERVER['PHP_SELF']);
   $file = $filetmp[count($filetmp) - 1];
   ///mise a jour de ce fichier
   $last_update = strftime('%Y-%m-%d', filemtime($file));
?>

<div class="footer">
<center>
	<!-- <img src="images/striped.gif" nosave="" border="0" height="13" width="532" align="bottom"> -->
	<hr>
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

// ---------------------------------------------------------------------

function render(string $template, array $data = []): string {
	extract($data, EXTR_SKIP);

	ob_start();
	include_once "include/$template";
	return ob_get_clean();
}

// ---------------------------------------------------------------------

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

// ---------------------------------------------------------------------

function redirect($link='index.php') {
	Header('Location: '.$link);
	exit();
}

// ---------------------------------------------------------------------

function loan_list_container($pdo, $equipment_loans, $equipment_loan_reserved=false, $loan_borrow=false, $logged_level) {
	?>
	<div class="loan-list-container">
	<?php if ($equipment_loan_reserved) { ?>
		<div>
			<h4 style="background-color: var(--color-alert);">Dernier retour N°<?php echo $equipment_loan_reserved[0]["id"] ?></h4>
			<?php
			echo $equipment_loan_reserved[0]['emprunt'].'&nbsp;&#8594;&nbsp;'.$equipment_loan_reserved[0]['retour'].PHP_EOL;
			echo '<br>'.get_team_by_id($pdo, $equipment_loan_reserved[0]['equipe'])["nom"].PHP_EOL;
			echo '<br>'.$equipment_loan_reserved[0]['commentaire'].PHP_EOL;
			?>
		</div>
	<?php } ?>

	<?php
	if ($equipment_loans) {
		foreach ($equipment_loans as $loan_current) {
			echo '<div>'.PHP_EOL;

			if ($loan_current["status"] == STATUS_LOAN_BORROWED) {
				#echo '<h4 style="background-color: var(--color-ok);">Emprunt actuel N°'.$loan_current["id"].' '.PHP_EOL;
				echo '<h4 style="background-color: var(--color-ok);">Emprunt actuel '.PHP_EOL;
			} else {
				echo '<h4>Réservation N°'.$loan_current["id"].' '.PHP_EOL;
			}
			echo '&nbsp; <span class="option-right">';
			if ($logged_level >= 3 && $loan_current["status"] == STATUS_LOAN_BORROWED) {
				echo '<a href="loan-del.php?id='.$loan_current['id'].'">'.ICON_LOAN_RETURNED.'</a>'.PHP_EOL;
			}
			if ($logged_level >= 3 && $loan_current["status"] == STATUS_LOAN_RESERVED && $loan_borrow == false) {
				echo '<a href="loan-process.php?id='.$loan_current['id'].'&mode=loan">'.ICON_LOAN_BORROWED.'</a>'.PHP_EOL;
			}
			
			if ($logged_level >= 3 && $loan_current["status"] == STATUS_LOAN_RESERVED) {
				echo '<a href="loan-del.php?id='.$loan_current["id"].'">'.ICON_TRASH.'</a>'.PHP_EOL;
			}

			if ($logged_level >= 3 && $loan_current["status"] != STATUS_LOAN_RETURNED) {
				echo '<a href="loan-edit.php?id='.$loan_current['id'].'&mode=edit">'.ICON_EDIT.'</a>'.PHP_EOL;
			}
			echo '</span>'.PHP_EOL;
			echo '</h4>'.PHP_EOL;
			echo $loan_current['emprunt'].'&nbsp;&#8594;&nbsp;'.$loan_current['retour'].PHP_EOL;

			echo '<br>'.get_team_by_id($pdo, $loan_current['equipe'])['nom'].PHP_EOL;
			echo '<br>'.$loan_current['commentaire'].PHP_EOL;
			echo '</div>'.PHP_EOL;
		}
	}
	?>
</div>
<?php
}
?>
