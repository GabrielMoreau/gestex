<?php
//essai1.php
// Authenticate

include("db_functions.php");

// if (!auth(1))
	// Header("Location: instru.php");

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
	style="width: 70%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr>
			<td style="vertical-align: top; text-align: center;">
				<a href="<?php GESTEX_URL_SERVICE ?>">Retour &agrave;<br /> la page du service</a>
				<br />
			</td>
			<td style="vertical-align: top; text-align: center;">
				<a href="list_fourn1.php">Liste<br />des fournisseurs</a>
				<br />
			</td>
			<td style="vertical-align: top; text-align: center;">
				<a href="login.php?variable=instru">Acc&egrave;s<br />restreint</a>
				<br />
			</td>
		</tr>
	</tbody>
</table>

<br />
Liste des appareils : <br />
<i>Cliquer sur une cat&eacute;gorie pour voir la liste...</i>
<br />
<br />
<table cellpadding="20" cellspacing="4" border="1"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
			<th style="vertical-align: top; text-align: center;">
				<a href ="instru1.php?list=">Liste globale</a><br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				<a href ="instru1.php?equipe=15">Appareils en pr&ecirc;t au service instru</a><br />
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
	$sql = 'SELECT id, nom FROM categorie ORDER BY ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($categorie as $data) {
		echo '<a href="instru1.php?categorie='.$data['id'].'">'.$data['nom'].'</a>';
		echo '<br />'.PHP_EOL;
	}
}
?>

			</td>
		</tr>
	</tbody>
</table>

<br />
</div>
<?php pied_page() ?>

