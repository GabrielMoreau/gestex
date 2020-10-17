<?php if (!$web_page) exit() ?>

<?php
// $team_name
// $team_id
?>

<?php en_tete('Suppression d\'une &eacute;quipe <i>'.$team_name.'</i>'); ?>

<center class="box-alert">
<form action="team-del.php" method="POST">
	<input type="hidden" name="id" value="<?=$team_id?>">
	Voulez-vous supprimer l'&eacute;quipe <i><?=$team_name?></i> (#<?=$team_id?>) ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="team-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
