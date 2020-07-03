<?php
//prets.php
// Authenticate

//include("session_auth.php");

include("db_functions.php");
//if (!auth(1))
	//Header("Location: instru.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

require("html_functions.php");

en_tete("Liste des prets:");

//recuper la methode de tri
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";
?>

<i>Consulter la liste des &eacute;quipements communs disponibles au service instrumentation et choisir :
'Demande de pr&ecirc;t' en face de l'appareil souhait&eacute;</i><br />

<br />
<table cellpadding="2" cellspacing="2" border="1"
	style="width: 70%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr>
			<td style="vertical-align: top; text-align: center;">
				<a href="<?php GESTEX_URL_ENTITY ?>">Retour &agrave;<br />l'intranet</a>
				<br />
			</td>

<?php
$tri = $_GET[tri];
if (empty($tri))
	$tri ="id";
if ($connex = connect_db()) {
	// recupere les refs du user
	$querry = "SELECT * FROM equipe order by '$tri'" ;
	list($qh,$num) = query_db($querry);
	$last_id=0;
	$data = result_db($qh);

	while ($data = result_db($qh)){
		if ($data['id'] == 15) {
			echo "<td style=\"vertical-align: top;\">";
			echo "	<a href =\"instru1.php?equipe=".$data['id']."\">Liste des appareils en pr&ecirc;t</a>";
			echo "</td>";
		}
	}
}
?>

			<td style="vertical-align: top; text-align: center;">
				<a href="reserva.php">Liste<br />des r&eacute;servations</a>
				<br />
			</td>
			<td style="vertical-align: top; text-align: center;">
				<a href="login.php?variable=pret">Acc&egrave;s<br />restreint</a>
				<br />
			</td>
		</tr>
	</tbody>
</table>

<?php pied_page() ?>

