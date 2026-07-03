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
				Pr&eacute;nom
			</th>
			<th>
				Nom de famille
			</th>
			<th>
				T&eacute;l&eacute;phone
			</th>
			<th>
				Courriel
			</th>
			<th>
				&Eacute;quipe
			</th>
			<?php if ($logged_level >= 3) { ?>
			<th class="sorttable_nosort" colspan="3">
				<span class="option-right"><a href="user-edit.php?"><?php echo ICON_ADD_USER ?></a></span>
			</th>
			<?php } ?>
		</tr>

<?php // interrogation base de données
if ($pdo = connect_db()) {
	// recupere la liste des users
	$user_fetch = get_user_listall_by_logged_level($pdo, $logged_level);
	$num_line = 1;
	foreach ($user_fetch as $data) {
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($data['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'">'.PHP_EOL;
		if ($logged_level >=3 ) {
			echo '  <td>';
			echo      $data['level'];
			echo '  </td>'.PHP_EOL;
		}
		echo '  <td>';
		echo '    <a name="item'.$data['id'].'"></a>'.$data['prenom'];
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		// l'utilisateur a la possiblite de modifier ses coordonnees
		if ($logged_id == $data['id'] || $logged_level >= 3)
			echo '    <a href="user-edit.php?id='.$data['id'].'">'.$data['nom'].'</a>';
		else
			echo      $data['nom'];

		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		echo      $data['tel'];
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		$user_mail = sanitize_mail($data['email']);
		if (!empty($user_mail))
			echo '    <a href="mailto:'.$user_mail.'">'.ICON_MAIL.'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td>';
		// recupere la liste de equipes
		$equipe = get_team_by_id($pdo, $data['equipe']);
		if ($equipe)
			echo $equipe['nom'].' ('.$data['equipe'].')';
		echo '  </td>'.PHP_EOL;
		if ($logged_level >= 3) {
			echo '  <td>';
			echo '    <a href="user-edit.php?id='.$data['id'].'">';
			echo        ICON_PERSON_PROFIL;
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			echo '    <a href="user-changepwd.php?id='.$data['id'].'">';
			echo        ICON_PERSON_PASWD;
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td>';
			if ($data['valid'] == 0){
				echo '<a href="user-del.php?id='.$data['id'].'&status=0">';
				echo ICON_PERSON_BAD;
				echo '</a>';
			}else{
				echo '<a href="user-del.php?id='.$data['id'].'&status=1">';
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
