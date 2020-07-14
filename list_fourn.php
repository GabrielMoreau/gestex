<?php
//list_fourn.php

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

session_start();
$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

en_tete('Liste de tous les fournisseurs');

// recupere la methode de tri
$tri = $_GET['tri'];
if (empty($_GET['tri']))
	$tri = 'nom';
?>

<br />
<table cellpadding="2" cellspacing="2" border="0"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr>
			<td style="vertical-align: top; text-align: center;">
				<a href="find_fourn.php">Rechercher</a>
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
				Adresse<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				T&eacute;l&eacute;phone<br />
			</th>
			<th style="vertical-align: top; text-align: center; " >
				Fax<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Courriel<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				WWW<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Contacts<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Description<br />
			</th>
			<?php
			if ($user_level >=2)
				echo"<th></th>";
			if ($user_level >=3)
				echo"<th></th>";
			?>
		</tr>

<?php	// interrogation base de donnees
if ($pdo = connect_db()) {
	// recupere la liste de fournisseurs
	$sql = 'SELECT * FROM fournisseurs ORDER BY ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	$fournisseur = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$num_line = 0;
	foreach ($fournisseur as $data) {
		// remplit le tableau
		if ($num_line % 2)
			echo '<tr class="impair">'.PHP_EOL;
		else
			echo '<tr class="pair">'.PHP_EOL;
		$num_line++;
		echo '  <td style="vertical-align: top;">'.$data['nom'].'</td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">'.$data['adresse'].'</td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;" nowrap>'.$data['tel'].'</td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;" nowrap>'.$data['fax'].'</td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		if (!empty($data['mail']))
			echo '    <a href="mailto:'.$data['mail'].'">'.ICON_MAIL.'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">';
		if (!empty($data['www']))
			echo '    <a href="http://'.$data['www'].'" target="_fournView">'.ICON_URL.'</a>';
		echo '  </td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">'.$data['contact'].'</td>'.PHP_EOL;
		echo '  <td style="vertical-align: top;">'.$data['descr'].'</td>'.PHP_EOL;
		if ($user_level >=2) {
			echo '  </td><td style="vertical-align: top;">';
			echo '    <a href="add_fourn.php?id='.$data['id'].'">'.ICON_EDIT.'</a>';
			echo '  </td>'.PHP_EOL;
		} //end if
		if ($user_level >=3) {
			echo '  </td><td style="vertical-align: top;">';
			echo '    <a href="del_fourn.php?id='.$data['id'].'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;
		} //end if
		echo '</tr>'.PHP_EOL;
	} //end foreach
} //end if
?>
	</tbody>
</table>
<br />
</div>
<?php pied_page() ?>
