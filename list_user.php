<?php
// list_user.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

auth_or_login('list_user.php');
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

en_tete('Liste de tous les utilisateurs');
?>

<table cellpadding="2" cellspacing="2" border="1"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
			<?php if ($user_level >= 3) { ?>
			<th style="vertical-align: top; text-align: center;">
				Level<br />
			</th>
			<?php } ?>
			<th style="vertical-align: top; text-align: center;">
				Pr&eacute;nom<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				<a href ="list_user.php?tri=nom">Nom de famille</a><br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				T&eacute;l&eacute;phone<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Courriel<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				<a href ="list_user.php?tri=equipe">&Eacute;quipe</a><br />
			</th>
			<?php if ($user_level >= 3) { ?>
			<th style="vertical-align: top; text-align: center;" colspan="4">
				Admin<br />
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
	$num_line = 0;

	foreach ($user as $data) {
		// remplit le tableau
		if (($num_line % 2 ) == 0)
			echo '<tr class="pair">'.PHP_EOL;
		else
			echo '<tr class="impair">'.PHP_EOL;
		if ($user_level >=3 ) {
			echo '  <td style="vertical-align: top;">';
			echo      $data['level'];
			echo '  </td>'.PHP_EOL;
		}
		echo '  <td style="vertical-align: top;">';
		echo      $data['prenom'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		// l'utilisateur a la possiblite de modifier ses coordonnees
		if ($user_id == $data['id'] || $user_level >= 3)
			echo '    <a href="add_user.php?id='.$data['id'].'">'.$data['nom'].'</a>';
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
			echo '    <a href="add_user.php?id='.$data['id'].'">';
			echo        ICON_PERSON_PROFIL;
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="user_changepwd.php?id='.$data['id'].'">';
			echo        ICON_PERSON_PASWD;
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td style="vertical-align: top; background-color:grss	ay;">';
			if ($data['valid'] == 0){
				echo '<a href="del_user.php?id='.$data['id'].'&status=0">';
				echo ICON_PERSON_BAD;
				echo '</a>';
			}else{
				echo '<a href="del_user.php?id='.$data['id'].'&status=1">';
				echo ICON_PERSON_OK;
				echo '</a>';
			}
		}
		echo '  </td>'.PHP_EOL;
		echo '</tr>'.PHP_EOL;
		$num_line++;
	} //end foreach
} //end if
?>

	</tbody>
</table>

<?php pied_page() ?>
