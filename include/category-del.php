<?php if (!$web_page) exit() ?>

<?php en_tete('Suppression de la cat&eacute;gorie <i>'.$category_name.'</i>') ?>

<center class="alert">
<form action="category-del.php" method="POST">
	<input type="hidden" name="id" value="<?=$id_category?>">
	Voulez-vous supprimer la cat&eacute;gorie <i><?=$category_name?></i> ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="category-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
