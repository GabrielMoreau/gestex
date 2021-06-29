<?php if (!$web_page) exit() ?>

<?php
require_once('base-functions.php');
require_once('connect.php');

// ---------------------------------------------------------------------

define('GESTEX_DB_VERSION', 4);

// ---------------------------------------------------------------------

// connexion au serveur mySQL

function connect_db_minimal() {
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

function connect_db() {
	if ($pdo = connect_db_minimal()) {
		try{
			$datasheet_version = get_version_by_name($pdo, 'datasheet');
			if ($datasheet_version < GESTEX_DB_VERSION) {
				error_log('Database version error: update the database schema');
				echo "Mettre à jour le schéma de la base de données";
				return false
			}
		}
		catch(PDOException $exception){
			error_log('Database version error: '.$exception->getMessage());
			echo $exception->getMessage();
			return false;
		}
	}

	return $pdo;
}

// ---------------------------------------------------------------------

function connect_db_or_alert() {
	if ($pdo = connect_db())
		return $pdo;

	include_once('include/alert-db.php');
	exit();
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
	//$sql = 'SELECT * FROM ? WHERE ? = ?;';
	$stmt = $pdo->prepare("SELECT * FROM $table WHERE $col = '$value'");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	///echo "check_val:".numrows_db($reponse)."<br />";
	//renvoie 0 si non trouve
	//renvoie le nbre d'occurences autrement
	if (count($result) > 0)
		return true;
	return false;
}

// ---------------------------------------------------------------------
// Category
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

function set_category_new($pdo, $name) {
	$sql = 'INSERT INTO categorie (nom) VALUE (?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($name));
	return $pdo->lastInsertId();
}

// ---------------------------------------------------------------------

function set_category_update($pdo, $id_category, $name) {
	$sql = 'UPDATE categorie SET nom = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($name, $id_category));
}

// ---------------------------------------------------------------------

function del_category_by_id($pdo, $id) {
	$sql = 'DELETE LOW_PRIORITY FROM categorie WHERE id = ? LIMIT 1';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($id));
	return $iostat;
}

// ---------------------------------------------------------------------
// Datasheet
// ---------------------------------------------------------------------

function get_datasheet_basepath() {
	return './data/datasheet';
}

// ---------------------------------------------------------------------

