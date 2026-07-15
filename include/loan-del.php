<?php if (!$web_page) exit() ?>

<?php
// $loan_id
// $equipment_name
require_once('module/db-functions.php');
$str_loan_type = "le prêt";
$loan_status = STATUS_LOAN_BORROWED;
if (get_loan_all_by_id($pdo, $loan_id)["status"] == STATUS_LOAN_RESERVED) {
	$str_loan_type = "la réservation";
	$loan_status = STATUS_LOAN_RESERVED;
}
?>


<?php en_tete('Retour d’un appareil (fin du prêt)'); ?>

<center class="alert">
<form action="loan-del.php" method="POST">
	<input type="hidden" name="id" value="<?=$loan_id?>" >
	Concernant <?=$str_loan_type?> <?=$loan_id?> (<?=$equipment_name?>), voulez-vous :
	<ul>
		<li>Modifier / Éditer <?=$str_loan_type?> ? <button type="submit" name="ok" value="edit"><?php echo ICON_EDIT ?></button></li>
		<li>Supprimer <?=$str_loan_type?>
			<?php if ($loan_status == STATUS_LOAN_BORROWED): ?>
			(retour de l'appareil)
			<?php endif; ?>
			?
			<button class="red" type="submit" name="ok" value="yes">Oui</button>
			<button class="green" type="submit" formaction="loan-list.php" value="no">Non</button>
		</li>
	</ul>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
