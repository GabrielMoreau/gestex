<?php if (!$web_page) exit() ?>

<?php
// $datasheet_id
// $datasheet_pathname
// $equipment_id
// $equipment_name
?>

<?php en_tete('Suppression d’une notice'); ?>

<center class="alert">
<form action="datasheet-del.php" method="POST">
	<input type="hidden" name="datasheet_id" value="<?=$datasheet_id?>" >
	Voulez-vous supprimer la notice <b><?=$datasheet_pathname?></b> (<?=$equipment_name?>) ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="equipment-view.php?equipment_id=<?=$equipment_id?>" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
