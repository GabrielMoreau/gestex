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
				return false;
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

/**
 * Vérifie si un pret a été emprunté. Retourne "false" s'il n'y en a pas
 * et "true" s'il en trouve au moins un. Il n'est censé normalement n'y 
 * avoir qu'un seul pret emprunter.
 * 
 * @todo Pensez à faire une méthode pour avertir s'il y a plus de 1 pret
 * emprunté
 * @return boolean
 */
function check_loan_borrowed_by_equipment($pdo, $equipment_id) {
	$sql = 'SELECT id FROM pret WHERE nom = ? AND status = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id, STATUS_LOAN_BORROWED));
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result) > 0)
		return true;
	return false;
}

// ---------------------------------------------------------------------

/**
 * Permet de vérifier si la valeur existe dans la colonne d'une table
 * spécifié manuellement. La fonction retournera "true" si la vérification
 * trouve des données sinon elle retournera "false"
 * 
 * @return boolean
 */
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

/**
 * Récupère l'ID et le nom de la catégorie via son ID
 * 
 * @return array Retourne directement l'élément
 */
function get_category_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM categorie WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$category_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $category_fetch[0];
}

// ---------------------------------------------------------------------

/**
 * Récupère l'ID et le nom de toutes les catégories rangées 
 * par nom croissant
 * 
 * @return array
 */
function get_category_listshort($pdo) {
	$sql = 'SELECT id, nom FROM categorie ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

/**
 * Effectue une vérification pour savoir si le nom d'une catégorie
 * existe déjà. Retourne "true" si la catégorie est présente sinon
 * renvoie "false"
 * 
 * @return boolean
 */
function check_category_by_name($pdo, $name) {
	$sql = 'SELECT COUNT(*) as count FROM categorie WHERE nom = ?';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($name));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'] > 0 ? true : false;
}

// ---------------------------------------------------------------------

/**
 * Ajoute une catégorie
 * 
 * @return int
 */
function set_category_new($pdo, $name) {
	$sql = 'INSERT INTO categorie (nom) VALUE (?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($name));
	return $pdo->lastInsertId();
}

// ---------------------------------------------------------------------

/**
 * Met à jour une catégorie (nom) via son ID
 */
function set_category_update($pdo, $category_id, $name) {
	$sql = 'UPDATE categorie SET nom = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($name, $category_id));
}

// ---------------------------------------------------------------------

/**
 * Supprime une catégorie via son ID
 */
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

/**
 * Récupère tout le contenu d'une notice via son ID
 * 
 * @return false|array Retourne directement l'élément
 */
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

/**
 * Récupère tout le contenu des notices appartenant à un 
 * équipement spécifié
 * 
 * @return array
 */
function get_datasheet_listall_by_equipment($pdo, $equipment_id) {
	$sql = 'SELECT * FROM datasheet WHERE id_equipment = ?;' ;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

/**
 * Récupère le nombre de notice pour un équipement donné
 * 
 * @return int
 */
function get_datasheet_count_by_equipment($pdo, $equipment_id) {
	$sql = 'SELECT COUNT(*) as count FROM datasheet WHERE id_equipment = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'];
}

// ---------------------------------------------------------------------

/**
 * Ajoute ou met à jour une notice
 */
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
	$datasheet_id = $pdo->lastInsertId();

	$sub_path = $datasheet_id.'-'.random_string(8);
	$sql2 = 'UPDATE datasheet SET pathname = ? WHERE id = ?;';
	$stmt2 = $pdo->prepare($sql2);
	$stmt2->execute(array($sub_path.'/'.$datasheet_filename_kebab, $datasheet_id));

	$new_dir = $new_datasheet_path.'/'.$sub_path;
	if (!is_dir($new_dir))
		mkdir($new_dir, 0755);

	$iostat = move_uploaded_file($datasheet_tmp_file, $new_dir.'/'.$datasheet_filename_kebab);
	if (!$iostat) {
		error_log('Error: not move datasheet file '.$datasheet_filename_upload.' to '.$datasheet_filename_kebab);
		del_datasheet_by_id($pdo, $datasheet_id);
		return false;
	}

	return $datasheet_id;
}

// ---------------------------------------------------------------------

/**
 * Supprime la notice via son ID ainsi que son fichier et son dossier 
 * sur le disque s'ils éxistent
 */
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

/**
 * Recupère l'ID et le nom d'un équipement par son ID
 * 
 * @return false|array Le contenu d'un équipement directement
 */
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

