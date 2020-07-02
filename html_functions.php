<?php

function en_tete($titre){
   /////echo"<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">\n";
   echo '<html>';
   echo '<head>';
   echo '  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />';
   echo '  <title>GestEx - '.$titre.'</title>';
   echo '  <link href="pool_project.css" rel="stylesheet" type="text/css" />';
   echo '</head>';
   echo '<body>';
   echo '<div width="100%" height="100%" align="center" valign="center">';
   echo '<br />';
   echo '<br />';
   echo '<table cellpadding="2" cellspacing="0" border="0" style="text-align: left; width: 75%;" align="center">';
   echo '  <tbody>';
   echo '    <tr bgcolor="#f7d709">';
   echo '      <td style="vertical-align: center;">';
   echo '        <a href="/"><img src="images/pool_project.jpg" nosave="" height="100" /></a>';
   echo '      </td>';
   echo '      <td style="vertical-align: top;"><br />';
   echo '        <h1><a href="./">GestEx</a> - Gestion des plateformes Exp&eacute;rimentales</h1>';
      if(!empty($_SESSION)){
         $pdo            = connect_db();
         $logged_in_user = $_SESSION['logged_in_user'];
         $sql            = 'SELECT nom, prenom FROM users WHERE loggin = ?;';
         $stmt           = $pdo->prepare($sql);
         $stmt->execute(array($logged_in_user));
         $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
         echo '<p><strong>'.$user[0]['nom'].'</strong> '.$user[0]['prenom'].' </p>';
      }else{
         echo "<p>Vous n'&ecirc;tes pas connect&eacute; </p>";
      }
   echo $titre;
   echo '      </td>';
   echo '    </tr>';
   echo '  </tbody>';
   echo '</table>';
   echo '<br />';
   nav_bar();
   echo '<br />';
   echo '</div>';
}

// -------------------------------------------------------------

function nav_bar(){
 ?>
<div class ="navbar">
  <link rel="stylesheet" type="text/css" href="nav_bar.css"> 
<ul>
<?php if(empty($_SESSION['level'])){ ?>
   <li><a href="list_fourn.php">Liste des fournisseurs</a></li>
  <li><a href="list_users.php">Liste des utilisateurs</a></li>
  <li><a href="list_equip.php">Liste des &eacute;quipes</a></li>
  <li class="dropdown">
         <a class="dropbtn">Liste des appareils</a>
         <div class="dropdown-content">
            <a href="essai.php">Cat&eacute;gories</a>
            <a href="instru.php">Global</a>
            <a href="instru.php?equipe=15">au service <br />instrumentation</a>
         </div>
   </li>  <li><a href="accueil.php">Liste des manips</a></li>
  <li class="right"><a href="login.php">Se connecter</a></li>
</ul>
</div>


<?php }else{ ?>
   <li><a href="list_fourn.php">Liste des fournisseurs</a></li>
   <li><a href="list_users.php">Liste des utilisateurs</a></li>
   <li><a href="list_equip.php">Liste des &eacute;quipes</a></li>
   <li class="dropdown">
         <a class="dropbtn">Liste des appareils</a>
         <div class="dropdown-content">
            <a href="essai.php">Cat&eacute;gories</a>
            <a href="instru.php">Global</a>
            <a href="instru.php?equipe=15">au service <br />instrumentation</a>
         </div>
   </li>
   <!-- <li><a href="essai.php">Liste des appareils</a></li> -->
   <li><a href="accueil.php">Liste des manips</a></li>
   <?php if($_SESSION['level'] == 2){ ?>
      <li class="dropdown">
         <a class="dropbtn">Ajouter</a>
         <div class="dropdown-content">
            <a href="add_manip.php">Manip</a>
            <a href="add_fourn.php">Fournisseur</a>
            <a href="add_pret.php">Pr&ecirc;t</a>
            <a href="add_time.php">Temps</a>
            <a href="add_task.php">Task</a>
            <a href="add_labviews.php">Labview</a>
         </div>
      </li>
<?php }else if($_SESSION['level'] >= 3){ ?>
  
  <li class="dropdown">
    <a class="dropbtn">Ajouter</a>
    <div class="dropdown-content">
      <a href="add_manip.php">Manip</a>
      <a href="add_categorie.php">Cat&eacute;gorie</a>
      <a href="add_app.php">Maintenance</a>
      <a href="add_app2.php">Appareils</a>
      <a href="add_equip.php">&Eacute;quipe</a>
      <a href="add_fourn.php">Fournisseur</a>
      <a href="add_intapp.php?app=3">Intervention</a>
      <a href="add_user.php">User</a>
      <a href="add_pret.php">Pr&ecirc;t</a>
      <a href="add_time.php">Temps</a>
      <a href="add_task.php">Task</a>
      <a href="add_demandes.php">Demande</a>
      <a href="add_labviews.php">Labview</a>
    </div>
  </li>
  <li class="dropdown">
    <a class="dropbtn">Supprimer</a>
    <div class="dropdown-content">
      <a href="#">Link 1</a>
      <a href="#">Link 2</a>
      <a href="#">Link 3</a>
    </div>
  </li>
  <?php  } ?>
  <li class="right"><a href="logout.php">Se d&eacute;connecter</a></li>
  <?php } ?>
</ul>
</div>
 <?php
}

// -------------------------------------------------------------

function pied_page(){
   echo '<center>';
   echo '<img src="images/striped.gif" nosave="" border="0" height="13"  width="532" align="bottom" />';

   //ne garde que le nom de fichier
   $filetmp = explode('/',$_SERVER['PHP_SELF']);
   $file = $filetmp[count($filetmp)-1];
   ///mise a jour de ce fichier
   echo '<table cellpadding="2" cellspacing="2" border="0" style="text-align: center; width: 95%;">';
   echo '  <tbody>';
   echo '    <tr>';
   echo '      <td>';
   echo '        <!-- <img src="images/php-small-purple.gif" align="top" nosave="" /> -->';
   echo '      </td>';
   echo '      <td>';
   echo '        <address><a href="mailto:webmaster@legi.grenoble-inp.fr?Subject=GestEx%20to%20WebMaster">LEGI WebMaster</a></address>';
   echo '        <br />';
   echo '        <i>Derni&egrave;re mise &agrave; jour : ';
   echo            strftime('%Y-%m-%d', filemtime($file));
   echo '        </i>';
   echo '      </td>';
   echo '      <td><!-- <img src="images/mysql.png"  align="top" nosave="" /> -->';
   echo '      </td>';
   echo '    </tr>';
   echo '  </tbody>';
   echo '</table>';
   echo '</center>';
   echo '</body>';
   echo '</html>';
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
