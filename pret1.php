<?php
//prets.php
// Authenticate

include("session_auth.php");

//include("db_functions.php");
if (!auth(1))
	Header("Location: login.php");

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

require("html_functions.php");

en_tete("Liste des prets:");
//recuper la methode de tri
if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];

?>

<i> Consulter la liste des &eacute;quipement communs disponibles au service instrumentation et choisir : 'Demande de pr&ecirc;t' en face de l'appareil souhait&eacute;</i><br />
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
	// recupere les refs du user
	$pdo = connect_db();
	$sql = 'SELECT id from equipe ORDER by ?';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
// 	$querry = "SELECT * FROM equipe order by '$tri'" ;
// 	list($qh,$num) = query_db($querry);
// 	$last_id=0;
// $data = result_db($qh);

// while ($data = result_db($qh)){
foreach($equipe as $data){
	if ($data['id'] == 15) {     
		echo"<td style=\"vertical-align: top;\">";
		echo "	<a href =\"instru.php?equipe=".$data['id']." pret=".$data['id']."\">Liste des appareils en pr&ecirc;t</a>";
		echo "</td>";
		echo"<td style=\"vertical-align: top;\">";
		echo "	<a href =\"reserva.php?user=".$user_level." \">Liste des r&eacute;servations</a>";
		echo "	<br />";
		echo "</td>";
	}
}
?>
		</tr>
	</tbody>
</table>

<?php pied_page() ?>

