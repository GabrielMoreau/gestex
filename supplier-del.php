<?php
// supplier-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('supplier-list.php');
level_or_alert(3, 'Suppression d\'un fournisseur');

en_tete('Suppression d\'un fournisseur');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$id_fourn = param_post_or_get('id');
$valid    = param_post_or_get('ok', 'no');

if (empty($id_fourn))
	redirect('supplier-list.php');

if ($valid != 'yes') {
	echo 'Sur de supprimer le fournisseur '.$id_fourn.' ?<br>';
	echo '<a href="'.$_SERVER['PHP_SELF'].'?id='.$id_fourn.'&ok=yes">OUI</a><br>';
	echo '<a href="'.$_SERVER['HTTP_REFERER'].'">NON</a><br>';
}
else {
	if ($pdo = connect_db()) {
		// on supprime le fournisseur
		$sql = 'DELETE LOW_PRIORITY FROM fournisseurs WHERE id = ? LIMIT 1';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_fourn));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}//end if connect
//on retourne a la page de la liste des fournisseur
redirect('supplier-list.php');
} //else end
?>

<?php pied_page() ?>
