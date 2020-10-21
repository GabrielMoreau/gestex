<?php if (!$web_page) exit() ?>

<?php
// $supplier_id
// $supplier_name
?>

<?php en_tete('Suppression du fournisseur <i>'.$supplier_name.'</i>'); ?>

<center class="box-alert">
<form action="supplier-del.php" method="POST">
	<input type="hidden" name="id" value="<?=$supplier_id?>">
	Voulez-vous supprimer le fournisseur <i><?=$supplier_name?></i> (#<?=$supplier_id?>) ?
	<button class="red" type="submit" name="ok" value="yes">Oui</button>
	<button class="green" type="submit" formaction="supplier-list.php" value="no">Non</button>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
