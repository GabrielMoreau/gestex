<?php
// intervention-edit.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('intervention-edit.php.php');
level_or_alert(3, 'Ajout d\'une intervention');

$equipment_id = param_post_or_get('equipment', 0);
$mode = 'Modifier';
if ($equipment_id == 0) // new
	$mode = 'Ajouter';

$pdo = connect_db_or_alert();
$intervention_fetch = array();

if ($mode == 'Ajouter') {
	en_tete('Ajouter une intervention');
} else if ($mode == 'Modifier') {
    en_tete('Modifier une intervention');
    $intervention_fetch = get_intervention_listall_by_equipment($pdo, $equipment_id);
}
?>



<div class="form">
<form action="team-process.php" method="POST" name="inscrForm">
	<input type="hidden" name="id_equip" value="<?php if( $mode=='Modifier'){ echo $team_id; }?>" >
<table>
    <tbody>
        <tr>
            <th>Description</th>
            <td>
                <textarea name="description" rows="4" cols="33">Description de l'intervention...
                </textarea>
            </td>
        </tr>
        <tr>
            <th>Société</th>
            <?php // recupere la liste des equipes
			$company_fetch = get_supplier_listshort($pdo);
			?>
			<td>
				<select  name="company">
					<?php
					foreach ($company_fetch as $company_current) {
						echo '<option value="'.$company_current['id'].'"';
						// selectionne la bonne equipe
/* 						if ($company_current['id'] == param_post_key('company', $user_selected, 0))
							echo ' selected'; */
						echo '>'.$company_current['nom'].'</option>';
					} // end foreach
					?>
				</select>
				<span class="option-right"><a href="team-edit.php"><?php echo ICON_ADD_TEAM ?></a></span>
			</td>
        </tr>
        <tr>
            <th>Fiche d'interevention</th>
            <td>
				<?php if ($mode == 'Modifier' && $datasheet_count > 0) { ?>
				<ul>
					<?php foreach ($datasheet_fetch as $datasheet_current) { ?>
					<li><a href="<?php echo $datasheet_path.'/'.$datasheet_current['pathname'] ?>" target="_top"><?php echo $datasheet_current['description'] ?> (<?php echo $datasheet_current['pathname']?>)</a></li>
					<?php } ?>
				</ul>
				<?php } ?>
				<input type="file" name="notice" value="<?= param_post_key('notice', $equipment_selected) ?>" placeholder="Notice (facultatif)">
			</td>
        <tr>
            <td></td>
			<th class="button">
				<input type="submit" name="Login" value="<?php echo $mode ?>">
			</th>
		</tr>
            <th></th>
            <td></td>
        </tr>
    </tbody>
    <tbody>
		<tr>
			<td colspan="2" class="button">
				<?php if ($mode == 'Modifier') { ?>
				<input class="cancel" type="submit" name="ok" formaction="equipment-view.php?id=<?php echo $equipment_id ?>" value="Annuler">
				<?php } else { ?>
				<input class="cancel" type="submit" name="ok" formaction="equipment-list.php" value="Annuler">
				<?php } ?>
			</td>
		</tr>
        </form>
    </tbody>
</table>
</form>
</div>
