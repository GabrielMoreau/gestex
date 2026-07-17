<?php if (!$web_page) exit() ?>

<?php
// $equipment_id
// $equipment_name
?>

<?php en_tete('Suppression de l’appareil et des notices associées <i>'.$equipment_name.'</i>'); ?>

<center class="box-alert">
<form action="equipment-del.php" method="POST">
	<input type="hidden" name="equipment_id" value="<?=$equipment_id?>">
	Voulez-vous supprimer l’appareil <i><?=$equipment_name?></i> (#<?=$equipment_id?>) ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="equipment-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
