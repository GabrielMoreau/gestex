<?php

require("connect.php");

//////////////////////////////////////////////////////
// function connect_db(){
// connexion au serveur mySQL
// $connexion= mysql_pconnect(SERVER, USER, PASSWD);

// selection de la base bultaco
// return mysql_select_db(DATABASE, $connexion);
// }

function connect_db() {
	try{
		$db = new PDO('mysql:host='.GESTEX_DB_SERVER.'; dbname='.GESTEX_DB_DATABASE, GESTEX_DB_USER, GESTEX_DB_PASSWORD);
	}
	catch(PDOException $exception){
		error_log('Connection error: '.$exception->getMessage());
		echo $exception->getMessage();
		return false;
	}
	return $db;
}

function query_db($statement) {
	$result   = mysql_query($statement) or die("<pre>\n\nCan't perform query: " . mysql_error() . " \n\n$statement\n\n</pre>");
	$num_rows = numrows_db($result);
	return array($result, $num_rows);
}

function numrows_db($result) {
	return @mysql_num_rows($result);
}

function result_db($result,$i=-1) {
	if ($i >= 0) {
		@mysql_data_seek($result,$i);
	}
	return mysql_fetch_array($result);
}

function last_id_db() {
	return mysql_insert_id();
}

function check_val($table, $col, $value) {
	//teste l'existence de $value dans le champ $col de la table $table
	//echo "check in:".$table.":".$col." for ".$value."<br />";
	$pdo = connect_db();
	$sql = 'SELECT * FROM ? WHERE ? = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($table, $col, $value));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	///echo "check_val:".numrows_db($reponse)."<br />";
	//renvoie 0 si non trouve
	//renvoie le nbre d'occurences autrement
	return $result;
}
?>
