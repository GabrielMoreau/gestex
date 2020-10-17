<?php
// datasheet-del.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('equipment-list.php');
level_or_alert(3, 'Suppression d\'une notice');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$datasheet_id = param_post_or_get('id', 0);
$valid        = param_post('ok', 'no');

$equipment_id = 0;
if ($datasheet_id > 0) {
	$pdo = connect_db_or_alert();

	$datasheet_selected = get_datasheet_all_by_id($pdo, $datasheet_id);
	$equipment_id       = $datasheet_selected['id_equipment'];
}

if ($datasheet_id == 0 || $equipment_id == 0 || $valid == 'cancel') {
	if ($equipment_id > 0)
		redirect('equipment-view.php?id='.$equipment_id);
	redirect('equipment-list.php');
}

if ($valid == 'yes') {
	$flag = del_datasheet($pdo, $datasheet_id);
	if ($flag) // ca a marche
		redirect('equipment-view.php?id='.$equipment_id);
	$message_alert = 'Erreur dans la suppression de la notice : '.$datasheet_id;
	include_once('include/alert-data.php');
	exit();
	}

$equipment_selected = get_equipment_by_id($pdo, $datasheet_selected['id_equipment']);

en_tete('Suppression d\'une notice');
?>

<center class="alert">
<form action="datasheet-del.php" method="POST">
	<input type="hidden" name="id" value="<?php echo $datasheet_id ?>" >
	Voulez-vous supprimer la notice <b><?php echo $datasheet_selected['pathname'] ?></b> (<?php echo $equipment_selected['nom'] ?>) ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="equipment-view.php?id=<?php echo $equipment_id ?>" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
