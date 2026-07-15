<?php
// user-list.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

auth_or_login('user-list.php');
level_or_alert(1, 'Liste de tous les utilisateurs');

$logged_id    = $_SESSION['logged_id'];
$logged_user  = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];
$id_highlight = param_post_or_get('highlight', 0);

en_tete('Liste de tous les utilisateurs');
?>

<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<?php if ($logged_level >= 3) { ?>
			<th>
				Level
			</th>
			<?php } ?>
			<th>
				Prénom
			</th>
			<th>
				Nom de famille
			</th>
			<th>
				Téléphone
			</th>
			<th>
				Courriel
			</th>
			<th>
				Équipe
			</th>
			<?php if ($logged_level >= 3) { ?>
			<th class="sorttable_nosort" colspan="3">
				<span class="option-right"><a href="user-edit.php?"><?php echo ICON_ADD_USER ?></a></span>
			</th>
			<?php } ?>
		</tr>

<?php // interrogation base de données
if ($pdo = connect_db()) {
	// Récupère la liste des users
	$user_fetch = get_user_listall_by_logged_level($pdo, $logged_level);
	$num_line = 1;
	foreach ($user_fetch as $user_current) {
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($user_current['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'">'.PHP_EOL;
		if ($logged_level >=3 ) {
			echo '  <td>';
			echo      $user_current['level'];
			echo '  </td>'.PHP_EOL;
		}
		echo '  <td>';
		echo '    <a name="item'.$user_current['id'].'"></a>'.$user_current['firstname'];
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		// l'utilisateur a la possiblite de modifier ses coordonnees
		if ($logged_id == $user_current['id'] || $logged_level >= 3)
			echo '    <a href="user-edit.php?id='.$user_current['id'].'">'.$user_current['familyname'].'</a>';
		else
			echo      $user_current['familyname'];

		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo      $user_current['phone'];
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		$user_mail = sanitize_mail($user_current['email']);
		if (!empty($user_mail))
			echo '    <a href="mailto:'.$user_mail.'">'.ICON_MAIL.'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		// Récupère la liste de equipes
		$team = get_team_by_id($pdo, $user_current['team_id']);
		if ($team)
			echo $team['name'].' ('.$user_current['team_id'].')';
		echo '  </td>'.PHP_EOL;
		if ($logged_level >= 3) {
			echo '  <td>';
			echo '    <a href="user-edit.php?id='.$user_current['id'].'">';
			echo        ICON_PERSON_PROFIL;
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			echo '    <a href="user-changepwd.php?id='.$user_current['id'].'">';
			if (preg_match('/^!ldap!/i', $user_current['password']))
				echo '<span class="check-warn">'.ICON_PERSON_PASWD.'</span>';
			else
				echo ICON_PERSON_PASWD;
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			if ($user_current['valid'] == 0){
				echo '<a href="user-del.php?id='.$user_current['id'].'&status=0">';
				echo ICON_PERSON_BAD;
				echo '</a>';
			}else{
				echo '<a href="user-del.php?id='.$user_current['id'].'&status=1">';
				echo ICON_PERSON_OK;
				echo '</a>';
			}
		}
		echo '  </td>'.PHP_EOL;
		echo '</tr>'.PHP_EOL;
	} // end foreach
} // end if
?>

	</tbody>
</table>
</div>

<?php pied_page() ?>
