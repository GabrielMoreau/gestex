<?php
// loan-del.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('loan-list.php');
level_or_alert(3, 'Suppression d\'un pr&ecirc;t');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$loan_id = param_post_or_get('id', 0);
$valid   = param_post('ok', 'no');

if ($loan_id == 0 || $valid == 'cancel')
	redirect('loan-list.php');

if ($valid == 'edit')
	redirect('loan-add.php?id='.$loan_id);

$pdo = connect_db_or_alert();

if ($valid == 'yes') {
	$flag = del_loan($pdo, $loan_id);
	if ($flag) // ca a marche
		redirect('loan-list.php');
	$message_alert = 'Erreur dans la suppression du pr&ecirc;t : '.$loan_id;
	include_once('include/alert-data.php');
	exit;
	}

$loan_selected      = get_loan_all_by_id($pdo, $loan_id);
$equipment_selected = get_equipment_by_id($pdo, $loan_selected['nom']);

en_tete('Retour d\'un appareil (fin du pr&ecirc;t)');
?>

<center class="alert">
<form action="loan-del.php" method="POST">
	<input type="hidden" name="id" value="<?php echo $loan_id ?>" >
	Concernant le pr&ecirc;t <?php echo $loan_id ?> (<?php echo $equipment_selected['nom'] ?>), voulez-vous :
	<ul>
		<li>Modifier / &Eacute;diter le pr&ecirc;t ? <button type="submit" name="ok" value="edit"><?php echo ICON_EDIT ?></button></li>
		<li>Supprimer le pr&ecirc;t (retour de l'appareil) ?
			<button class="red" type="submit" name="ok" value="yes">Oui</button>
			<button class="green" type="submit" formaction="loan-list.php" value="no">Non</button>
		</li>
	</ul>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
