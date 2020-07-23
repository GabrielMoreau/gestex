<?php
// admin-upgrade.php
$web_page = true;

// Authenticate
require_once('auth-functions.php');
require_once('html-functions.php');
require_once('upgrade-functions.php');

auth_or_login('index.php');
level_or_alert(5, 'Mise &agrave; jour de l\'application');

if (!$pdo = connect_db())
	exit();

en_tete('Mise à jour de la base de donnée des notices');

$datasheet_version = get_version_by_name($pdo, 'datasheet');
echo "HERE31 $datasheet_version TTT<br>";
if (!$datasheet_version or $datasheet_version < 2) {
	echo 'YES';
	if (upgrade_datasheet_1_to_2($pdo))
		set_version_by_name($pdo, 'datasheet', 2);
} else 
	echo 'NOOO';

echo "HERE33<br>";
?>

<?php pied_page() ?>
