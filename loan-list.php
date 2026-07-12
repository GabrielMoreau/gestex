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

$team_id = param_get('team_id', 0);
if ($team_id > 0) {
	$team_selected = get_team_by_id($pdo, $team_id);
	$title        .= ' de l\'&eacute;quipe <i>'.$team_selected['nom'].'</i>';
	$loan_fetch = get_loan_listall_by_team($pdo, $team_id);
} else
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
			<?php if ($logged_level >= 3): ?>
			<th class="sorttable_nosort"></th>
			<th class="sorttable_nosort"></th>
			<th class="sorttable_nosort"></th>
			<?php endif; ?>
		</tr>

	<?php
	$num_line = 1;
	foreach ($loan_fetch as $loan_current):
	?>
		<?php if ($num_line % 2): ?>
		<tr class="impair">
		<?php else: ?>
		<tr class="pair">
		<?php endif; ?>

			<?php $num_line++; ?>

			<td>
				<a href="equipment-view.php?id=<?= $loan_current['equipment_id'] ?>">
					<?= $loan_current['equipment_name'] ?>
				</a>
			</td>

			<?php
			$team_selected = get_team_by_id($pdo, $loan_current['team_id']);
			?>

			<td>
				<a href="equipment-list.php?team_id=<?= $loan_current['team_id'] ?>">
					<?= $team_selected['nom'] ?>
				</a>
			</td>

			<td><?= $loan_current['emprunt'] ?></td>
			<td><?= $loan_current['retour'] ?></td>
			<td><?= $loan_current['commentaire'] ?></td>
			<td><?= $loan_current['nom'] ?></td>

			<?php if ($logged_level >= 3): ?>
			<td>
				<a href="loan-edit.php?equipment_id=<?= $loan_current['equipment_id'] ?>&mode=booking">
					<?= ICON_LOAN_RESERVED ?>
				</a>
			</td>

			<td>
				<a href="loan-edit.php?id=<?= $loan_current['id'] ?>&mode=edit">
					<?= ICON_EDIT ?>
				</a>
			</td>

			<td>
				<a href="loan-del.php?id=<?= $loan_current['id'] ?>">
					<?= ICON_LOAN_RETURNED ?>
				</a>
			</td>
			<?php endif; ?>

		</tr>
	<?php endforeach; ?>

	</tbody>
</table>
</div>

<?php pied_page() ?>
