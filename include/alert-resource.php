<?php if (!$web_page) exit() ?>

<?php
// $resource_name
// $resource_index
?>

<?php en_tete('Erreur dans votre demande') ?>

<div class="box-alert">
	<center>
		Une erreur est survenue lors du traitement de votre demande.
		L’accès à la ressource est impossible, car celle-ci n’existe pas <i>a priori</i>.
		<ul>
			<li>ressource : <?=$resource_name?> = <?=$resource_index?></li>
		</ul>
		<br>
		Si vous avez été sur ce lien erroné via l'application (en suivant les liens proposés),
		veuillez voir avec votre administrateur pour corriger éventuellement les problèmes.
		En cas d'erreurs systématiques, veuillez remonter un bogue aux développeurs amont.
	</center>
</div>

<?php pied_page() ?>