function get_datasheet_all_by_id($pdo, $id) {
	$sql = 'SELECT * FROM datasheet WHERE id = ?;' ;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
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

function set_datasheet_new($pdo, $equipment_id, $file_field_name) {
	$datasheet_filename_upload = $_FILES[$file_field_name]['name'];
	$datasheet_tmp_file        = $_FILES[$file_field_name]['tmp_name'];
	$datasheet_io_error        = $_FILES[$file_field_name]['error'];

	$file_upload_errors = array(
		0 => 'There is no error, the file uploaded with success',
		1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
		3 => 'The uploaded file was only partially uploaded',
		4 => 'No file was uploaded',
		6 => 'Missing a temporary folder',
		7 => 'Failed to write file to disk.',
		8 => 'A PHP extension stopped the file upload.',
	);
	if (!is_uploaded_file($datasheet_tmp_file) or $datasheet_io_error > 0) {
		error_log('Error: not uploaded datasheet file - '.$datasheet_filename_upload.' - '.$file_upload_errors[$datasheet_io_error]);
		return false;
	}

	if (!preg_match('/\.pdf$/i', $datasheet_filename_upload)) {
		error_log('Error: datasheet file not a pdf - '.$datasheet_filename_upload);
		return false;
	}

	$new_datasheet_path = './data/datasheet';
	if (!is_dir($new_datasheet_path))
		mkdir($new_datasheet_path, 0755);

	$datasheet_filename_no_ext = pathinfo($datasheet_filename_upload, PATHINFO_FILENAME);
	$datasheet_filename_kebab = string_to_filename_kebab($datasheet_filename_no_ext).'.pdf';

	$sql1 = 'INSERT INTO datasheet (description, id_equipment) VALUES (?, ?);';
	$stmt1 = $pdo->prepare($sql1);
	$stmt1->execute(array($datasheet_filename_no_ext, $equipment_id));
	$id_datasheet = $pdo->lastInsertId();

	$sub_path = $id_datasheet.'-'.random_string(8);
	$sql2 = 'UPDATE datasheet SET pathname = ? WHERE id = ?;';
	$stmt2 = $pdo->prepare($sql2);
	$stmt2->execute(array($sub_path.'/'.$datasheet_filename_kebab, $id_datasheet));

	$new_dir = $new_datasheet_path.'/'.$sub_path;
	if (!is_dir($new_dir))
		mkdir($new_dir, 0755);

	$iostat = move_uploaded_file($datasheet_tmp_file, $new_dir.'/'.$datasheet_filename_kebab);
	if (!$iostat) {
		error_log('Error: not move datasheet file '.$datasheet_filename_upload.' to '.$datasheet_filename_kebab);
		del_datasheet_by_id($pdo, $id_datasheet);
		return false;
	}

	return $id_datasheet;
}

// ---------------------------------------------------------------------

function del_datasheet_by_id($pdo, $id) {
	$datasheet_selected = get_datasheet_all_by_id($pdo, $id);

	$datasheet_basepath = get_datasheet_basepath();
	$datasheet_pathname = $datasheet_selected['pathname'];
	$datasheet_dirname  = pathinfo($datasheet_pathname, PATHINFO_DIRNAME);

	if (is_file($datasheet_basepath.'/'.$datasheet_pathname))
		$iostat = unlink($datasheet_basepath.'/'.$datasheet_pathname);

	if (!empty($datasheet_dirname) and is_dir($datasheet_basepath.'/'.$datasheet_dirname))
		$iostat = rmdir($datasheet_basepath.'/'.$datasheet_dirname);

	$sql = 'DELETE LOW_PRIORITY FROM datasheet WHERE id = ? LIMIT 1;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($id));
	return $iostat;
}

// ---------------------------------------------------------------------
// Equipment
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

function get_equipment_listall($pdo) {
	// $sql = 'SELECT * FROM Listing ORDER BY categorie, nom;';
	$sql = 'SELECT DISTINCT e.*, c.nom AS category_name FROM Listing AS e INNER JOIN categorie AS c ON e.categorie = c.id ORDER BY c.nom, e.nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function get_equipment_listall_by_team($pdo, $id_team) {
	//$sql = 'SELECT * FROM Listing WHERE equipe = ? ORDER BY categorie, nom;';
	$sql = 'SELECT DISTINCT e.*, c.nom AS category_name FROM Listing AS e INNER JOIN categorie AS c ON e.categorie = c.id WHERE e.equipe = ? ORDER BY c.nom, e.nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_team));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function get_equipment_count_loanable_by_team($pdo, $id_team) {
	$sql = 'SELECT COUNT(*) as count FROM Listing AS e WHERE e.loanable = 1 and e.equipe = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_team));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'];
}

// ---------------------------------------------------------------------

function get_equipment_listall_by_category($pdo, $id_category) {
	$sql = 'SELECT * FROM Listing WHERE categorie = ? ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_category));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function get_equipment_listshort($pdo) {
	$sql = 'SELECT id, nom FROM Listing ORDER BY categorie, nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function get_equipment_by_loan_id($pdo, $id_loan) {
	$sql = 'SELECT nom FROM pret WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_loan));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]["nom"];
}

// ---------------------------------------------------------------------

function set_equipment_new($pdo, $categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable) {
	$sql = 'INSERT INTO Listing (categorie, nom, modele, gamme, equipe, fournisseur, achat, responsable, reparation, accessoires, inventaire, notice, barcode, loanable)';
	$sql .=            ' VALUES (?,         ?,   ?,      ?,     ?,      ?,           ?,     ?,           ?,          ?,           ?,          ?,      ?,       ?);';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return array($pdo->lastInsertId(), $err_msg);
}

