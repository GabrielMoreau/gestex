<?php
// loan-process.php
$web_page = true;

// Module
require_once('module/auth-functions.php');
require_once('module/html-functions.php');

// Authenticate
auth_or_login('loan-list.php');
level_or_alert(3, 'Ajout ou modification d’un prêt');

// validation d'un pret

unset($erreur);

$loan_id      = param_post('id_loan', 0); // modify
$equipment_id = param_post('id_equipment');
$team_id      = param_post('equipe');
$date_emprunt = param_post('emprunt');
$date_retour  = param_post('retour');
$commentaire  = param_post('commentaire');

$param_mode   = param_post('mode', 'booking'); // booking, booking-after, edit
if (isset($_GET['mode']) && param_get('mode') != '') {
	$param_mode = param_get("mode");
	$loan_id = param_get('id');
}

$date_tomorrow = strtotime('+1 day', strtotime(date("Y-m-d", time())));
$date_out_ymd  = strtotime(date('Y-m-d', strtotime($date_emprunt)));
$date_out_rtn  = strtotime(date('Y-m-d', strtotime($date_retour)));

//variables ne pouvant etre nulles
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
	if (empty($equipment_id))
		$erreur = 'Nom de l’appareil non précisé';
	if (empty($team_id))
		$erreur = 'Équipe non précisée';
	if (empty($date_emprunt))
		$erreur = 'Date d’emprunt non précisé';
}
if($date_retour < $date_emprunt)
	$erreur = 'L’intervalle des dates ne correspond pas. Merci de les vérifier';

$pdo = connect_db_or_alert();

if ($param_mode == "loan" || $param_mode == "booking") {
	$day_diff = $date_out_rtn - $date_out_ymd;
	$day_diff = intval(date('d', $day_diff));
	if ($loan_id > 0) {
		$equipment_max_day = get_equipment_all_by_id($pdo, get_equipment_by_loan_id($pdo, $loan_id))["max_day"];
	} else {
		$equipment_max_day = get_equipment_all_by_id($pdo, $equipment_id)["max_day"];
	}
	if ($equipment_max_day != 0) {
		if ($day_diff > $equipment_max_day)
			$erreur = 'L’équipement ne peut pas être emprunter sur une durée de plus de '.$equipment_max_day.' jours';
	}
}

/* $flag_new = true;
if ($loan_id > 0)
	$flag_new = false; */

