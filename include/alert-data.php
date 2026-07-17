<?php if (!$web_page) exit() ?>

<?php
// $message_alert
?>

<?php en_tete('Erreur avec la base de données') ?>

<div class="box-alert">
	<center>
		Une erreur est survenue lors de l’accès à la base de données.
		<br>
		<b><?=$message_alert?></b>
		<br>
		Veuillez voir avec votre administrateur pour corriger éventuellement les problèmes liés à cette base de données.
		En cas d'erreurs systématiques, veuillez remonter un bogue aux développeurs amont.
	</center>
</div>

<?php pied_page() ?>