// ---------------------------------------------------------------------

function set_equipment_update($pdo, $id_equipment, $categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable) {
	$sql = 'UPDATE Listing SET categorie = ?, nom = ?, modele = ?, gamme = ?, equipe = ?, fournisseur = ?, achat = ?, responsable = ?, reparation = ?, accessoires = ?, inventaire = ?, notice = ?, barcode = ?, loanable = ? WHERE id = ?;)';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable, $id_equipment));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return $err_msg;
}

// ---------------------------------------------------------------------

function del_equipment_by_id($pdo, $id) {
	$sql = 'DELETE LOW_PRIORITY FROM Listing WHERE id = ? LIMIT 1;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($id));
	return $iostat;
}

// ---------------------------------------------------------------------
// Loan
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

function get_all_reservations_equipment($pdo, $id_equipment) {
	$sql = 'SELECT * FROM pret WHERE nom = ? AND status != \''.STATUS_LOAN_RETURNED.'\' ORDER BY status DESC, emprunt ASC, retour ASC;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

function get_loan_short_by_id_equipment($pdo, $id_equipment) {
	// recupere l'appareil via l'id qui est mis dans un champs texte (nom) !
	$sql = 'SELECT id, status FROM pret WHERE nom = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

function get_loan_all_by_id_equipment($pdo, $id_equipment) {
	// recupere l'appareil via l'id qui est mis dans un champs texte (nom) !
	$sql = 'SELECT * FROM pret WHERE nom = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

function get_loan_listall($pdo) {
	//$sql = 'SELECT * FROM pret;';
	$sql = 'SELECT DISTINCT l.*, e.nom AS equipment_name FROM pret AS l INNER JOIN Listing AS e ON l.nom = e.id WHERE status = \''.STATUS_LOAN_BORROWED.'\' ORDER BY l.retour DESC, l.emprunt DESC, e.nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}
// ---------------------------------------------------------------------

function get_loan_listall_by_team($pdo, $id_team) {
	$sql = 'SELECT DISTINCT l.*, e.nom AS equipment_name FROM pret AS l INNER JOIN Listing AS e ON l.nom = e.id WHERE l.equipe = ? AND status = \''.STATUS_LOAN_BORROWED.'\' ORDER BY l.retour DESC, l.emprunt DESC, e.nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_team));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

// ---------------------------------------------------------------------

function get_loan_count_by_team($pdo, $id_team) {
	$sql = 'SELECT COUNT(*) as count FROM pret AS l INNER JOIN Listing AS e ON l.nom = e.id WHERE e.equipe = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_team));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'];
}

// ---------------------------------------------------------------------

function get_loan_find($pdo, $find) {
	$sql = 'SELECT * FROM pret WHERE commentaire RLIKE ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($find));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function get_loans_blacklist_by_equipment($pdo, $id_equipment) {
	$sql = 'SELECT * FROM pret WHERE nom = ? AND status = \''.STATUS_LOAN_BORROWED.'\';';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

function get_loans_interval_by_id($pdo, $id_equipment, $from, $to) {
	$sql = 'SELECT * FROM pret WHERE ((`emprunt` <= ? AND `retour` >= ?) AND `nom` = ?) OR ((`emprunt` <= ? AND `retour` >= ?) AND `nom` = ?) OR ((`emprunt` >= ? AND `retour` <= ?) AND `nom` = ?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($from, $from, $id_equipment, $to, $to, $id_equipment, $from, $to, $id_equipment));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

function get_loans_interval_by_id_except_self($pdo, $id_equipment, $from, $to, $except_id) {
	$sql = 'SELECT * FROM pret WHERE (((`emprunt` <= ? AND `retour` >= ?) AND `nom` = ?) OR ((`emprunt` <= ? AND `retour` >= ?) AND `nom` = ?) OR ((`emprunt` >= ? AND `retour` <= ?) AND `nom` = ?)) AND NOT id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($from, $from, $id_equipment, $to, $to, $id_equipment, $from, $to, $id_equipment, $except_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

function get_loan_status_by_id($pdo, $id_loan) {
	$sql = 'SELECT status FROM pret WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_loan));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]["status"];
}

// ---------------------------------------------------------------------

function get_last_reserved_loan($pdo, $id_equipment) {
	$sql = 'SELECT * FROM pret WHERE nom = ? AND status = \''.STATUS_LOAN_RETURNED.'\' ORDER BY retour DESC LIMIT 1;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

function set_loan_new($pdo, $id_equipment, $id_team, $date_begin, $date_end, $comment) {
	$sql = 'INSERT INTO pret (nom, equipe, emprunt, retour, commentaire, status) VALUES (?, ?, ?, ?, ?, \''.STATUS_LOAN_BORROWED.'\');';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment, $id_team, $date_begin, $date_end, $comment));
	return $pdo->lastInsertId();
}

// ---------------------------------------------------------------------

function set_booking_update_to_loan($pdo, $id_loan) {
	$sql = "UPDATE pret SET status = '".STATUS_LOAN_BORROWED."' WHERE id = $id_loan;";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
}

// ---------------------------------------------------------------------

function set_booking_new($pdo, $id_equipment, $id_team, $date_begin, $date_end, $comment) {
	$sql = 'INSERT INTO pret (nom, equipe, emprunt, retour, commentaire, status) VALUES (?, ?, ?, ?, ?, \''.STATUS_LOAN_RESERVED.'\');';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment, $id_team, $date_begin, $date_end, $comment));
	return $pdo->lastInsertId();
}

// ---------------------------------------------------------------------

function set_loan_update($pdo, $id_loan, $id_equipment, $id_team, $date_begin, $date_end, $comment) {
	$sql = 'UPDATE pret SET nom = ?, equipe = ?, emprunt = ?, retour = ?, commentaire = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_equipment, $id_team, $date_begin, $date_end, $comment, $id_loan));
}

// ---------------------------------------------------------------------

function del_loan_by_id($pdo, $id) {
	$sql = 'DELETE LOW_PRIORITY FROM pret WHERE id = ? LIMIT 1;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($id));
	return $iostat;
}

// ---------------------------------------------------------------------

function set_loan_to_returned($pdo, $id) {
	$sql = "UPDATE LOW_PRIORITY pret SET status = '".STATUS_LOAN_RETURNED."', retour = CURRENT_DATE WHERE id = $id;";
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute();
	return $iostat;
}


// ---------------------------------------------------------------------
// Supplier
// ---------------------------------------------------------------------

function get_supplier_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM fournisseurs WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

function get_supplier_all_by_id($pdo, $id) {
	$sql = 'SELECT * FROM fournisseurs WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
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

function set_supplier_new($pdo, $name, $address, $tel, $fax, $email, $www, $contact, $description) {
	$sql = 'INSERT INTO fournisseurs (nom, adresse, mail, www, tel, fax, contact, descr) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($name, $address, $tel, $fax, $email, $www, $contact, $description));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return array($pdo->lastInsertId(), $err_msg);
}

// ---------------------------------------------------------------------

function set_supplier_update($pdo, $id_supplier, $name, $address, $tel, $fax, $email, $www, $contact, $description) {
	$sql = 'UPDATE LOW_PRIORITY fournisseurs  SET nom = ?, adresse = ?, tel = ?, fax = ?, mail = ?, www = ?, contact = ?, descr = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($name, $address, $tel, $fax, $email, $www, $contact, $description, $id_supplier));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return $err_msg;
}

// ---------------------------------------------------------------------

function del_supplier_by_id($pdo, $id) {
	$sql = 'DELETE LOW_PRIORITY FROM fournisseurs WHERE id = ? LIMIT 1;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($id));
	return $iostat;
}

// ---------------------------------------------------------------------
// Team
// ---------------------------------------------------------------------

function get_team_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

function get_team_all_by_id($pdo, $id) {
	$sql = 'SELECT * FROM equipe WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
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
	$sql = 'SELECT DISTINCT equipe.id, equipe.nom FROM equipe INNER JOIN Listing ON equipe.id = Listing.equipe ORDER BY equipe.nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$team_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $team_fetch;
}

// ---------------------------------------------------------------------

function get_team_count($pdo) {
	$sql = 'SELECT COUNT(*) as count FROM equipe;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'];
}

// ---------------------------------------------------------------------

function set_team_new($pdo, $name, $description, $account, $manager) {
	$sql = 'INSERT INTO equipe (nom, descr, compte, chef) VALUES (?,  ?, ?, ?);';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($name, $description, $account, $manager));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return array($pdo->lastInsertId(), $err_msg);
}

// ---------------------------------------------------------------------

function set_team_update($pdo, $id_team, $name, $description, $account, $manager) {
	$sql = 'UPDATE LOW_PRIORITY equipe SET nom = ?, descr = ?, compte = ?, chef = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($name, $description, $account, $manager, $id_team));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return $err_msg;
}

// ---------------------------------------------------------------------

function del_team_by_id($pdo, $id) {
	$sql = 'DELETE LOW_PRIORITY FROM equipe WHERE id = ? LIMIT 1';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($id));
	return $iostat;
}

// ---------------------------------------------------------------------
// User
// ---------------------------------------------------------------------

function get_user_by_id($pdo, $id) {
	$sql = 'SELECT id, nom, prenom FROM users WHERE id = ?;';
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

function get_user_listall_by_logged_level($pdo, $logged_level) {
	if ($logged_level > 3)       // lorsqu'on est haut place, on voit tout le monde
		$sql = 'SELECT * FROM users ORDER BY nom, prenom;';
	else if ($logged_level == 3) // losrqu'on est de niveau 3, on voit tout le monde sauf les users de plus haut level
		$sql = 'SELECT * FROM users WHERE level < 4 ORDER BY nom, prenom;';
	else                         // lorsqu'on est < 3, on voit tout le monde sauf le suser de level > 3 et les users non valides
		$sql = 'SELECT * FROM users WHERE valid = 1 and level < 3 ORDER BY nom, prenom;';
	$stmt = $pdo->prepare($sql);
    $stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function get_user_listshort_with_right($pdo, $level_min=1, $id_bonus=0) {
	$sql = 'SELECT id, nom, prenom FROM users WHERE (valid = 1 and level >= ?) or id = ? ORDER BY nom, prenom;';
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($level_min, $id_bonus));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

function get_user_count($pdo) {
	$sql = 'SELECT COUNT(*) as count FROM users;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'];
}

// ---------------------------------------------------------------------

function set_user_new($pdo, $familyname, $firstname, $login, $password, $email, $level, $tel, $team_id, $theme) {
	$sql = 'INSERT INTO users (nom, prenom, loggin, password, email, level, tel, equipe, valid, theme) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?);';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($familyname, $firstname, $login, $password, $email, $level, $tel, $team_id, $theme));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return array($pdo->lastInsertId(), $err_msg);
}

// ---------------------------------------------------------------------

function set_user_password_by_id($pdo, $user_id, $user_password) {
	$sql = 'UPDATE users SET password = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($user_password, $user_id));
	return $iostat;
}

// ---------------------------------------------------------------------

function set_user_status_by_id($pdo, $user_id, $user_status) {
	$sql = 'UPDATE users SET valid = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($user_status, $user_id));
	return $iostat;
}

// ---------------------------------------------------------------------

function set_user_update($pdo, $user_id, $familyname, $firstname, $email, $level, $tel, $team_id, $theme) {
	$sql = 'UPDATE LOW_PRIORITY users SET nom = ?, prenom = ?, email = ?, level = ?, tel = ?, equipe = ?, theme = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($familyname, $firstname, $email, $level, $tel, $team_id, $theme, $user_id));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return $err_msg;
}

// ---------------------------------------------------------------------
// Version
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
