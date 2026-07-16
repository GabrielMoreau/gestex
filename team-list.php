<?php
// team-list.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

session_start();
if (empty($_SESSION['logged_user'])) {
	$logged_level = 0;
} else {
	$logged_id    = $_SESSION['logged_id'];
	$logged_user  = strtolower($_SESSION['logged_user']);
	$logged_level = $_SESSION['logged_level'];
}

$id_highlight = param_post_or_get('highlight', 0);

en_tete('Liste de toutes les équipes');
?>

<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<th>
				Nom
			</th>
			<th>
				Description
			</th>
			<th class="sorttable_nosort">
				<a href="equipment-list.php"><?=ICON_LIST?></a>
			</th>
			<th>
				Compte
			</th>
			<th>
				Chef d’équipe
			</th>
			<th class="sorttable_nosort"></th>
			<th class="sorttable_nosort"></th>

			<?php
			if ($logged_level == 2)
				echo '<th class="sorttable_nosort"></th>';
			if ($logged_level >= 3)
				echo '<th class="sorttable_nosort" colspan=2"><span class="option-right"><a href="team-edit.php">'.ICON_ADD_TEAM.'</a></span></th>';
			?>
		</tr>

<?php //interrogation base de données
if ($pdo = connect_db()) {
	// Récupère la liste de fournisseurs
	$team_fetch = get_team_listall($pdo);
	$num_line = 1;
	foreach ($team_fetch as $team_current) {
		// remplit le tableau
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($team_current['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'">'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo '    <a name="item'.$team_current['id'].'"></a>'.$team_current['name'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $team_current['description'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		$count_equipment = get_equipment_count_by_team($pdo, $team_current['id']);
		if ($count_equipment > 0)
			echo '    <a href="equipment-list.php?team_id='.$team_current['id'].'">'.ICON_LIST.'</a> '.$count_equipment;
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $team_current['accounting'];
		echo '  </td>'.PHP_EOL;

		// Récupère le nom de chef d'equipe
		$manager_user_id = get_user_short_by_id($pdo, $team_current['manager_user_id']);
		echo '  <td style="vertical-align: top;">';
		if ($manager_user_id)
			echo $manager_user_id['familyname'].' '.$manager_user_id['firstname'];
		echo '  </td>'.PHP_EOL;

		echo '  <td>';
		if (get_loan_count_by_team($pdo, $team_current['id']) > 0)
			echo '<a href="loan-list.php?team_id='.$team_current['id'].'">'.ICON_LOAN_RETURNED.'</a>';
		echo '  </td>'.PHP_EOL;

		echo '  <td>';
		if (get_equipment_count_is_loanable_by_team($pdo, $team_current['id']) > 0)
			echo '<a href="equipment-list.php?is_loanable=yes&team='.$team_current['id'].'">'.ICON_LOAN_BORROWED.'</a>';
		echo '  </td>'.PHP_EOL;

		if ($logged_level >= 2) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="team-edit.php?id='.$team_current['id'].'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
		} //end if
		if ($logged_level >= 3) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="team-del.php?id='.$team_current['id'].'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;
		} //end if
		echo '</tr>'.PHP_EOL;
	} //end foreach
} //end if
?>
	</tbody>
</table>
</div>

<?php pied_page() ?>
