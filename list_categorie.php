<?php
//list_categorie.php
$web_page = true;

// Authenticate
require_once('session_auth.php');
require_once('html_functions.php');

session_start();
if (empty($_SESSION['logged_in_user'])) {
	$user_level = 0; // no auth
} else {
	$user_id        = $_SESSION['user_id'];
	$logged_in_user = strtolower($_SESSION['logged_in_user']);
	$user_level     = $_SESSION['level'];
}

en_tete('Liste des appareils');

//recupere la methode de tri

if (empty($_GET['tri']))
	$tri = 'nom';
else
	$tri = $_GET['tri'];
?>

Liste des appareils :<br />
<table cellpadding="20" cellspacing="4" border="1"
	style="width: 70%; text-align: left; margin-left: auto; margin-right: auto;">
	<tbody>
		<tr bgcolor="#f7d709">
			<th style="vertical-align: top; text-align: center;">
				<a href ="list_appareil.php?categorie=0 ">Liste globale</a><br />
			</th>
			<th style="vertical-align: top; text-align: center;">
				<a href ="list_appareil.php?equipe=15">Appareils au service instrumentation</a><br />
			</th>
		</tr>
	</tbody>
</table>

<br />
Liste des appareils par cat&eacute;gorie : <br />
<div class="catalog">
<table class="sortable">
	<tbody>
		<tr>
			<th>
				Cat&eacute;gorie
			</th>
			<?php if ($user_level >= 3) { ?>
			<th class="sorttable_nosort" colspan="3">
				<span class="option-right"><a href="list_categorie.php?"><?php echo ICON_ADD_CAT ?></a></span>
			</th>
			<?php } ?>
		</tr>

<?php
if ($pdo = connect_db()) {
	// recupere les refs du user
	$sql = 'SELECT id, nom FROM categorie ORDER BY ? ASC;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($tri));
	$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$num_line = 0;
	foreach ($categorie as $data) {
		if (($num_line % 2 )==0)
			echo '<tr class="pair">'.PHP_EOL;
		else
			echo '<tr class="impair">'.PHP_EOL;
		$num_line++;
		echo '  <td style="vertical-align: top;">';
		echo '    <a href="list_appareil.php?categorie='.$data['id'].'">'.$data['nom'].'</a>';
		echo '  </td>'.PHP_EOL;
		if ($user_level >= 3) {
			echo '  <td style="vertical-align: top;">';
			echo '    <a href="del_categorie.php?id=',$data['id'],'">'.ICON_TRASH.'</a>';
			echo '  </td>'.PHP_EOL;
		}
		echo '</tr>'.PHP_EOL;
	}
}
?>
	</tbody>
</table>
</div>

<?php pied_page() ?>

