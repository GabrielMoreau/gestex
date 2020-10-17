<?php if (!$web_page) exit() ?>

<?php
// $user_id
// $user_status
// $user_fullname
?>

<?php en_tete('Changer l\'&eacute;tat d\'un utilisateur'); ?>

<center class="alert">
<form action="user-del.php" method="POST">
	<input type="hidden" name="id" value="<?=$user_id?>">
	<input type="hidden" name="status" value="<?=$user_status?>">
	Voulez-vous changer l'&eacute;tat de l'utilisateur <?=$user_fullname?> (#<?=$user_id?>) ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="user-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page(); ?>
