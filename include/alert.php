<?php if (!$web_page) exit() ?>

<?php en_tete('Alerte') ?>

<div class="alert">
	<center>
		Vous ne disposez pas des autorisations suffisantes pour consulter la page
		<br>
		<b><?=$msg_alert?></b>
		<br>
		Veuillez voir avec votre administrateur pour vous faire changer éventuellement les droits.
	</center>
</div>

<?php pied_page() ?>
