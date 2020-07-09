<?php
define('ICON_PERSON_OK', '<span class="check-ok"><svg width="1.2em" height="1.2em" fill="currentColor"><title>Valid&eacute;</title><use xlink:href="images/bootstrap-icons.svg#person-check"/></svg></span>');
define('ICON_PERSON_BAD', '<span class="check-bad"><svg width="1.2em" height="1.2em" fill="currentColor"><title>Non Valid&eacute;</title><use xlink:href="images/bootstrap-icons.svg#person-dash"/></svg></span>');

function en_tete($titre){
   /////echo"<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">\n";
   echo '<html>'.PHP_EOL;
   echo '<head>'.PHP_EOL;
   echo '  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />'.PHP_EOL;
   echo '  <link rel="icon" sizes="192x126" href="images/logo-gestex-192.png" />'.PHP_EOL;
   echo '  <title>GestEx - '.$titre.'</title>'.PHP_EOL;
   echo '  <link href="pool_project.css" rel="stylesheet" type="text/css" />'.PHP_EOL;
   echo '</head>'.PHP_EOL;
   echo '<body>'.PHP_EOL;
   echo '<div width="100%" height="100%" align="center" valign="center">'.PHP_EOL;
   echo '<br />'.PHP_EOL;
   echo '<br />'.PHP_EOL;
   echo '<table cellpadding="2" cellspacing="0" border="0" style="text-align: left; width: 75%;" align="center">'.PHP_EOL;
   echo '  <tbody>'.PHP_EOL;
   echo '    <tr bgcolor="#f7d709">'.PHP_EOL;
   echo '      <td style="vertical-align: center;">'.PHP_EOL;
   echo '        <a href="./"><img src="images/logo-gestex.png" nosave="" height="100" /></a>'.PHP_EOL;
   echo '      </td>'.PHP_EOL;
   echo '      <td style="vertical-align: top;"><br />'.PHP_EOL;
   echo '        <h1><a href="./">GestEx</a> - Gestion des plateformes Exp&eacute;rimentales</h1>'.PHP_EOL;
   echo '        <h2>'.$titre.'</h2>'.PHP_EOL;
   echo '      </td>'.PHP_EOL;
   echo '    </tr>'.PHP_EOL;
   echo '  </tbody>'.PHP_EOL;
   echo '</table>'.PHP_EOL;
   echo '<br />'.PHP_EOL;
   if(!empty($_SESSION)){
      $pdo            = connect_db();
      $logged_in_user = $_SESSION['logged_in_user'];
      $sql            = 'SELECT nom, prenom FROM users WHERE loggin = ?;';
      $stmt           = $pdo->prepare($sql);
      $stmt->execute(array($logged_in_user));
      $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
      nav_bar($user[0]['prenom'], $user[0]['nom'], $_SESSION['level'],$_SESSION['user_id']);
   }else{
      nav_bar('', '',0,0);
   }
   echo '<br />'.PHP_EOL;
   echo '</div>'.PHP_EOL;
}

// -------------------------------------------------------------

function nav_bar($prenom, $nom, $level, $user_id){
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
   <li><a href="list_manip.php">Liste des manips</a></li>
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
   <li><a href="list_manip.php">Liste des manips</a></li>

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
        <a href="list_appareil.php?equipe=15 pret=15">Pr&ecirc;t</a>
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
      </div>
    </li>
  <?php } ?>

  <li class="dropdown right">
         <a class="dropbtn"><?php echo "$nom",   "  $prenom ";?></a>
         <div class="dropdown-content">
            <a href="logout.php"><img src="images/box-arrow-in-right.svg" nosave=""> Se d&eacute;connecter</a>
            <a href="add_user.php?id=<?php echo $user_id ?>"><img src="images/gear.svg" nosave=""> Modifier mon profil</a>
            <a href="user_changepwd.php?id=<?php echo $user_id ?>"><img src="images/key.svg"> Modifier mon <br />&nbsp;&nbsp;&nbsp;&nbsp;mot de passe</a>
            <a href="user_pret.php?id=<?php echo $user_id ?>"><img src="images/box-arrow-in-down.svg" nosave=""> Mes emprunts</a>
        </div>
  </li>
  <?php } ?>
</ul>
</div>
<?php
}

// -------------------------------------------------------------

function pied_page(){
   echo '<center>'.PHP_EOL;
   echo '<img src="images/striped.gif" nosave="" border="0" height="13"  width="532" align="bottom" />'.PHP_EOL;

   //ne garde que le nom de fichier
   $filetmp = explode('/',$_SERVER['PHP_SELF']);
   $file = $filetmp[count($filetmp)-1];
   ///mise a jour de ce fichier
   echo '<table cellpadding="2" cellspacing="2" border="0" style="text-align: center; width: 95%;">'.PHP_EOL;
   echo '  <tbody>'.PHP_EOL;
   echo '    <tr>'.PHP_EOL;
   echo '      <td>'.PHP_EOL;
   echo '        <address><a href="mailto:'.GESTEX_ADMIN_MAIL.'?Subject=GestEx%20to%20WebMaster">GestEx WebMaster</a></address>'.PHP_EOL;
   echo '        <br />'.PHP_EOL;
   echo '        <i>Derni&egrave;re mise &agrave; jour : ';
   echo            strftime('%Y-%m-%d', filemtime($file));
   echo '        </i>'.PHP_EOL;
   echo '      </td>'.PHP_EOL;
   echo '    </tr>'.PHP_EOL;
   echo '  </tbody>'.PHP_EOL;
   echo '</table>'.PHP_EOL;
   echo '</center>'.PHP_EOL;
   echo '</body>'.PHP_EOL;
   echo '</html>'.PHP_EOL;
   }

// -------------------------------------------------------------

function check_mail($mail){
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

?>
