<?php
//list_equip.php

// Authenticate
include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

require("html_functions.php");

en_tete("Liste de toutes les &eacute;quipes:");

//recuper la methode de tri
$tri = $_GET['tri'];
if (empty($tri))
	$tri = 'nom';
?>

<br />
<table cellpadding="2" cellspacing="2" border="0"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr class="menu">
			<td style="vertical-align: top; text-align: center;">
				<a href="accueil.php?tri=date">Retour &agrave; l'accueil</a>
				<br />
			</td>

			<?php if ($user_level >=3) { ?>
			<td style="vertical-align: top; text-align: center;">
				<a href="add_equip.php">Ajout d'une &eacute;quipe</a>
				<br />
			</td>
			<?php } ?>

			<td style="vertical-align: top; text-align: center;">
				<a href="logout.php?variable=projet">Quitter</a>
				<br />
			</td>
		</tr>
	</tbody>
</table>
<br />

<table cellpadding="2" cellspacing="2" border="1"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
			<th style="vertical-align: top; text-align: center;">
				<a href ="list_fourn.php?tri=nom">Nom</a><br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Description<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Compte<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Chef d'&eacute;quipe <br />
			</th>

			<?php
			if ($user_level >= 2)
				echo '<th></th>';
			if ($user_level >= 3)
				echo '<th></th>';
			?>
		</tr>

<?php	//interrogation base de donnees
if ($pdo = connect_db()) {
	// recupere la liste de fournisseurs
	$sql = 'SELECT * FROM equipe order by ?';
	// list($qh,$num) = query_db($querry);
	// $last_id=0;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$num_line=0;

	// while ($data = result_db($qh)) {
	foreach ($equipe as $data) {
		// remplit le tableau
		if (($num_line % 2 )==0)
			echo '<tr class="pair">'.PHP_EOL;
		else
			echo '<tr class="impair">'.PHP_EOL;

		echo '  <td style="vertical-align: top;">';
		echo      $data['nom'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['descr'];
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		echo      $data['compte'];
		echo '  </td>'.PHP_EOL;

		// recupere le nom de chef d'equipe
		$sql = 'SELECT id, nom FROM users WHERE id = ?';
		// list($qheq,$numeq) = query_db($querry);
		// $chef = result_db($qheq);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($data['chef']));
		$chef = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo '  <td style="vertical-align: top;">';
		echo      $chef[0]['nom'];
		echo '  </td>'.PHP_EOL;

		if ($user_level >= 2) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="add_equip.php?id='.$data['id'].'"><img src="images/pen.svg" nosave="" title="Modifier"></a>';
			echo '  </td>'.PHP_EOL;
		} //end if
		if ($user_level >= 3) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="del_equip.php?id='.$data['id'].'"><img src="images/trash.svg" nosave="" title="Supprimer"></a>';
			echo '  </td>'.PHP_EOL;
		} //end if
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
