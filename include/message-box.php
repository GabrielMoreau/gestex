<?php if (!$web_page) exit() ?>

<?php
// $title
// $action
// $highlight
// $message_text
?>

<?php en_tete($title) ?>

<center class="box-info">
<?php if (empty($highlight)): ?>
<form action="<?=$action?>" method="POST">
<?php else: ?>
<form action="<?=$action?>#item<?=$highlight?>" method="POST">
	<input type="hidden" name="highlight" value="<?=$highlight?>">
<?php endif; ?>
	<?=$message_text?>
	<hr>
	<button type="submit" name="ok" value="next">Suite</button>
</form>
</center>

<?php pied_page() ?>
