<?php

require_once('connect.php');

//////////////////////////////////////////////////////
// function connect_db(){
// connexion au serveur mySQL
// $connexion= mysql_pconnect(SERVER, USER, PASSWD);

// selection de la base bultaco
// return mysql_select_db(DATABASE, $connexion);
// }

function connect_db() {
	try{
		$pdo = new PDO('mysql:host='.GESTEX_DB_SERVER.'; dbname='.GESTEX_DB_DATABASE, GESTEX_DB_USER, GESTEX_DB_PASSWORD);
	}
	catch(PDOException $exception){
		error_log('Connection error: '.$exception->getMessage());
		echo $exception->getMessage();
		return false;
	}
	return $pdo;
}

// -------------------------------------------------------------

function query_db($statement) {
	$result   = mysql_query($statement) or die("<pre>\n\nCan't perform query: " . mysql_error() . " \n\n$statement\n\n</pre>");
	$num_rows = numrows_db($result);
	return array($result, $num_rows);
}

// -------------------------------------------------------------

function numrows_db($result) {
	return @mysql_num_rows($result);
}

// -------------------------------------------------------------

function result_db($result,$i=-1) {
	if ($i >= 0) {
		@mysql_data_seek($result,$i);
	}
	return mysql_fetch_array($result);
}

// -------------------------------------------------------------

function last_id_db() {
	return mysql_insert_id();
}

// -------------------------------------------------------------

function check_val_in_db($pdo, $table, $col, $value) {
	//teste l'existence de $value dans le champ $col de la table $table
	//echo "check in:".$table.":".$col." for ".$value."<br />";
	$sql = 'SELECT * FROM ? WHERE ? = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($table, $col, $value));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	///echo "check_val:".numrows_db($reponse)."<br />";
	//renvoie 0 si non trouve
	//renvoie le nbre d'occurences autrement
	if (count($result) > 0)
		return true;
	return false;
}

// -------------------------------------------------------------

function get_appareil_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM Listing WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$appareil_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $appareil_fetch[0];
}

// -------------------------------------------------------------

function get_appareil_all_by_id($pdo, $id) {
	$sql = 'SELECT * FROM Listing WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$appareil_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $appareil_fetch[0];
}

// -------------------------------------------------------------

function get_categorie_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM categorie WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$categorie_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $categorie_fetch[0];
}

// -------------------------------------------------------------

function get_equip_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$equip_fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $equip_fetch[0];
}

// -------------------------------------------------------------

function get_equip_listshort($pdo) {
	$sql = 'SELECT id, nom FROM equipe;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$equip_fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $equip_fetch;
}

// -------------------------------------------------------------

function get_equip_listall($pdo) {
	$sql = 'SELECT * FROM equipe;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$equip_fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $equip_fetch;
}

// -------------------------------------------------------------

function get_equip_with_appareil($pdo) {
	$sql = 'SELECT id, nom FROM equipe INNER JOIN Listing ON equipe.id = Listing.equipe;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$equip_fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $equip_fetch;
}

// -------------------------------------------------------------

function get_fournisseur_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM fournisseurs WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$fournisseur_fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $fournisseur_fetch[0];
}

// -------------------------------------------------------------

function get_fournisseur_listshort($pdo) {
	$sql = 'SELECT id, nom FROM fournisseurs;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$fournisseur_fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $fournisseur_fetch;
}

// -------------------------------------------------------------

function get_fournisseur_listall($pdo) {
	$sql = 'SELECT * FROM fournisseurs;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$fournisseur_fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $fournisseur_fetch;
}

// -------------------------------------------------------------

function get_fournisseur_find($pdo, $find='') {
	if (empty($find) or ($find === true))
		return get_fournisseur_listall($pdo);
	$sql = 'SELECT * FROM fournisseurs WHERE nom RLIKE ? OR descr RLIKE ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($find, $find));
	$fournisseur_fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $fournisseur_fetch;
}

// -------------------------------------------------------------

function get_user_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM users WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$user_fetch =  $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $user_fetch[0];
}

?>
