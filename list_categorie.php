<?php
//list_categorie.php
// Authenticate

include("session_auth.php");
session_start();
// if (!auth(1))
	// Header("Location: list_appareil.php");
if (empty($_SESSION['logged_in_user'])) {
	$user_level = 0; // no auth
} else {
	$user_id        = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);
	$user_level     = $_SESSION['level'];
}
require("html_functions.php");

en_tete('Liste des appareils');

//recupere la methode de tri

if (empty($_GET['tri']))
	$tri = 'nom';
else
	$tri = $_GET['tri'];
?>

<br />
<table cellpadding="2" cellspacing="2" border="1"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr>
			<td style="vertical-align: top; text-align: center;">
				<a href="<?php GESTEX_URL_SERVICE ?>">Retour &agrave;<br /> la page du service</a>
				<br />
			</td>
			<?php 
			if ($user_level >= 2) { ?>
				<td style="vertical-align: top; text-align: center;">
					<a href="add_appareil.php">Ajout<br />d'un appareil</a>
					<br />
				</td>
				<td style="vertical-align: top; text-align: center;">
					<a href="list_fourn.php">Liste<br />des fournisseurs</a>
					<br />
				</td>
				<td style="vertical-align: top; text-align: center;">
					<a href="add_fourn.php">Ajout<br />d'un fournisseur</a>
					<br />
				</td>
			<?php }	?>
			<?php if ($user_level >= 3) { ?>
				<td style="vertical-align: top; text-align: center;">
					<a href="add_categorie.php">Ajout<br />d'une cat&eacute;gorie</a>
					<br />
				</td>
			<?php } ?>
		</tr>
	</tbody>
</table>

<br />
Liste des appareils : <br />
<i>Affichage de la liste globale ou bien des appareils en pr&ecirc;t au service instrumentation...</i><br />
<br />
<table cellpadding="20" cellspacing="4" border="1"
	style="width: 70%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
			<th style="vertical-align: top; text-align: center;">
				<a href ="list_appareil.php?categorie=0 ">Liste globale</a><br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				<a href ="list_appareil.php?equipe=15">Appareils au service instrumentation</a><br />
			</th>
		</tr>
	</tbody>
</table>

<br />
Liste des appareils par cat&eacute;gorie : <br />
<i>Cliquer sur une cat&eacute;gorie pour voir la liste...</i><br />
<br />
<table cellpadding="10" cellspacing="2" border="1"
	style="width: 70%; text-align: center; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
			<td style="vertical-align: top;">

<?php
if ($pdo = connect_db()) {
	// recupere les refs du user
	$sql = 'SELECT id, nom FROM categorie ORDER BY ? ASC;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($categorie as $data) {
		echo '<a href="list_appareil.php?categorie='.$data['id'].'">'.$data['nom'].'</a>';
		echo '<br />'.PHP_EOL;
	}
}
?>

			</td>
		</tr>
	</tbody>
</table>

<?php if (($user_level >= 3) || ($user_id == 33) || ($user_id == 2) || ($user_id == 105)) { ?>
<br /><br /><br />
<td style="vertical-align: top; text-align: center;">
	<a href="list_demandes.php">Demandes en cours</a>
<td style="vertical-align: top; text-align: center;">
	<a href="prets.html">Pr&ecirc;ts en cours</a>
<?php } ?>

<br />
</div>
<?php pied_page() ?>

