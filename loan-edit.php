<?php
// loan-edit.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

// Authenticate
auth_or_login('loan-list.php');
level_or_alert(3, 'Ajout ou modification d\'un pr&ecirc;t');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);
$logged_level = $_SESSION['logged_level'];

$equipment_id = param_get('equipment'); // -> new
$loan_id      = param_get('id');     // -> modify
$param_mode	  = param_get('mode', 'booking');

/* if ($loan_id == 0) {
	$mode = 'Ajouter';
	en_tete('Ajouter un pr&ecirc;t');
} else if (!empty($equipment_id) && $equipment_id != 0 && $equipment_id != NULL){
	$mode = 'Reserver apres';
	en_tete('Reserver plus tard');
} else {
	$mode = 'Modifier';
	en_tete('Modifier le pr&ecirc;t d\'un appareil'); 
}*/

if ($param_mode == 'booking') {
	$mode = 'Ajouter';
	en_tete('Ajouter un pr&ecirc;t');
} else if ($param_mode == 'booking-after'){
	$mode = 'Reserver apres';
	en_tete('Reserver plus tard');
} else {
	$mode = 'Modifier';
	en_tete('Modifier le pr&ecirc;t d\'un appareil'); 
}

// transmet la valeur de la categorie a la page valid appareil

$pdo = connect_db_or_alert();

$loan_selected = [];
if ($mode == 'Modifier') {
	$loan_selected = get_loan_all_by_id($pdo, $loan_id);
	$equipment_id = $loan_selected['nom'];
}
$num_line = 0;
$equipment_selected = get_equipment_by_id($pdo, $equipment_id);
$equipment_loans = get_all_reservations_equipment($pdo, $equipment_selected['id']);

if ($equipment_loans != false && $mode == 'Reserver apres') {
?>
<div class="catalog" style="margin-bottom: 2rem">
<table>
	<tbody>
		<tr>
			<th>
				ID
			</th>
			<th>
				Emprunteur
			</th>
			<th>
                Equipe Redevable
			</th>
			<th>
				Emprunt le
			</th>
			<th>
				Retour le
			</th>
		</tr>
        <?php
            foreach ($equipment_loans as $current_loan) {

                if ($num_line % 2)
				    echo '<tr class="pair">'.PHP_EOL;
			    else
				    echo '<tr class="impair">'.PHP_EOL;
			    $num_line++;
                $current_team = get_team_by_id($pdo, $current_loan["equipe"]);

                echo '  <td>';
                echo      $current_loan['id'];
                echo '  </td>'.PHP_EOL;

                echo '  <td>';
                echo      $current_loan['commentaire'];
                echo '  </td>'.PHP_EOL;

                echo '  <td>';
                echo      $current_team['nom'];
                echo '  </td>'.PHP_EOL;

                echo '  <td>';
                echo      $current_loan['emprunt'];
                echo '  </td>'.PHP_EOL;

                echo '  <td>';
                echo      $current_loan['retour'];
                echo '  </td>'.PHP_EOL;

                echo '</tr>' . PHP_EOL;
            }
        ?>
	</tbody>
</table>
</div>
<?php }?>

<div class="form" style="margin-bottom: 2rem">
<div class="catalog">
<table>
	<tbody>
		<tr>
			<th>
				ID
			</th>
			<th>
				Emprunteur
			</th>
			<th>
                Equipe Redevable
			</th>
			<th>
				Emprunt le
			</th>
			<th>
				Retour le
			</th>
		</tr>
        <?php
            foreach ($equipment_loans as $current_loan) {

                if ($num_line % 2)
				    echo '<tr class="pair">'.PHP_EOL;
			    else
				    echo '<tr class="impair">'.PHP_EOL;
			    $num_line++;
                $current_team = get_team_by_id($pdo, $current_loan["equipe"]);

                echo '  <td>';
                echo      $current_loan['id'];
                echo '  </td>'.PHP_EOL;

                echo '  <td>';
                echo      $current_loan['commentaire'];
                echo '  </td>'.PHP_EOL;

                echo '  <td>';
                echo      $current_team['nom'];
                echo '  </td>'.PHP_EOL;

                echo '  <td>';
                echo      $current_loan['emprunt'];
                echo '  </td>'.PHP_EOL;

                echo '  <td>';
                echo      $current_loan['retour'];
                echo '  </td>'.PHP_EOL;

                echo '</tr>' . PHP_EOL;
            }
        ?>
	</tbody>
