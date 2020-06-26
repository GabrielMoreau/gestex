<?php

require("html_functions.php");

/// valid_inscr.php
//validation d'une nouvelle inscription

unset($erreur);unset($loggin);unset($password);unset($password2); unset($nom);
//variables ne pouvant etre nulles
if (empty($_POST[loggin]))
  $erreur="loggin non pr&eacute;cis&eacute;";
else {
  $loggin=$_POST[loggin];
  if (empty($_POST[password]))
    $erreur="password non pr&eacute;cis&eacute;";
  else {
    $password=$_POST[password];
    if (empty($_POST[password2]))
      $erreur="confirmation de password non pr&eacute;cis&eacute;";    
    else {
      $password2=$_POST[password2];
      if ($password!=$password2)
        $erreur="les passwords diff&egrave;rent";
      else {
        if (empty($_POST[nom]))
          $erreur="nom non pr&eacute;cis&eacute;";  
        else {
          $nom =$_POST[nom];
          if (!isset($_POST[level]))
            $erreur="Qualit&eacute; non pr&eacute;cis&eacute;";  
          else
            $level = $_POST[level];

          $mail=$_POST[addr_mail];
          //variables pouvant etre nulles
          $prenom=$_POST[prenom];
          $phone=$_POST[phone];
          $equipe=$_POST[equipe];
          }
        }
      }
    }
  }

en_tete("resultat inscription ");

require("db_functions.php");

if ( $connex = connect_db() ){

  if (check_val('users', 'nom', $nom)!=0){
    //nom existant deja dans db
    $erreur ="le nom <i>".$nom."</i> est déjà entré dans la base de données";
    }
  elseif ( check_val('users', 'loggin', $loggin)!=0){
    //nom existant deja dans db
    $erreur ="l'identifiant <i>".$loggin."</i> est déjà utilisé dans la base de données";
    }

  if (check_mail($mail) !=0){
    //adresse mail incorrecte
    $erreur ="l'adresse de courriel <i>".$mail."</i> est incorrecte";
    }

/*if (!empty($_POST[loggin]))
  echo "loggin :".$_POST[loggin].".";
echo "<br />passwd :".$password.".";
echo "<br />passwd :".$password2.".";
echo "<br />nom :".$nom.".";
echo "<br />mail :".$mail.".";
echo "<br />tel :".$phone.".";
echo "<br />Level :".$level.".";*/


if (!empty($erreur) ){

  //erreur

    echo "<br /><b>erreur de saisie :</b>".$erreur;
    echo"<br /><center><a href=\"add_user.php?".$loggin."\">Suite</a></center><br />\n";

    pied_page();
    exit();
  }
  else{
  ///tout est ok
  //pas d'erreur
  ///on inscrit
    //inscription
    $mot_crypte = md5($password);
  $table = "users";
    $result = mysql_query("INSERT INTO $table ".
      "(nom, prenom, loggin, password, email, level, tel, equipe, valid)".
      " VALUES ('$nom', '$prenom', '$loggin', '$mot_crypte', '$mail', '$level', '$phone', '$equipe', 0)");
      //
     if (!$result){
      //inscription !ok
      $erreur = mysql_error();
      echo "<br /><b>erreur mySQL:</b>".$erreur;
    }
    else{
      //inscription enregistrée mais pas encore validée!
      //envoi d'un mail a l'admin
      $texte = "Inscription de ".$prenom." ".$nom;
      mail(GESTEX_ADMIN_MAIL, "[GestEx] ajout utilisateur - ".$nom." ".$prenom, $texte);


      echo "inscription de ".$prenom." ".$nom."<br />";
      echo" <img src=\"images/pool_project.jpg\" height=\"100\" nosave=\"\" align=\"middle\" alt=\"\" />";
      echo" est propos&eacute;e avec le loggin : ".$loggin;
      echo"<br />Vous serez prevenu de sa validation par mail....";
      }//end else
    echo "<br /><center><a href=\"list_users.php\">Suite</a></center><br /><br />\n";
    pied_page();
    exit();
  }//else end
}//end if connect


?>
