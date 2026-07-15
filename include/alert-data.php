<?php if (!$web_page) exit() ?>

<?php
// $message_alert
?>

<?php en_tete('Erreur avec la base de données') ?>

<div class="box-alert">
	<center>
		Une erreur est survenue lors de l’accès aux données avec la base de donnée.
		<br>
		<b><?=$message_alert?></b>
		<br>
		Veuillez voir avec votre administrateur pour vous corriger éventuellement les problèmes sur votre base de données.
		En cas d'erreurs systématiques, veuillez remonter un bogue aux développeurs amont.
	</center>
</div>

<?php pied_page() ?>