</table>
</div>

<?php }?> */

?>
<div class="loan-list-container">
	<?php if ($equipment_loans) {
			foreach($equipment_loans as $loan_current) {
				echo '<div>'.PHP_EOL;
				echo '<h4>PRET N°'.$loan_current["id"].'</h4>'.PHP_EOL;
				echo $loan_current['emprunt'].'&nbsp;&#8594;&nbsp;'.$loan_current['retour'].PHP_EOL;
				echo '<span class="option-right">';
				if ($logged_level >= 3) {echo '<a href="loan-del.php?id='.$loan_current['id'].'">';}
				echo ICON_RETURN;
				if ($logged_level >= 3) {echo '</a></span> <span class="option-right"><a href="loan-edit.php?id='.$loan_current['id'].'&mode=edit">'.ICON_EDIT.'</a>&nbsp;';}
				echo '</span>'.PHP_EOL;
				echo '<br>'.get_team_by_id($pdo, $loan_current['equipe'])["nom"].PHP_EOL;
				echo '<br>'.$loan_current['commentaire'].PHP_EOL;
				echo '</div>'.PHP_EOL;
			}
		}?>
</div>

<div class="space-between"></div>


<div class="form" style="margin-bottom: 2rem">
<form action="loan-process.php" method="POST" name="inscrForm">
	<input type="hidden" name="id_equipment" value="<?php echo $equipment_id ?>" >
	<?php if ($mode == 'Modifier' || $mode == 'Reserver apres') { ?>
		<input type="hidden" name="id_loan" value="<?php echo $loan_id ?>" >
	<?php } ?>
<table>

		



	<tbody>
		<tr>
			<td>Nom de l'appareil
			</td>
			<td>
				<b><?php echo $equipment_selected['nom'] ?></b>
			</td>
		</tr>

		<tr>
			<td>Date demande pr&ecirc;t * (<i>format YYYY-MM-DD</i>)
			</td>
			<td>
				<input type="date" name="emprunt" size="10" maxlength="10" value="<?= param_post_key('emprunt', $loan_selected, date('Y-m-d', time())) ?>"
			</td>
		</tr>

		<tr>
			<td>Date de retour estim&eacute;e * (<i>format YYYY-MM-DD</i>)
			</td>
			<td>
				<input type="date" name="retour" size="10" maxlength="10" value="<?= param_post_key('retour', $loan_selected, date('Y-m-d', time())) ?>" >
			</td>
		</tr>

		<tr>
			<td>&Eacute;quipe redevable *
			</td>
			<td>
				<select name="equipe">
				<?php
				// recupere la liste des equipes
				$team_fetch = get_team_listshort($pdo);
				foreach ($team_fetch as $team_current) {
					echo '<option value="'.$team_current['id'].'"';
					if ($team_current['id'] == param_post_key('equipe', $loan_selected)) {
						echo ' selected';
					}
					echo '>'.$team_current['nom'].'</option>';
				} //end foreach
				?>
				</select>
				
			</td>
		</tr>

		<tr>
			<td>Commentaire (Nom de l'emprunteur)
			</td>
			<td>
				<input type="text" name="commentaire" size="30" maxlength="30" value="<?= param_post_key('commentaire', $loan_selected) ?>" >
			</td>
		</tr>

		<tr>
			<td>Les champs avec * sont &agrave;
				remplir obligatoirement, les autres sont optionnels.
			</td>
			<td style="vertical-align: top;" align="right">
				<input type="submit" name="Login" value="<?php echo $mode ?>">
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td colspan="2" class="button">
				<input class="cancel" type="submit" name="ok" formaction="equipment-view.php?id=<?php echo $equipment_id ?>" value="Annuler">
			</td>
		</tr>
	</tbody>
</table>
</form>
</div>

<?php pied_page() ?>
