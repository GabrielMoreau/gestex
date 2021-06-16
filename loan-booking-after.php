<?php
// loan-edit.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');


// Authenticate
auth_or_login('loan-list.php');
level_or_alert(3, 'Reservation d\'un équipement plus tard');

$logged_id   = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);

$equipment_id = param_get('equipment');

en_tete('Resérvation d\'un appareil plus tard');

$pdo = connect_db_or_alert();

$equipment_loans = get_all_reservations_equipment($pdo, $equipment_id);
var_dump($equipment_id);
var_dump($equipment_loans);

if ($equipment_loans == false) {
    exit();
}
?>

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

<?php pied_page() ?>