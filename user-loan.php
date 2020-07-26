<?php
// user-loan.php
// Authenticate

require_once('module/auth-functions.php');

//require_once('module/db-functions.php');
if (!auth(1))
	Header("Location: login.php");

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

require_once('module/html-functions.php');

en_tete('Liste de vos emprunts');
//recuper la methode de tri
if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];

if ($pdo = connect_db()) {
	$user = get_user_all_by_id($pdo, $logged_id);
	$sql = 'SELECT * FROM pret WHERE commentaire RLIKE ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($user['nom']));
	$pret = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="catalog">
<table>
	<tbody>
		<tr>
			<th>
				Nom
			</th>
			<th>
				&Eacute;quipe
			</th>
			<th>
				Date
			</th>
			<th>
				Retour
			</th>
			<th>
				Emprunteur
			</th>
			<th>
				Num&eacute;ro de l'appareil
			</th>
		</tr>
		<?php
		foreach ($pret as $data) {
			if ($num_line % 2)
				echo '<tr class="pair">'.PHP_EOL;
			else
				echo '<tr class="impair">'.PHP_EOL;
			$num_line++;

			$sql = 'SELECT id, nom FROM Listing WHERE id = ?;';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array($data['nom']));
			$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo '  <td>';
			echo      $listing[0]['nom'];
			echo '  </td>'.PHP_EOL;

			// recupere le nom d'equipe
			$team = get_team_by_id($pdo, $data['equipe']);
			echo '  <td>';
			echo      $team['nom'];
			echo '  </td>'.PHP_EOL;

			echo '  <td>';
			echo      $data['emprunt'];
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			echo    $data['retour'];
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			echo      $data['commentaire'];
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			echo      $data['nom'];
			echo '  </td>'.PHP_EOL;

			if ($logged_level >= 3)  {
				echo '  <td>';
				echo '    <a href="loan-del.php?id=',$data['id'],'">'.ICON_TRASH.'</a>';
				echo '  </td>'.PHP_EOL;
			}

			echo '</tr>'.PHP_EOL;
		}
		?>
	</tbody>
</table>
</div>

<?php pied_page() ?>

