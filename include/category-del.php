<?php if (!$web_page) exit() ?>

<?php
// $category_id
// $category_name
?>

<?php en_tete('Suppression de la catégorie <i>'.$category_name.'</i>') ?>

<center class="box-alert">
<form action="category-del.php" method="POST">
	<input type="hidden" name="category_id" value="<?=$category_id?>">
	Voulez-vous supprimer la catégorie <i><?=$category_name?></i> (#<?=$category_id?>) ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="category-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
