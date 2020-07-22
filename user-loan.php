<?php
// user-loan.php
// Authenticate

require_once('auth-functions.php');

//require_once('db-functions.php');
if (!auth(1))
	Header("Location: login.php");

$logged_id        = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);
$user_level     = $_SESSION['level'];

require_once('html-functions.php');

en_tete('Liste de vos emprunts');
//recuper la methode de tri
if (empty($_GET['tri']))
	$tri ="id";
else
	$tri = $_GET['tri'];

if( $pdo =connect_db()){
	$sql = 'SELECT * from pret where nom_utilisateur = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($logged_id));
	$pret = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<table cellpadding="2" cellspacing="2" border="1"
	style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
			<th style="vertical-align: top; text-align: center;">
				Nom<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				&Eacute;quipe<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Date<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Retour<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				Emprunteur<br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				<a href="loan-list.php?tri=nom">
					Num&eacute;ro de l'appareil<br />
				</a>
			</th>
			<?php
			foreach ($pret as $data) {
				if ($num_line % 2)
				echo '<tr class="pair">'.PHP_EOL;
			else
				echo '<tr class="impair">'.PHP_EOL;
			$num_line++;
	
			$sql = 'SELECT id, nom FROM Listing WHERE id = ?;';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array($data['nom']));
			$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo '  <td style="vertical-align: top;">';
			echo      $listing[0]['nom'];
			echo '  </td>'.PHP_EOL;
	
			// recupere le nom d'equipe
			$sql = 'SELECT id, nom FROM equipe WHERE id = ?;';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array($data['equipe']));
			$equip = $stmt->fetchAll(PDO::FETCH_ASSOC);
			echo '  <td style="vertical-align: top;">';
			echo      $equip[0]['nom'];
			echo '  </td>'.PHP_EOL;
	
			echo '  <td style="vertical-align: top;">';
			echo      $data['emprunt'];
			echo '  </td>'.PHP_EOL;
			echo '  <td style="vertical-align: top;">';
			echo    $data['retour'];
			echo '  </td>'.PHP_EOL;
			echo '  <td style="vertical-align: top;">';
			echo      $data['commentaire'];
			echo '  </td>'.PHP_EOL;
			echo '  <td style="vertical-align: top;">';
			echo      $data['nom'];
			echo '  </td>'.PHP_EOL;
	
			if ($user_level >= 3) 	{
				echo '  <td style="vertical-align: top;">';
				echo '    <a href="loan-del.php?id=',$data['id'],'">'.ICON_TRASH.'</a>';
				echo '  </td>'.PHP_EOL;
			}
	
			echo '</tr>'.PHP_EOL;
		}
			
			
			
			
			?>


<?php pied_page() ?>

