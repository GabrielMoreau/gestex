<?php if (!$web_page) exit() ?>

<?php
// $user_id
// $user_status
// $user_fullname
?>

$user_status_msg = 'déactivé';
if ($user_status === '0')
	$user_status_msg = 'activé';

<?php en_tete('Changer l’état de l’utilisateur '.$user_fullname.' ('.$user_status_msg.')'); ?>

<center class="alert">
<form action="user-del.php" method="POST">
	<input type="hidden" name="user_id" value="<?=$user_id?>">
	<input type="hidden" name="status" value="<?=$user_status?>">
	Voulez-vous changer l’état de l'utilisateur <?=$user_fullname?> (actuellement <?=$user_status_msg?>) ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="user-list.php" value="no">Non</button>
	<hr>
	Voulez-vous supprimer <b>définitivement</b> l'utilisateur <?=$user_fullname?> (#<?=$user_id?>) ?
	<button class="red" type="submit" name="ok" value="destroy">Supprimer</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page(); ?>
