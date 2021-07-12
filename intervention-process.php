<?php
// equipment-process.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('equipment-list.php');
level_or_alert(3, 'Ajout / Modification d\'une intervention');

$logged_level = $_SESSION['logged_level'];

unset($erreur);

$description        = param_post('description');
$equipment_id       = param_post('equipment');
$supplier_id        = param_post('company');
$recipe             = param_post('recipe');
$date               = param_post('date');
$intervention_id    = param_post('id');

$pdo = connect_db_or_alert();

$new = True;
if (isset($id) && !empty($id))
    $new = false;

var_dump($description);
var_dump($equipment_id);
var_dump($supplier_id);
var_dump($date);

if ($recipe != '') {
    $id_recipe = set_recipe_new($pdo, $equipment_id, 'recipe');
    if (!$id_recipe) {
        $title        = 'Erreur appareil';
        $action       = 'equipment-view.php?id='.$equipment_id;
        $message_text = ($logged_level > 3 ? $err_msg : 'Erreur dans l\'ajout d\'une fiche d\'intervention &agrave; appareil (pas au format PDF ?)');
        include_once('include/message-box.php');
        exit();
    }
}

if ($new) {
    $res = set_new_intervention($pdo, $description, $supplier_id, $equipment_id, $date);
}
?>