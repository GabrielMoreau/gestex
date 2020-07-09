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

<?php
if ($pdo = connect_db()) {
	// recupere les refs du user
	$sql = 'SELECT id, nom FROM categorie ORDER BY ? ASC;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$num_line = 0;
	foreach ($categorie as $data) {
		if (($num_line % 2 )==0)
			echo '<tr class="pair">'.PHP_EOL;
		else
			echo '<tr class="impair">'.PHP_EOL;
		$num_line++;
		echo '  <td style="vertical-align: top;">';
		echo '    <a href="list_appareil.php?categorie='.$data['id'].'">'.$data['nom'].'</a>';
		echo '  </td>'.PHP_EOL;
		if ($user_level >= 3) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="del_categorie.php?id=',$data['id'],'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;
		}
		echo '</tr>'.PHP_EOL;
	}
}
?>
	</tbody>
</table>

<br />
</div>
<?php pied_page() ?>

