<?php if (!$web_page) exit() ?>

<?php
// $title
// $action
// $message_text
?>

<?php en_tete($title) ?>

<center class="box-warn">
<form action="<?=$action?>" method="POST">
	<?=$message_text?>
	<hr>
	<button type="submit" name="ok" value="next">Suite</button>
</form>
</center>

<?php pied_page() ?>
