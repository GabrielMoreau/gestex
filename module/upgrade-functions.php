<?php if (!$web_page) exit() ?>

<?php
require_once('db-functions.php');
require_once('base-functions.php');

// ---------------------------------------------------------------------

function upgrade_datasheet_1_to_2($pdo) {
	$old_datasheet_path = './data/instru';
	$new_datasheet_path = './data/datasheet';

	if (!is_dir($new_datasheet_path))
		mkdir($new_datasheet_path, 0755);

	$count = 0;
	foreach (get_equipment_listshort($pdo) as $equipment) {
		$equipment_name = $equipment['nom'];
		$equipment_id   = $equipment['id'];
		$datasheet_filename_kebab = string_to_filename_kebab($equipment_name);
		$datasheet_filename_snake = string_to_filename_snake($equipment_name);

		$local_dir = $old_datasheet_path.'/'.$datasheet_filename_snake;
		if (is_dir($local_dir) and ($dh = @opendir($local_dir))) {
			while (($file = readdir($dh)) !== false) {
				if (is_dir($file))
					continue;
				if (preg_match('/\.pdf$/i', $file)) {
					
					$sql1 = 'INSERT INTO datasheet (description, id_equipment) VALUES (?, ?);';
					$stmt1 = $pdo->prepare($sql1);
					$stmt1->execute(array($equipment_name, $equipment_id));
					$datasheet_id = $pdo->lastInsertId();
					
					$sub_path = $datasheet_id.'-'.random_string(8);
					$sql2 = 'UPDATE datasheet SET pathname = ? WHERE id = ?;';
					$stmt2 = $pdo->prepare($sql2);
					$stmt2->execute(array($sub_path.'/'.$datasheet_filename_kebab.'.pdf', $datasheet_id));
					
					$new_dir = $new_datasheet_path.'/'.$sub_path;
					if (!is_dir($new_dir))
						mkdir($new_dir, 0755);
					copy($local_dir.'/'.$file, $new_dir.'/'.$datasheet_filename_kebab.'.pdf');
					echo 'Copy '.$local_dir.'/'.$file .' to '. $new_dir.'/'.$datasheet_filename_kebab.'.pdf' .'<br>';
				}
			}
		}
	}
	return true;
}

// ---------------------------------------------------------------------

?>
