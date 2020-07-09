<?php
// list_user.php

// Authenticate
include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

require("html_functions.php");

en_tete('Liste de tous les utilisateurs');
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
?>

<br />
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
		</tr>

<?php	//interrogation base de donnees
if ($pdo = connect_db()) {
	// recupere la liste des users
	if ($user_level >=3) {
		$sql = 'SELECT * FROM users ORDER BY ?;';
	}
	else {
		$sql = 'SELECT * FROM users WHERE valid = 1 ORDER BY ?;';
	}
	// list($qh,$num) = query_db($querry);
	$stmt = $pdo->prepare($sql);
    $stmt->execute(array($tri));
	$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$num_line = 0;

	// while ($data = result_db($qh)) {
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
		echo '    <a href="mailto:'.$data['email'].'"> <img src="images/envelope.svg" nosave=""></a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		// recupere la liste de equipes
		$sql = 'SELECT nom FROM equipe WHERE id = ?;';
		// list($qheq,$numeq) = query_db($querry);
		// 	$eq = result_db($qheq)	 ;
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
			echo '      <img src="images/gear.svg" nosave="" title="Modifier le profil">';
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="user_changepwd.php?id='.$data['id'].'">';
			echo '      <img src="images/key.svg" nosave="" title="Changer le mot de passe">';
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="del_user.php?id='.$data['id'].'">';
			echo '       <img src="images/trash.svg" nosave="" title="Supprimer l\'utilisateur !">';
			echo '    </a>';
			echo '  </td>'.PHP_EOL;
			echo '  <td style="vertical-align: top; background-color:grss	ay;">';
			if ($data['valid'] == 0)
				echo ICON_PERSON_BAD;
			else
				echo ICON_PERSON_OK;
			echo '  </td>'.PHP_EOL;
		}
		echo '</tr>'.PHP_EOL;
		$num_line++;
	} //end foreach
} //end if
?>
	</tbody>
</table>
<br />
</div>
<?php pied_page() ?>
