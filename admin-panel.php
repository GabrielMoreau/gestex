<?php
// category-list.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

// Authenticate
auth_or_login('index.php');
level_or_alert(4, 'Console Administrateur');

// session_start();
// if (empty($_SESSION['logged_user'])) {
// 	$logged_level = 0;
// } else {
// 	$logged_id    = $_SESSION['logged_id'];
// 	$logged_user  = strtolower($_SESSION['logged_user']);
// 	$logged_level = $_SESSION['logged_level'];
// }

en_tete('Console Administrateur');

// if ($logged_level < 4) {
//     echo 'Permission denied !';
//     exit();
// }

$pdo = connect_db_minimal();
?>

<div class="adm-panel-body">
	<div>
	<h3>Features and services versions</h3>
		<?php foreach(get_version_listall($pdo) as $current_version) { ?>
			<div>
				<h4><?=$current_version["soft"]?></h4>
				<table class="version-item">
					<tr>
						<td>Version</td>
						<td><?=$current_version['version']?></td>
					</tr>
					<tr>
						<td>Updated on</td>
						<td><?php if (isset($current_version['updated_on'])) { echo $current_version['updated_on']; } ?></td>
					</tr>
				</table>
			</div>
		<?php } ?>

		Database version needed: <?= GESTEX_DB_VERSION ?>
	</div>
	<content>
		<h3>Logs</h3>
	</content>
</div>
