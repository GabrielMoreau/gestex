<?php
// loan-list.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
session_start();
if (empty($_SESSION['logged_user'])) {
	$logged_level = 0;
} else {
	$logged_id    = $_SESSION['logged_id'];
	$logged_user  = strtolower($_SESSION['logged_user']);
	$logged_level = $_SESSION['logged_level'];
}

$title = 'Liste des pr&ecirc;ts';

$pdo = connect_db_or_alert();

$team_id = param_get('equipe', 0);
if ($team_id > 0) {
	$team_selected = get_team_by_id($pdo, $team_id);
	$title        .= ' de l\'&eacute;quipe <i>'.$team_selected['nom'].'</i>';
	$loan_fetch = get_loan_listall_by_team($pdo, $team_id);
} 
else
	$loan_fetch = get_loan_listall($pdo);
?>

<?php en_tete($title) ?>

<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<th>
				Nom
			</th>
			<th>
				&Eacute;quipe redevable
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
			<?php if ($logged_level >= 3) { ?>
			<th class="sorttable_nosort"></th>
			<th class="sorttable_nosort"></th>
			<th class="sorttable_nosort"></th>
			<?php } ?>
		</tr>

<?php
$num_line = 1;
foreach ($loan_fetch as $loan_current) {
	if ($num_line % 2)
		echo '<tr class="impair">'.PHP_EOL;
	else
		echo '<tr class="pair">'.PHP_EOL;
	$num_line++;

	// recupere le nom de l'appareil via l'ID qui est mis dans un champs texte !
	// $appareil_selected = get_equipment_by_id($pdo, $loan_current['nom']);
	echo '  <td>';
	echo '    <a href="equipment-view.php?id='.$loan_current['nom'].'">'.$loan_current['equipment_name'].'</a>';
	echo '  </td>'.PHP_EOL;

	// recupere le nom d'equipe
	$team_selected = get_team_by_id($pdo, $loan_current['equipe']);
	echo '  <td>';
	echo '    <a href="equipment-list.php?equipe='.$loan_current['equipe'].'">'.$team_selected['nom'].'</a>';
	echo '  </td>'.PHP_EOL;

	echo '  <td>';
	echo      $loan_current['emprunt'];
	echo '  </td>'.PHP_EOL;
	echo '  <td>';
	echo      $loan_current['retour'];
	echo '  </td>'.PHP_EOL;
	echo '  <td>';
	echo      $loan_current['commentaire'];
	echo '  </td>'.PHP_EOL;
	echo '  <td>';
	echo      $loan_current['nom'];
	echo '  </td>'.PHP_EOL;

	if ($logged_level >= 3) {
		echo '  <td>';
		echo '    <a href="loan-edit.php?equipment=',$loan_current['nom'],'&mode=booking">'.ICON_LOAN_RESERVED.'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo '    <a href="loan-edit.php?id=',$loan_current['id'],'&mode=edit">'.ICON_EDIT.'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo '    <a href="loan-del.php?id=',$loan_current['id'],'">'.ICON_LOAN_RETURNED.'</a>';
		echo '  </td>'.PHP_EOL;
	}

	echo '</tr>'.PHP_EOL;
} // end foreach
?>
	</tbody>
</table>
</div>

<?php pied_page() ?>
