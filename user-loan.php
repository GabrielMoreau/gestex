<?php
// user-loan.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

auth_or_login('user-list.php');
level_or_alert(1, 'Pr&ecirc;t d\'un utilisateur');

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

en_tete('Liste de vos emprunts');

if ($pdo = connect_db()) {
	$user = get_user_all_by_id($pdo, $logged_id);
	$loan_fetch = get_loan_find($pdo, $user['familyname']);
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
		foreach ($loan_fetch as $loan_current) {
			if ($num_line % 2)
				echo '<tr class="pair">'.PHP_EOL;
			else
				echo '<tr class="impair">'.PHP_EOL;
			$num_line++;

			$equipment = get_equipment_listshort_by_id($pdo, $loan_current['equipment_id']);
			echo '  <td>';
			echo      $equipment['nom'];
			echo '  </td>'.PHP_EOL;

			// recupere le nom d'equipe
			$team = get_team_by_id($pdo, $loan_current['team_id']);
			echo '  <td>';
			echo      $team['name'];
			echo '  </td>'.PHP_EOL;

			echo '  <td>';
			echo      $loan_current['start_date'];
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			echo    $loan_current['end_date'];
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			echo      $loan_current['comment'];
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			echo      $loan_current['equipment_id'];
			echo '  </td>'.PHP_EOL;

			if ($logged_level >= 3)  {
				echo '  <td>';
				echo '    <a href="loan-del.php?id=',$loan_current['id'],'">'.ICON_TRASH.'</a>';
				echo '  </td>'.PHP_EOL;
			}

			echo '</tr>'.PHP_EOL;
		}
		?>
	</tbody>
</table>
</div>

<?php pied_page() ?>

