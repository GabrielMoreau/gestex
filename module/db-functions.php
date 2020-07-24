<?php

require_once('connect.php');

// ---------------------------------------------------------------------

// connexion au serveur mySQL

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

// ---------------------------------------------------------------------

function query_db($statement) {
	$result   = mysql_query($statement) or die("<pre>\n\nCan't perform query: " . mysql_error() . " \n\n$statement\n\n</pre>");
	$num_rows = numrows_db($result);
	return array($result, $num_rows);
}

// ---------------------------------------------------------------------

function numrows_db($result) {
	return @mysql_num_rows($result);
}

// ---------------------------------------------------------------------

function result_db($result,$i=-1) {
	if ($i >= 0) {
		@mysql_data_seek($result,$i);
	}
	return mysql_fetch_array($result);
}

// ---------------------------------------------------------------------

function last_id_db() {
	return mysql_insert_id();
}

// ---------------------------------------------------------------------

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

// ---------------------------------------------------------------------

function get_datasheet_listall_by_equipment($pdo, $id_equipment) {
	$sql = 'SELECT * FROM datasheet WHERE id_equipment = ?;' ;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function get_datasheet_count_by_equipment($pdo, $id_equipment) {
	$sql = 'SELECT COUNT(*) as count FROM datasheet WHERE id_equipment = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'];
}

// ---------------------------------------------------------------------

function get_equipment_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM Listing WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

function get_equipment_all_by_id($pdo, $id) {
	$sql = 'SELECT * FROM Listing WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

function get_equipment_listshort($pdo) {
	$sql = 'SELECT id, nom FROM Listing ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function get_category_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM categorie WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$category_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $category_fetch[0];
}

// ---------------------------------------------------------------------

function get_category_listshort($pdo) {
	$sql = 'SELECT id, nom FROM categorie ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function check_category_by_name($pdo, $name) {
	$sql = 'SELECT COUNT(*) as count FROM categorie WHERE nom = ?';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($name));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'] > 0 ? true : false;
}

// ---------------------------------------------------------------------

function get_loan_all_by_id_equipment($pdo, $id_equipment) {
	// recupere l'appareil via l'id qui est mis dans un champs texte (nom) !
	$sql = 'SELECT * FROM pret WHERE nom = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

function get_loan_all_by_id($pdo, $id) {
	$sql = 'SELECT * FROM pret WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

function get_loan_listall($pdo) {
	$sql = 'SELECT * FROM pret;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function set_loan_new($pdo, $id_equipment, $id_team, $date_begin, $date_end, $comment) {
	$sql = 'INSERT INTO pret (nom, equipe, emprunt, retour, commentaire) VALUES (?, ?, ?, ?, ?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment, $id_team, $date_begin, $date_end, $comment));
	return $pdo->lastInsertId();
}

// ---------------------------------------------------------------------

function get_supplier_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM fournisseurs WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$supplier_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $supplier_fetch[0];
}

// ---------------------------------------------------------------------

function get_supplier_listshort($pdo) {
	$sql = 'SELECT id, nom FROM fournisseurs ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$supplier_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $supplier_fetch;
}

// ---------------------------------------------------------------------

function get_supplier_listall($pdo) {
	$sql = 'SELECT * FROM fournisseurs ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$supplier_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $supplier_fetch;
}

// ---------------------------------------------------------------------

function get_supplier_find($pdo, $find='') {
	if (empty($find) or ($find === true))
		return get_supplier_listall($pdo);
	$sql = 'SELECT * FROM fournisseurs WHERE nom RLIKE ? OR descr RLIKE ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($find, $find));
	$supplier_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $supplier_fetch;
}

// ---------------------------------------------------------------------

function get_team_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$team_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $team_fetch[0];
}

// ---------------------------------------------------------------------

function get_team_listshort($pdo) {
	$sql = 'SELECT id, nom FROM equipe ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$team_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $team_fetch;
}

// ---------------------------------------------------------------------

function get_team_listall($pdo) {
	$sql = 'SELECT * FROM equipe ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$team_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $team_fetch;
}

// ---------------------------------------------------------------------

function get_team_with_appareil($pdo) {
	$sql = 'SELECT id, nom FROM equipe INNER JOIN Listing ON equipe.id = Listing.equipe;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$team_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $team_fetch;
}

// ---------------------------------------------------------------------

function get_user_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM users WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

function get_user_all_by_id($pdo, $id) {
	$sql = 'SELECT * FROM users WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

function get_user_all_by_login($pdo, $login) {
	$sql = 'SELECT * FROM users WHERE loggin = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($login));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

function get_version_by_name($pdo, $name) {
	$sql = 'SELECT version FROM version WHERE name = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($name));
	$version_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($version_fetch) > 0)
		return $version_fetch[0];
	return false;
}
// ---------------------------------------------------------------------

function set_version_by_name($pdo, $name, $version) {
	$sql = 'INSERT INTO version (name, version) VALUES (?, ?);';
	if (get_version_by_name($pdo, $name))
		$sql = 'UPDATE version SET version = ? WHERE name = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($name, $version));
}

?>
