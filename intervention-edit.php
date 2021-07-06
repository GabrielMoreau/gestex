<?php
// intervention-edit.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('team-list.php');
level_or_alert(3, 'Ajout d\'une intervention');

$intervention_id = param_post_or_get('id', 0);
$mode = 'Modifier';
if ($intervention_id == 0) // new
	$mode = 'Ajouter';

$pdo = connect_db_or_alert();

if ($mode == 'Ajouter') {
	en_tete('Ajouter une intervention');
} else if ($mode == 'Modifier') {
    en_tete('Modifier une intervention');
}
?>

<table>
    <tbody>
        <tr>
            <th>Description</th>
            <td></td>
        </tr>
        <tr>
            <th></th>
            <td></td>
        </tr>
        <tr>
            <th></th>
            <td></td>
        </tr>
        <tr>
            <th></th>
            <td></td>
        </tr>
    </tbody>
</table>
