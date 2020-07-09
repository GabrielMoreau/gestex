<?php
// list_prets.php
// Authenticate

//include("db_functions.php");
include("session_auth.php");
session_start();
//if (!auth(1))
	//Header("Location: login.php");

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

require("html_functions.php");

en_tete('Liste des pr&ecirc;ts');

//recuper la methode de tri
if (empty($_GET['tri']))
	$tri ="emprunt";
else
	$tri = $_GET['tri'];
echo $tri;

//recupere l'equipe
if (empty($_GET['equipe']))
	$eq = '';
else
	$eq = $_GET['equipe'];
?>

<i>Consulter la liste des &eacute;quipement communs disponibles au service instrumentation et choisir : 'Demande de pr&ecirc;t' en face de l'appareil souhait&eacute;</i><br />
<br />
Liste des pr&ecirc;ts : <br /><br /><br />

<table cellpadding="2" cellspacing="2" border="1"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
			<th style="vertical-align: top; text-align: center;">
				Nom<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				&Eacute;quipe<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Date<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Retour<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Emprunteur<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				<a href="list_pret.php?tri=nom">
					Num&eacute;ro de l'appareil<br />
				</a>
			</th>

			<?php 
			if ( $user_level >=3 )
				echo "<th></th>";
			?>
		</tr>

<?php	// interrogation base de donnees
if ($pdo = connect_db()) {
	// recupere la liste de appareils

	$sql = 'SELECT * FROM pret ORDER BY ? DESC;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	$pret = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($pret as $data) {
		// remplit le tableau
		echo '<tr">'.PHP_EOL;

		$sql = 'SELECT id, nom FROM Listing WHERE id = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['nom']));
		$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo '  <td style="vertical-align: top;">';
		echo      $listing[0]['nom'];
		echo '  </td>'.PHP_EOL;

		// recupere le nom d'equipe
		$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['equipe']));
		$equip = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo '  <td style="vertical-align: top;">';
		echo      $equip[0]['nom'];
		echo '  </td>'.PHP_EOL;

		echo '  <td style="vertical-align: top;">';
		echo      $data['emprunt'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo    $data['retour'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['commentaire'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['nom'];
		echo '  </td>'.PHP_EOL;

		if ($user_level >= 3) 	{
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="del-pret.php?id=',$data['id'],'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;
		}

		echo '</tr>'.PHP_EOL;
	} //end foreach
} //end if

?>
	</tbody>
</table>
<br />
</div>
<?php pied_page() ?>
