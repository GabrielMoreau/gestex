<?php
// loan-del.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

if (!auth(3))
	Header("Location: loan-list.php");

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$id_loan = param_post_or_get('id');
if (empty($id_loan) || $_POST['ok'] == 'cancel')
	redirect('loan-list.php');
if ($_POST['ok'] == 'edit')
	redirect('loan-add.php?id='.$id_loan);

$valid = 'no';
if ($_POST['ok'] == 'yes') // si ok dans l'url est 'yes', on valide la suppression
	$valid = 'yes';

if ($valid == 'yes') {
	if ($pdo = connect_db()) {
		// on supprime le pret
		$sql = 'DELETE LOW_PRIORITY FROM pret WHERE id = ? LIMIT 1;';
		// list($qh,$num) = query_db($querry);
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($id_loan));
	}
	//on retourne a la page d'accueil
	redirect('loan-list.php');
	
$pdo = connect_db()
$loan = get_loan_all_by_id($pdo, $id_loan);
$equipment = get_equipment_by_id($pdo, $loan['nom']);
}

en_tete('Retour d\'un appareil (fin du pr&ecirc;t)');
?>

<center class="alert">
<form action="loan-del.php" method="POST">
	<input type="hidden" name="id" value="<?php echo $id_loan ?>" >
	Concernant le pr&ecirc;t <?php echo $id_loan ?> (<?php echo $equipment['nom'] ?>), voulez-vous :
	<ul>
		<li>Modifier / &Eacute;diter le pr&ecirc;t ? <button type="submit" name="ok" value="edit"><?php echo ICON_EDIT ?></button></li>
		<li>Supprimer le pr&ecirc;t (retour de l'appareil) ?
			<button class="red" type="submit" name="ok" value="yes">Oui</button>
			<button class="green" type="submit" formaction="loan-list.php" value="no">Non</button>
		</li>
	</ul>
	<hr>
	<button type="submit" name="ok" value="cancel">Annuler</button>
</form>
</center>

<?php pied_page() ?>
