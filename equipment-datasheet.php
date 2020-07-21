<?php
// equipment-datasheet.php
$web_page = true;

// Authenticate
require_once('auth-functions.php');
require_once('html-functions.php');

session_start();
$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

//recupere le numero du nom
if (empty($_GET['id']))
	Header('Location: equipment-list.php');
else
	$id_app = $_GET['id'];

if ($pdo = connect_db()) {

	$sql = 'SELECT id, nom FROM Listing WHERE id = ?;' ;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_app));
	$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$titre = 'Documents de l\'appareil : '.$listing[0]['nom'].' ('.$id_app.')';
	en_tete($titre);

	$dossier_proj = './data/datasheet';
	?>

<div>
<table>
	<tbody>

		<?php
		$datasheet_fetch = get_datasheet_listall_by_equipment($pdo, $id_app);
		foreach ($datasheet_fetch as $datasheet) {
			if (!is_file($dossier_proj.'/'.$datasheet['pathname']))
				continue;
			?>
		<tr>
			<td>
				<a href="<?php echo $dossier_proj.'/'.$datasheet['pathname'] ?>" target="_blank">
					<?php echo $datasheet['description'] ?>
				</a>
			</td>
		</tr>
		<?php } ?>

	<tbody>
</table>
</div>

<?php
} // end if connect
?>

<?php pied_page() ?>
