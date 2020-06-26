<?php
//liste_owners.php

// Authenticate
include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

en_tete("Liste de tous les utilisateurs:");
//recuper la methode de tri
$tri = $_GET['tri'];
// if (empty($tri))
// 	$tri ="nom";

echo "Tu es connect&eacute; en tant que : ".$logged_in_user." (".$user_id.")";
?>
<br />
<table cellpadding="2" cellspacing="2" border="0"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr class=menu>
	 <td style="vertical-align: top; text-align: center;">
	<a href="accueil.php">Retour a l'accueil</a>
	<br /></td>
<?php if ( $user_level ==3) {	?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_user.php">Ajout d'un utilisateur</a>
	<br /></td>
<?php }	
else	{ //edition/modif de ses propres coordonn�es
?>
 <td style="vertical-align: top; text-align: center;">
	<a href="add_user.php?id=<?php echo $user_id ?>">
		<img src="images/edit.png" nosave="" title="modifier son profil"></a>
	<br /></td>
 <?php } ?>
	 <td style="vertical-align: top; text-align: center;">
	<a href="changepwd.php?id=<?php echo $user_id ?>">
		<img src="images/unlock.png" nosave="" title="changer son mot de passe"></a>
	<br /></td>
	
 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php?variable=projet">Quitter</a>
	<br /></td> </tr></tbody>
</table>
<br />

<table cellpadding="2" cellspacing="2" border="1"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr bgcolor="#f7d709">
      <th style="vertical-align: top; text-align: center;">
	Prenom<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_users.php?tri=nom">Nom</a><br />
      </th>

      <th style="vertical-align: top; text-align: center;">
	Telephone<br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	Email <br />
      </th>
      <th style="vertical-align: top; text-align: center;">
	<a href ="list_users.php?tri=equipe">Equipe</a><br />
      </th>
    </tr>
<?php	//interrogation base de donn�es

if ( $pdo = connect_db() ){
	// recupere la liste des users
	if ($user_level==3){
		$sql = 'SELECT * FROM users ORDER by ?';
	}
	else{
		$sql = 'SELECT * FROM users WHERE valid = 1 ORDER by ?';
	}
	// list($qh,$num) = query_db($querry);
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($tri));
	$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$num_line=0;

// while ($data = result_db($qh)) {
	foreach($user as $data){
	// remplit le tableau
	if (($num_line % 2 )==0)
 		echo"<tr class=pair>";
	else
		echo"<tr class=impair>";

 echo "<td style=\"vertical-align: top;\">";
	echo $data['prenom'];
       echo"</td><td style=\"vertical-align: top;\">";
	//l'utilisateur a la possiblit� de modifier ses coordonn�es
	if ($user_id == $data['id'] || $user_level==3)
		echo "<a href=\"add_user.php?id=".$data['id']."\">".$data['nom']."</a>";
	else
		echo $data['nom'];
	
      echo"</td><td style=\"vertical-align: top;\">";
      echo $data['tel'];
      echo"</td><td style=\"vertical-align: top;\">";
      echo "<a href=\"mailto:".$data['email']."\"> <img src=\"images/mail_generic.png\" nosave=\"\"></a>";
      echo"</td><td style=\"vertical-align: top;\">";
 			// recupere la liste de equipes
	$sql = 'SELECT nom FROM equipe WHERE id =?';
	// list($qheq,$numeq) = query_db($querry);
	// 	$eq = result_db($qheq)	 ;
		$stmt = $pdo->prepare($sql);
        $stmt->execute(array($data['equipe']));
        $equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo $equipe[0]['nom'];
		
      echo " (".$data['equipe'].")";
     if ($user_level==3){
		 echo"</td><td style=\"vertical-align: top;\">";
		echo "<a href=\"changepwd.php?id=".$data['id']."\">";
		echo "<img src=\"images/unlock.png\" nosave=\"\" title=\"changer le mot de passe\"></a>";
		 echo"</td><td style=\"vertical-align: top;\">";
		echo "<a href=\"del_user.php?id=".$data['id']."\">";
		echo "<img src=\"images/kill.png\" nosave=\"\" title=\"supprimer l'utilisateur!\"></a>";
		 echo"</td><td style=\"vertical-align: top;\">";
		if ($data['valid']==0)
			echo "Non Valid�";
		else
			echo "Valid�";
		}

      echo"</td></tr>";$num_line++;
	}//end while
}//end if
?>
  </tbody>
</table>
<br />
</div>
<?php pied_page() ?>
