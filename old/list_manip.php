<?php
// list_manip.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');

if (!auth(1))
	Header("Location: login.php");

$logged_user = strtolower($_SESSION['logged_user']);
$logged_id = $_SESSION['logged_id'];
$logged_level = $_SESSION['logged_level'];
require_once('module/html-functions.php');

en_tete('Liste des Manips');

//recuper la methode de tri
if (empty($_GET['tri']))
	$tri ="nom";
else
	$tri = $_GET['tri'];

if ( $pdo = connect_db() ){
	// recupere les refs du user
	// $querry = "SELECT * FROM users WHERE loggin='$logged_user' " ;
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	$sql = 'SELECT nom, prenom FROM users WHERE loggin = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($logged_user));
	$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
	?>
	<br />
	<table cellpadding="2" cellspacing="2" border="0" style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
		<tbody>
			<tr class=menu>
				<th style="vertical-align: top; text-align: center; class:menu;" >
					<a href="rapport-create.php">Editer<br />les rapports</a>
					<br />
				</th>
			</tr>
		</tbody>
	</table>
	<br />
	Voici la liste des manips d&eacute;j&agrave; rentr&eacute;es dans la base de donn&eacute;es :
	<br />
	<table cellpadding="2" cellspacing="2" border="1" style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
		<tbody>
			<tr bgcolor="#f7d709">
				<th style="vertical-align: top; text-align: center;">
					<a href ="list_manip.php?tri=date">Date</a><br />
				</th>
				<th style="vertical-align: top; text-align: center;">
					<a href ="list_manip.php?tri=nom">Nom</a><br />
				</th>
				<th style="vertical-align: top; text-align: center;">
					<a href ="list_manip.php?tri=descr">Description</a><br />
				</th>
				<th style="vertical-align: top; text-align: center;">
					<a href ="list_manip.php?tri=local">Local</a> <br />
				</th>
				<th style="vertical-align: top; text-align: center;">
					<a href ="list_manip.php?tri=equipe">&Eacute;quipe</a>
				</th>
				<?php if ($logged_level != 1) {
				//pas necessaire si chercheur logue
				?>
				<th style="vertical-align: top; text-align: center;">
					<a href ="list_manip.php?tri=chercheur">Chercheur</a>
				</th>
				<th style="vertical-align: top; text-align: center;">
					<br />
				</th>
				<?php } ?>
				<th style="vertical-align: top; text-align: center;">
					<br />
				</th>
			</tr>
			<?php	//interrogation base de donnees

			// recupere la liste de manips
			$sql = 'SELECT * FROM manip ORDER BY ?;';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array($tri));
			$manip_fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$num_line = 1;
			foreach ($manip_fetch as $manip) {
				if ($num_line % 2)
					echo '<tr class="pair">'.PHP_EOL;
				else
					echo '<tr class="impair">'.PHP_EOL;
				$num_line++;
				$relief = 0;
				if ($logged_level > 1)
					$relief = 1;
				// remplit le tableau
				//if ($logged_level <= 1) {
				//mise en evidence des manips concernant le chercheur connecte
				//	if ($manip['chercheur'] == $logged_id || $manip['chercheur_bis'] == $logged_id){
				//		echo '<tr bgcolor="#FFFAD0" style="vertical-align: top;">';
				//		$relief = 1;
				//	} else {
				//		echo '<tr style="vertical-align: top;">';
				//	}
				//} else {
				//	echo '<tr style="vertical-align: top;">';
				//}
				echo '  <td style="vertical-align: top;">';
				echo      $manip['date'];
				echo '  </td>';
				echo '  <td style="vertical-align: top;">';
				if ($relief ==1)
					//possibilite d'edition des manips concernant le chercheur connecte
					echo '    <a href="manip_maint.php?id=',$manip['id'],'">';
				echo $manip['nom'];
				if ($relief == 1)
					echo ' '.ICON_URL.'</a>';
				echo '  </td>';
				echo '  <td style="vertical-align: top;">';
				echo $manip['descr'];
				echo '  </td>';
				echo '  <td style="vertical-align: top;">';
				echo $manip['local'];
				echo '  </td>';
				// recupere la liste de equipes
				$sql = 'SELECT nom FROM equipe WHERE id = ?;';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array($manip['equipe']));
				$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
				echo '  <td style="vertical-align: top;">';
				if (!empty($equipe)) {
					echo $equipe[0]['nom'];
				}
				echo '  </td>';
				if ($logged_level != 1) {
					//si chercheur logue pas necessaire
					// recupere la liste des chercheurs
					$sql = 'SELECT nom FROM users WHERE id = ?;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array($manip['chercheur']));
					$chercheur = $stmt->fetchAll(PDO::FETCH_ASSOC);
					// var_dump($chercheur);
					echo '  <td style="vertical-align: top;">';
					if (!empty($chercheur)) {
						echo $chercheur[0]['nom'];
					}
					echo '  </td>';
				}
				if ($logged_level >= 2) {
					echo '  <td style="vertical-align: top;">';
					echo '    <a href="add_manip.php?id=',$manip['id'],'">'.ICON_EDIT.'</a>';
					echo '  </td>';
				}
				if ($logged_level >= 3) {
					echo '  <td style="vertical-align: top;">';
					echo '    <a href="del_manip.php?id=',$manip['id'],'">'.ICON_TRASH.'</a>';
					echo '  </td>';
				}
			echo '</tr>';
			} // end foreach
			?>
 		</tbody>
	</table>
	<br />

<?php
} //end if
pied_page()
?>
