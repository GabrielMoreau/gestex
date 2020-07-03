<?php

require("html_functions.php");
//recuper la methode de tri

/// valid_pret.php
//validation d'un pret
unset($erreur);
//variables ne pouvant etre nulles

if (empty($_POST['nom']))
	$erreur="nom non pr&eacute;cis&eacute;";
else{
	$nom =$_POST['nom'];

		if (empty($_POST['equipe']))
			$erreur="&Eacute;quipe non pr&eacute;cis&eacute;";
		else{
			$equipe =$_POST['equipe'];

							//variables pouvant etre nulles

if (empty($_POST['emprunt']))
		$erreur="date non pr&eacute;cis&eacute;";
	else{
		$emprunt=$_POST['emprunt'];

	$retour =$_POST['retour'];

				$commentaire =$_POST['commentaire'];

	}}}

en_tete("resultat ajout appareil ");

if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];

if (!empty($erreur) ){

	//erreur

	echo "<br />erreur :".$erreur;
	echo"<br /><a href=\"add-pret.php\">Suite</a><br />\n";

	pied_page();
	exit();
}
else{
///tout est ok
//pas d'erreur
///on inscrit
}
require("db_functions.php");

if ( $pdo = connect_db() ){
	$sql = 'SELECT * FROM pret where nom = ? and equipe = ?';
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($nom,$equipe));
	$pret = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if(!empty($pret)){
		echo 'erreur, l\'appareil est déjà emprunté';
		pied_page();
		exit();
	}
		//inscription
	$table = "pret";

		// $result = mysql_query("INSERT INTO $table ".
		// 	"(nom, equipe, emprunt, retour, commentaire)".
		// 	" VALUES ('$nom','$equipe','$emprunt','$retour','$commentaire')");
		$sql = 'INSERT INTO pret (nom, equipe, emprunt, retour, commentaire) VALUES (?,?,?,?,?);';
		$stmt = $pdo->prepare($sql);
        $stmt->execute(array($nom,$equipe,$emprunt,$retour,$commentaire));
			//
// if (!$result){
			//inscription !ok
			// $erreur = mysql_error();
		// echo "<br />erreur :".$erreur;
		// }
	// 	$querry = "SELECT * FROM pret ";
	// list($qh,$num) = query_db($querry);

	// $last_id=0;

$data = result_db($qh);
		echo "de $nom[nom]$equipe $nom<br />";
//	}//end if connect

$querry = "SELECT id, nom FROM equipe WHERE id='$equipe'";
	list($qheq,$numeq) = query_db($querry);
		$equip = result_db($qheq);

		$querry = "SELECT id, nom FROM Listing WHERE id='$nom'";
	list($qheeq,$numeeq) = query_db($querry);
		$nom = result_db($qheeq);

echo "de   $equip[nom] <br />";

echo "de  $nom[nom]  <br />";

			//echo "<br />ajout <br />";
//echo "de $nom[nom] $equip[nom]<br />";
//echo" est valid&eacute;e ";
echo"<br /><br /><a href=\"reserva.php?user=3\">Suite</a><br /><br />\n";

$querry = "SELECT email FROM users WHERE id='2'";
	list($qheh,$numeh) = query_db($querry);
	$email = result_db($qheh);

$querry = "SELECT email FROM users WHERE id='33'";
	list($qheeh,$numeeh) = query_db($querry);
	$email2 = result_db($qheeh);
//echo $email2[email];

//echo $email[email];
mail($email[email],demandedepret,$nom[nom].$equip[nom].$commentaire);

mail($email2[email],demandedepret,$nom[nom].$equip[nom].$commentaire);
//quand on va sur suite, on retourne sur la page du materiel commun
pied_page();

}//end if connect
exit();

?>