if (!empty($erreur)) {
	//erreur
	$title         = 'Erreur sur l’emprunt';
	$action        = 'loan-edit.php?id='.$loan_id.'&mode=edit'; # à fixer
	if ($param_mode == 'booking')
		$action    = 'loan-edit.php?equipment='.$equipment_id.'&mode='.$param_mode;
	$message_text  = $erreur;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

if ($param_mode == "booking") {
	// CHECK FUTUR
	if ($date_out_ymd >= $date_tomorrow) {
		// CHECK DATE OVERLAP
		$loan_dates = get_loans_interval_by_id($pdo, $equipment_id, $date_emprunt, $date_retour);
		if (!empty($loan_dates) || $loan_dates != false) {
			$action = 'loan-edit.php?equipment='.$equipment_id.'&mode='.$param_mode;
			$title = 'Impossible de réserver sur la même plage qu’une autre réservation';
			$message_text = $title;
			$transmit_post = true;
			include_once('include/warning-box.php');
			exit();
		}

		// RESERVATION POSSIBLE
		set_loan_reserved_new($pdo, $equipment_id, $team_id, $date_emprunt, $date_retour, $commentaire);
		$message_text = 'La réservation a été effectuer avec succés';
	} else {
		// RESERVATION IMPOSSIBLE
		$title        = 'Impossible de réserver le jour même ou avant';
		$message_text = $title;
		$action       = 'loan-edit.php?equipment='.$equipment_id.'&mode='.$param_mode;
		$transmit_post = true;
		include_once('include/warning-box.php');
		exit();
	}
} else if ($param_mode == "edit") {
	// CHECK FUTUR

	if (get_loan_all_by_id($pdo, $loan_id)["status"] == STATUS_LOAN_BORROWED) {
		set_loan_update($pdo, $loan_id, $equipment_id, $team_id, $date_emprunt, $date_retour, $commentaire);
		$message_text = 'Mise à jour du prêt avec succés';
	} else {
		if ($date_out_ymd >= $date_tomorrow) {
			// CHECK DATE OVERLAP
			#$loan_dates = get_loans_interval_by_id($pdo, $equipment_id, $date_emprunt, $date_retour);
			$loan_dates = get_loans_interval_by_id_except_loan($pdo, $equipment_id, $date_emprunt, $date_retour, $loan_id);
			if (!empty($loan_dates) || $loan_dates != false) {
				$action = 'loan-edit.php?id='.$loan_id.'&mode='.$param_mode;
				$title = 'Impossible d’éditer sur la même plage qu’une autre réservation';
				$message_text = $title;
				$transmit_post = true;
				include_once('include/warning-box.php');
				exit();
			}

			set_loan_update($pdo, $loan_id, $equipment_id, $team_id, $date_emprunt, $date_retour, $commentaire);
			$message_text = 'Mise à jour du prêt sur l’appareil '.$equipment_id.' validé<br />';

		} else {
			// EDITION IMPOSSIBLE
			$title        = 'Impossible d’éditer la réservation le jour même ou avant';
			$message_text = $title;
			$action       = 'loan-edit.php?id='.$loan_id.'&mode='.$param_mode;
			$transmit_post = true;
			include_once('include/warning-box.php');
			exit();
		}
	}
} else if ($param_mode == 'loan') {

	if ($loan_id > 0 && get_loan_all_by_id($pdo, $loan_id)["status"] == STATUS_LOAN_RESERVED) {
		if (get_loans_all_by_equipment_borrowed($pdo, get_equipment_by_loan_id($pdo, $loan_id)) == false) {
			set_loan_update_to_borrowed($pdo, $loan_id);
			$message_text = 'Votre réservation a bien été enregistré comme emprunt';
			$title     = 'Résultat demande d’emprunt';
			$action    = 'equipment-view.php?id='.get_equipment_by_loan_id($pdo, $loan_id);
			include_once('include/message-box.php');
			exit();
		} else {
			$title         = 'Erreur sur l’emprunt';
			$action        = 'loan-edit.php?id='.$loan_id.'&mode=edit';
			$erreur        = 'L’équipement est déjà emprunté';
			$message_text  = $erreur;
			$transmit_post = true;
			include_once('include/warning-box.php');
			exit();
		}
	} else {
		if (empty($equipment_id)) {
			$equipment_id = get_equipment_by_loan_id($pdo, $loan_id);
			$check = check_loan_borrowed_by_equipment($pdo, get_equipment_by_loan_id($pdo, $loan_id));
		} else {
			$check = check_loan_borrowed_by_equipment($pdo, $equipment_id);
		}
		if ($check) {
			$title         = 'L’équipement est déjà en emprunt';
			$action        = 'loan-edit.php?equipment='.$equipment_id.'&mode=booking';
			$erreur        = 'L’équipement est déjà en emprunt';
			$message_text  = $erreur;
			$transmit_post = true;
			include_once('include/warning-box.php');
			exit();
		}
		if ($date_out_ymd >= $date_tomorrow) {
			$loan_id = set_loan_reserved_new($pdo, $equipment_id, $team_id, $date_emprunt, $date_retour, $commentaire);
			$message_text = 'Ajout de la réservation sur l’appareil '.$equipment_id.' validé<br />';
		} else {
			$loan_id = set_loan_borrowed_new($pdo, $equipment_id, $team_id, $date_emprunt, $date_retour, $commentaire);
			$message_text = 'Ajout du prêt sur l’appareil '.$equipment_id.' validé<br />';
		}
	}



} else {
	$title         = 'Impossible d’effectuer un emprunt ou une réservation';
	$action        = 'loan-edit.php?id='.$loan_id.'&mode=edit';
	$erreur        = 'Impossible d’effectuer un emprunt ou une réservation, erreur inconnue';
	$message_text  = $erreur;
	$transmit_post = true;
	include_once('include/warning-box.php');
	exit();
}

$title     = 'Résultat demande d’emprunt';
$action    = 'equipment-view.php?id='.$equipment_id;
$highlight = '';
include_once('include/message-box.php');
exit();
?>
