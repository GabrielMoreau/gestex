<?php
// user-list.php
$web_page = true;

// Authenticate
require_once('auth-functions.php');
require_once('html-functions.php');

auth_or_login('user-list.php');
level_or_alert(1, 'Liste de tous les utilisateurs');

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

//recuper la methode de tri
if (empty($_GET['tri'])){
	if ($user_level >= 3) {
		$tri = 'level';
	} else {
		$tri = 'nom';
	}
} else {
	$tri = $_GET['tri'];
}

$id_highlight = 0;
if (!empty($_GET['highlight']))
	$id_highlight = $_GET['highlight'];

en_tete('Liste de tous les utilisateurs');
?>

<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<?php if ($user_level >= 3) { ?>
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
			<?php if ($user_level >= 3) { ?>
			<th class="sorttable_nosort" colspan="3">
				<span class="option-right"><a href="user-add.php?"><?php echo ICON_ADD_USER ?></a></span>
			</th>
			<?php } ?>
		</tr>

<?php	//interrogation base de donnees
if ($pdo = connect_db()) {
	// recupere la liste des users
	if ($user_level > 3){ // lorsqu'on est haut place, on voit tout le monde
		$sql = 'SELECT * FROM users ORDER BY ?;';
	}
	else if ($user_level == 3) { // losrqu'on est de niveau 3, on voit tout le monde sauf les users de plus haut level
		$sql = 'SELECT * FROM users WHERE level < 3 ORDER BY ?;';
	}
	else { // lorsqu'on est < 3, on voit tout le monde sauf le suser de level > 3 et les users non valide
		$sql = 'SELECT * FROM users WHERE valid = 1 and level < 3 ORDER BY ?;';
	}
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($tri));
	$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$num_line = 1;
	foreach ($user as $data) {
		$class = 'impair';
		if ($num_line % 2)
			$class = 'pair';
		$num_line++;
		if ($data['id'] == $id_highlight)
			$class .= ' highlight';
		echo '<tr class="'.$class.'">'.PHP_EOL;
		if ($user_level >=3 ) {
			echo '  <td style="vertical-align: top;">';
			echo      $data['level'];
			echo '  </td>'.PHP_EOL;
		}
		echo '  <td style="vertical-align: top;">';
		echo '    <a name="item'.$data['id'].'"></a>'.$data['prenom'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		// l'utilisateur a la possiblite de modifier ses coordonnees
		if ($user_id == $data['id'] || $user_level >= 3)
			echo '    <a href="user-add.php?id='.$data['id'].'">'.$data['nom'].'</a>';
		else
			echo      $data['nom'];

		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['tel'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo '    <a href="mailto:'.$data['email'].'">'.ICON_MAIL.'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		// recupere la liste de equipes
		$sql = 'SELECT nom FROM equipe WHERE id = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['equipe']));
		$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!empty($equipe)) {
			echo $equipe[0]['nom'];
			echo " (".$data['equipe'].")";
		}
		echo '  </td>'.PHP_EOL;
		if ($user_level >= 3) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="user-add.php?id='.$data['id'].'">';
			echo        ICON_PERSON_PROFIL;
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="user-changepwd.php?id='.$data['id'].'">';
			echo        ICON_PERSON_PASWD;
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td style="vertical-align: top; background-color:grss	ay;">';
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
	} //end foreach
} //end if
?>

	</tbody>
</table>
</div>

<?php pied_page() ?>
