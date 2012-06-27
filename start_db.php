<?php 
require("db_functions.php");

if ( $connex = connect_db() ){
		//inscription
	$table = "equipe";
		$result = mysql_query("INSERT INTO $table ".
			"(nom,descr,compte, chef)".
			" VALUES ('pool_G', 'equipe technique batG', '0', '1')");
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
		echo "<br>erreur :".$erreur;
	}

		$mot_crypte = md5('lemans4!');
	$table = "users";
		$result = mysql_query("INSERT INTO $table ".
			"(nom,prenom,loggin,password,level, email,equipe, tel)".
			" VALUES ('Carecchio', 'Pierre', 'carecchi', '$mot_crypte', '3', 'Pierre.Carecchio@hmg.inpg.fr', '', '25134')");
			//
 		if (!$result){
			//inscription !ok
			$erreur = mysql_error();
		echo "<br>erreur :".$erreur;
		}

	}//end if connect

?>