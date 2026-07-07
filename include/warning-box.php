<?php if (!$web_page) exit() ?>

<?php
// $title
// $action
// $message_text
// $transmit_post
?>

<?php en_tete($title) ?>

<center class="box-warn">
<form action="<?=$action?>" method="POST">
	<?php if ($transmit_post): ?>
	<?php foreach ($_POST as $key => $value): ?>
	<input type="hidden" name="<?=$key?>" value="<?=$value?>">
	<?php endforeach; ?>
	<?php endif; ?>
	<?=$message_text?>
	<hr>
	<button type="submit" name="ok" value="next">Suite</button>
</form>
</center>

<?php pied_page() ?>
