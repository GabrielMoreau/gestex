<?php if (!$web_page) exit() ?>

<?php
// $loan_id
// $equipment_name
?>

<?php en_tete('Retour d\'un appareil (fin du pr&ecirc;t)'); ?>

<center class="alert">
<form action="loan-del.php" method="POST">
	<input type="hidden" name="id" value="<?=$loan_id?>" >
	Concernant le pr&ecirc;t <?=$loan_id?> (<?=$equipment_name?>), voulez-vous :
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