/**
 * Récupère tout le contenu d'un équipement par son ID
 * 
 * @return false|array Le contenu d'un équipement directement
 */
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

/**
 * Recupere tout le contenu d'un équipement ainsi que son nom de categorie
 * rangé par nom d'équipement et nom d'équipe
 * 
 * @return array
 */
function get_equipment_listall($pdo) {
	// $sql = 'SELECT * FROM Listing ORDER BY categorie, nom;';
	$sql = 'SELECT DISTINCT e.*, c.nom AS category_name FROM Listing AS e INNER JOIN categorie AS c ON e.categorie = c.id ORDER BY c.nom, e.nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

/**
 * Recupere tout le contenu des équipements ainsi que leurs nom d'équipe via 
 * l'ID d'équipe, rangé par nom d'équipement et nom d'équipe
 * 
 * @return array
 */
function get_equipment_listall_by_team($pdo, $team_id) {
	//$sql = 'SELECT * FROM Listing WHERE equipe = ? ORDER BY categorie, nom;';
	$sql = 'SELECT DISTINCT e.*, c.nom AS category_name FROM Listing AS e INNER JOIN categorie AS c ON e.categorie = c.id WHERE e.equipe = ? ORDER BY c.nom, e.nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($team_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

/**
 * Recupere le nombre d'équipement empruntable avec la même équipe via 
 * l'ID de l'équipe
 * 
 * @return int
 */
function get_equipment_count_loanable_by_team($pdo, $team_id) {
	$sql = 'SELECT COUNT(*) as count FROM Listing AS e WHERE e.loanable = 1 and e.equipe = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($team_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'];
}

// ---------------------------------------------------------------------

/**
 * Recupere tout le contenu des equipements ayant la même 
 * categorie, rangé par nom
 * 
 * @return array
 */
function get_equipment_listall_by_category($pdo, $category_id) {
	$sql = 'SELECT * FROM Listing WHERE categorie = ? ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($category_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

/**
 * Recupere l'ID et le nom de tout les equipements rangé par
 * catégorie et nom
 * 
 * @return array
 */
function get_equipment_listshort($pdo) {
	$sql = 'SELECT id, nom FROM Listing ORDER BY categorie, nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

/**
 * Récupere l'ID d'un équipement depuis la table pret, 
 * de par l'ID du pret
 * 
 * @return ID de l'equipement
 */
function get_equipment_by_loan_id($pdo, $loan_id) {
	$sql = 'SELECT nom FROM pret WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($loan_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]["nom"];
}

// ---------------------------------------------------------------------

/**
 * Créer un équipement
 * 
 * @return array|string Renvoie une chaine si echec sinon 
 * un tableau avec l'ID et un msg d'erreur eventuellement
 */
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

/**
 * Met à jour un équipement par son ID
 *
 * @return string Renvoie une chaine vide
 * si réussite sinon une chaine d'erreurs
 */
function set_equipment_update($pdo, $equipment_id, $categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable) {
	$sql = 'UPDATE Listing SET categorie = ?, nom = ?, modele = ?, gamme = ?, equipe = ?, fournisseur = ?, achat = ?, responsable = ?, reparation = ?, accessoires = ?, inventaire = ?, notice = ?, barcode = ?, loanable = ? WHERE id = ?;)';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($categorie, $nom, $modele, $feature, $equipe, $fourn, $achat, $tech, $reparation, $accessoires, $inventaire, $notice, $barcode, $loanable, $equipment_id));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return $err_msg;
}

// ---------------------------------------------------------------------

/**
 * Supprime un seul équipement par son ID
 */
function del_equipment_by_id($pdo, $id) {
	$sql = 'DELETE LOW_PRIORITY FROM Listing WHERE id = ? LIMIT 1;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($id));
	return $iostat;
}

// ---------------------------------------------------------------------
// Loan
// ---------------------------------------------------------------------

/**
 * Récupère tout le contenu d'un pret par son ID
 * 
 * @return array|false S'il n'est pas "false", le 
 * retour ne sera qu'un seul objet
 */
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

/**
 * Récupere tout les prets liés à un équipement qui ne sont pas retourné
 * 
 * @deprecated Remplacé par la fonction get_loans_all_not_return_by_equipment()
 */
function get_loans_all_by_equipment($pdo, $equipment_id) {
	$sql = 'SELECT * FROM pret WHERE nom = ? AND NOT status = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id, STATUS_LOAN_RETURNED));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

/**
* Récupere tout les prets liés à un équipement qui ne sont pas retourné,
* rangé dans un certaine ordre de priorité.
*/
function get_loans_all_not_return_by_equipment($pdo, $equipment_id) {
	$sql = 'SELECT * FROM pret WHERE nom = ? AND status != ? ORDER BY status DESC, emprunt ASC, retour ASC;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id, STATUS_LOAN_RETURNED));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

/**
 * Recupère l'ID et le status d'un pret via l'ID d'équipement
 * 
 * @return false|array Le contenu du pret directement
 * @deprecated
 */
function get_loan_short_by_id_equipment($pdo, $equipment_id) {
	// recupere l'appareil via l'id qui est mis dans un champs texte (nom) !
	$sql = 'SELECT id, status FROM pret WHERE nom = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

/**
 * Recupère tout le contenu d'un pret via l'ID d'équipement
 * 
 * @return false|array Le contenu du pret directement
 */
function get_loan_all_by_id_equipment($pdo, $equipment_id) {
	// recupere l'appareil via l'id qui est mis dans un champs texte (nom) !
	$sql = 'SELECT * FROM pret WHERE nom = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

/**
 * @todo effectuer la documentation de la jointure
 */
function get_loan_listall($pdo) {
	//$sql = 'SELECT * FROM pret;';
	$sql = 'SELECT DISTINCT l.*, e.nom AS equipment_name FROM pret AS l INNER JOIN Listing AS e ON l.nom = e.id WHERE status = ? ORDER BY l.retour DESC, l.emprunt DESC, e.nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(STATUS_LOAN_BORROWED));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}
// ---------------------------------------------------------------------

/**
 * @todo effectuer la documentation de la jointure
 */
function get_loan_listall_by_team($pdo, $team_id) {
	$sql = 'SELECT DISTINCT l.*, e.nom AS equipment_name FROM pret AS l INNER JOIN Listing AS e ON l.nom = e.id WHERE l.equipe = ? AND status = ? ORDER BY l.retour DESC, l.emprunt DESC, e.nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($team_id, STATUS_LOAN_BORROWED));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

/**
 * Récupère le nombre de pret d'une équipe correspondante
 * 
 * @return int
 */
function get_loan_count_by_team($pdo, $team_id) {
	$sql = 'SELECT COUNT(*) as count FROM pret AS l INNER JOIN Listing AS e ON l.nom = e.id WHERE e.equipe = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($team_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'];
}

// ---------------------------------------------------------------------

/**
 * Récupère tout le contenu des prets d'un emprunteur en utilisant
 * la directive "RLIKE" pour détécter l'utilisateur dans le champ
 * commentaire des prets
 * 
 * @return array 
 */
function get_loan_find($pdo, $find) {
	$sql = 'SELECT * FROM pret WHERE commentaire RLIKE ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($find));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

/**
 * Récupère tout le contenu des prets qui possède le même ID d'équipement
 * et étant actuellement en emprunt
 * 
 * @return false|array
 */
function get_loans_all_by_equipment_and_borrowed($pdo, $equipment_id) {
	$sql = 'SELECT * FROM pret WHERE nom = ? AND status = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id, STATUS_LOAN_BORROWED));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

/**
 * Récupère tout le contenu des prets étant dans l'intervalle d'emprunt
 * des dates d'un équipement spécifier. Renverra "false" s'il n'y en a pas
 * 
 * @return false|array
 */
function get_loans_interval_by_id($pdo, $equipment_id, $from, $to) {
	$sql = 'SELECT * FROM pret WHERE ((`emprunt` <= ? AND `retour` >= ?) AND `nom` = ?) OR ((`emprunt` <= ? AND `retour` >= ?) AND `nom` = ?) OR ((`emprunt` >= ? AND `retour` <= ?) AND `nom` = ?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($from, $from, $equipment_id, $to, $to, $equipment_id, $from, $to, $equipment_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

/**
 * Récupère tout le contenu des prets étant dans l'intervalle d'emprunt
 * des dates d'un équipement spécifier excepter un emprunt en particulier.
 * Renverra "false" s'il n'y en a pas
 * 
 * @return false|array
 */
function get_loans_interval_by_id_except_loan($pdo, $equipment_id, $from, $to, $except_id) {
	$sql = 'SELECT * FROM pret WHERE (((`emprunt` <= ? AND `retour` >= ?) AND `nom` = ?) OR ((`emprunt` <= ? AND `retour` >= ?) AND `nom` = ?) OR ((`emprunt` >= ? AND `retour` <= ?) AND `nom` = ?)) AND NOT id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($from, $from, $equipment_id, $to, $to, $equipment_id, $from, $to, $equipment_id, $except_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

/**
 * Récupère le status du pret via son ID
 * 
 * @return string 
 */
function get_loan_status_by_id($pdo, $loan_id) {
	$sql = 'SELECT status FROM pret WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($loan_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]["status"];
}

// ---------------------------------------------------------------------

/**
 * Récupère tout le contenu du dernier prêt retourné
 * 
 * @todo Faire en sorte de retourner qu'un seul objet directement
 * @return false|array
 */
function get_loan_all_last_returned($pdo, $equipment_id) {
	$sql = 'SELECT * FROM pret WHERE nom = ? AND status = ? ORDER BY retour DESC LIMIT 1;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id, STATUS_LOAN_RETURNED));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch;
	return false;
}

// ---------------------------------------------------------------------

/**
 * Ajoute un nouveau pret défini comme étant actuellement en emprunt
 * 
 * @deprecated
 * @return int
 */
function set_loan_borrowed_new($pdo, $equipment_id, $team_id, $date_begin, $date_end, $comment) {
	$sql = 'INSERT INTO pret (nom, equipe, emprunt, retour, commentaire, status) VALUES (?, ?, ?, ?, ?, ?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id, $team_id, $date_begin, $date_end, $comment, STATUS_LOAN_BORROWED));
	return $pdo->lastInsertId();
}

// ---------------------------------------------------------------------

/**
 * Ajoute un nouveau pret défini comme étant en réservation
 * 
 * @deprecated
 * @return int
 */
function set_loan_reserved_new($pdo, $equipment_id, $team_id, $date_begin, $date_end, $comment) {
	$sql = 'INSERT INTO pret (nom, equipe, emprunt, retour, commentaire, status) VALUES (?, ?, ?, ?, ?, ?);';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id, $team_id, $date_begin, $date_end, $comment, STATUS_LOAN_RESERVED));
	return $pdo->lastInsertId();
}

// ---------------------------------------------------------------------

/**
 * Met à jour un emprunt (en spécifiant son ID) comme étant emprunter et bloque également
 * sa date d'emprunt au jour même de son appel
 * 
 * @deprecated
 */
function set_loan_update_to_borrowed($pdo, $loan_id) {
	$sql = 'UPDATE pret SET status = ?, emprunt = CURRENT_DATE WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(STATUS_LOAN_BORROWED, $loan_id));
}

// ---------------------------------------------------------------------

/**
 * Met à jour le pret en spécifiant son ID sans modifier par défaut le status
 */
function set_loan_update($pdo, $loan_id, $equipment_id, $team_id, $date_begin, $date_end, $comment) {
	$sql = 'UPDATE pret SET nom = ?, equipe = ?, emprunt = ?, retour = ?, commentaire = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($equipment_id, $team_id, $date_begin, $date_end, $comment, $loan_id));
}

// ---------------------------------------------------------------------

/**
 * Supprime un seul pret via son ID
 */
function del_loan_by_id($pdo, $loan_id) {
	$sql = 'DELETE LOW_PRIORITY FROM pret WHERE id = ? LIMIT 1;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($loan_id));
	return $iostat;
}

// ---------------------------------------------------------------------

/**
 * Met à jour un pret (en spécifiant son ID) comme étant retourné et
 * bloque sa date de retour au jour de son appel
 */
function set_loan_to_returned($pdo, $loan_id) {
	$sql = 'UPDATE LOW_PRIORITY pret SET status = ?, retour = CURRENT_DATE WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array(STATUS_LOAN_RETURNED, $loan_id));
	return $iostat;
}


// ---------------------------------------------------------------------
// Supplier
// ---------------------------------------------------------------------

/**
 * Récupère l'ID et le nom d'un fournisseur via son ID
 * 
 * @return false|array Retourne directement le fournisseur
 */
function get_supplier_short_by_id($pdo, $id) {
	$sql = 'SELECT id, nom FROM fournisseurs WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

/**
 * Récupère tout le contenu d'un seul fournisseur via son ID
 * 
 * @return false|array Retourne directement le fournisseur
 */
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

/**
 * Récupère l'ID et le nom de tout les fournisseurs trier par nom croissant
 * 
 * @return array
 */
function get_supplier_listshort($pdo) {
	$sql = 'SELECT id, nom FROM fournisseurs ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$supplier_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $supplier_fetch;
}

// ---------------------------------------------------------------------

/**
 * Récupère tout le contenu de tout les fournisseurs trier par nom croissant
 * 
 * @return array
 */
function get_supplier_listall($pdo) {
	$sql = 'SELECT * FROM fournisseurs ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$supplier_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $supplier_fetch;
}

// ---------------------------------------------------------------------

/**
 * Retourne la liste de tout les fournisseurs si la valeurs $find est "true"
 * ou une chaîne vide. Sinon elle récuperera tout le contenu des fournisseurs
 * où le chaine demandé a le plus de similitude avec le nom ou la description
 * du fournisseur
 * 
 * @return array
 */
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

/**
 * Ajoute un nouveau fournisseur
 * 
 * @return array Avec chaine d'erreur au deuxième index
 */
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

/**
 * Met à jour un fournisseur via son ID
 * 
 * @return array Avec chaine d'erreur au deuxième index
 */
function set_supplier_update($pdo, $supplier_id, $name, $address, $tel, $fax, $email, $www, $contact, $description) {
	$sql = 'UPDATE LOW_PRIORITY fournisseurs  SET nom = ?, adresse = ?, tel = ?, fax = ?, mail = ?, www = ?, contact = ?, descr = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($name, $address, $tel, $fax, $email, $www, $contact, $description, $supplier_id));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return $err_msg;
}

// ---------------------------------------------------------------------

/**
 * Supprime un fournisseur via son ID
 */
function del_supplier_by_id($pdo, $id) {
	$sql = 'DELETE LOW_PRIORITY FROM fournisseurs WHERE id = ? LIMIT 1;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($id));
	return $iostat;
}

// ---------------------------------------------------------------------
// Team
// ---------------------------------------------------------------------

/**
 * Récupère l'ID et le nom d'une seul équipe via son ID
 * 
 * @return array Retourne directement l'équipe
 */
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

/**
 * Récupère tout le contenu d'une seul équipe via son ID
 * 
 * @return array Retourne directement l'équipe
 */
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

/**
 * Récupère l'ID et le nom de toutes les équipes par ordre de nom croissant
 * 
 * @return array
 */
function get_team_listshort($pdo) {
	$sql = 'SELECT id, nom FROM equipe ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$team_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $team_fetch;
}

// ---------------------------------------------------------------------

/**
 * Récupère tout le contenu de toutes les équipe par ordre de nom croissant
 * 
 * @return array
 */
function get_team_listall($pdo) {
	$sql = 'SELECT * FROM equipe ORDER BY nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$team_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $team_fetch;
}

// ---------------------------------------------------------------------

/**
 * Récupère l'ID et le nom des équipe par ordre croissant de nom,
 * possèdant au moins un équipement
 * 
 * @return array
 */
function get_team_with_appareil($pdo) {
	$sql = 'SELECT DISTINCT equipe.id, equipe.nom FROM equipe INNER JOIN Listing ON equipe.id = Listing.equipe ORDER BY equipe.nom;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$team_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $team_fetch;
}

// ---------------------------------------------------------------------

/**
 * Récupère le nombre d'équipe directement
 * 
 * @return int
 */
function get_team_count($pdo) {
	$sql = 'SELECT COUNT(*) as count FROM equipe;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'];
}

// ---------------------------------------------------------------------

/**
 * Ajoute une nouvelle équipe
 * 
 * @return array Avec potentiellement une chaine d'erreur
 */
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

/**
 * Met à jour une équipe via son ID
 * 
 * @return array Avec potentiellement une chaine d'erreur
 */
function set_team_update($pdo, $team_id, $name, $description, $account, $manager) {
	$sql = 'UPDATE LOW_PRIORITY equipe SET nom = ?, descr = ?, compte = ?, chef = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($name, $description, $account, $manager, $team_id));
	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return $err_msg;
}

// ---------------------------------------------------------------------

/**
 * Supprime une équipe via son ID
 */
function del_team_by_id($pdo, $id) {
	$sql = 'DELETE LOW_PRIORITY FROM equipe WHERE id = ? LIMIT 1';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($id));
	return $iostat;
}

// ---------------------------------------------------------------------
// User
// ---------------------------------------------------------------------

/**
 * Récupère l'ID, le nom et le prénom d'un utilisateur via son ID
 * 
 * @return false|array Retourne un seul utilisateur
 */
function get_user_short_by_id($pdo, $id) {
	$sql = 'SELECT id, nom, prenom FROM users WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($result_fetch) > 0)
		return $result_fetch[0];
	return false;
}

// ---------------------------------------------------------------------

/**
 * Récupère tout le contenu d'un utilisateur via son ID
 * 
 * @return false|array Retourne un seul utilisateur
 */
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

/**
 * Récupère tout le contenu d'un utilisateur via son nom de login
 * 
 * @return false|array Retourne un seul utilisateur
 */
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

/**
 * Récupère tout le contenu des utilisateurs suivant le privilège défini
 * rangé par ordre de nom et prénom
 * 
 * @return array
 */
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

/**
 * Récupère l'ID, le nom et le prénom des utilisateurs étant validé et
 * étant supérieur ou égale au privilege minimum indiqué. Ou bien via
 * l'ID s'il n'est pas égale à 0. La requète est rangé par ordre de nom
 * et prénom utilisateur. Attention toutefois au fait que cette fonction
 * ne vérifie pas les privilèges, mais néanmoins, elle retournera des 
 * éléments de la table qui ne sont pas sensible.
 * 
 * @todo Voir pour renommer
 * @return array
 */
function get_user_listshort_with_right($pdo, $level_min=1, $bonus_id=0) {
	$sql = 'SELECT id, nom, prenom FROM users WHERE (valid = 1 and level >= ?) or id = ? ORDER BY nom, prenom;';
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($level_min, $bonus_id));
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch;
}

// ---------------------------------------------------------------------

/**
 * Récupère le nombre d'utilisateurs directement
 * 
 * @return int
 */
function get_user_count($pdo) {
	$sql = 'SELECT COUNT(*) as count FROM users;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$result_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result_fetch[0]['count'];
}

// ---------------------------------------------------------------------

/**
 * Ajoute un nouvel utilisateur
 * 
 * @return array Avec potentiellement une chaine d'erreur
 */
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

/**
 * Met à jour le mot de passe d'un utilisateur via son ID
 */
function set_user_password_by_id($pdo, $user_id, $user_password) {
	$sql = 'UPDATE users SET password = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($user_password, $user_id));
	return $iostat;
}

// ---------------------------------------------------------------------

/**
 * Aciennement : set_user_valid_by_id()
 * 
 * @todo Voir qu'est-ce que la colonne "valid"
 */
function set_user_valid_by_id($pdo, $user_id, $user_status) {
	$sql = 'UPDATE users SET valid = ? WHERE id = ?;';
	$stmt = $pdo->prepare($sql);
	$iostat = $stmt->execute(array($user_status, $user_id));
	return $iostat;
}

// ---------------------------------------------------------------------

/**
 * Met à jour un utilisateur via son ID
 * 
 * @return array Avec potentiellement une chaine d'erreur
 */
/* function set_user_update($pdo, $user_id, $familyname, $firstname, $email, $level, $tel, $team_id, $theme, $logged_level, $loggin='') {
	if (isset($loggin) && $loggin != '' && $logged_level > 3) {
		$sql = 'UPDATE LOW_PRIORITY users SET loggin = ?, nom = ?, prenom = ?, email = ?, level = ?, tel = ?, equipe = ?, theme = ? WHERE id = ?;';
		$stmt = $pdo->prepare($sql);
		$iostat = $stmt->execute(array($loggin, $familyname, $firstname, $email, $level, $tel, $team_id, $theme, $user_id));
	} else {
		$sql = 'UPDATE LOW_PRIORITY users SET nom = ?, prenom = ?, email = ?, level = ?, tel = ?, equipe = ?, theme = ? WHERE id = ?;';
		$stmt = $pdo->prepare($sql);
		$iostat = $stmt->execute(array($familyname, $firstname, $email, $level, $tel, $team_id, $theme, $user_id));
	}

	$err_msg = '';
	if (!$iostat)
		$err_msg = $stmt->errorInfo()[2];
	return $err_msg;
} */

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

/**
 * Récupère le numéro de version via le nom de l'application/fonctionnalitée
 * 
 * @return false|array
 */
function get_version_by_name($pdo, $name) {
	$sql = 'SELECT version FROM version WHERE soft = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($name));
	$version_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($version_fetch) > 0)
		return $version_fetch[0];
	return false;
}
// ---------------------------------------------------------------------

/**
 * Ajoute une version d'application/fonctionnalité si celle si existe
 * sinon met seulement à jour la version
 */
function set_version_by_name($pdo, $name, $version) {
	$sql = 'INSERT INTO version (soft, version) VALUES (?, ?);';
	if (get_version_by_name($pdo, $name))
		$sql = 'UPDATE version SET version = ? WHERE soft = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($name, $version));
}

?>
