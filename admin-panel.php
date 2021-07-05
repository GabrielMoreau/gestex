<?php
// category-list.php
$web_page = true;

// Authenticate
require_once('module/auth-functions.php');
require_once('module/html-functions.php');
require_once('module/base-functions.php');

session_start();
if (empty($_SESSION['logged_user'])) {
	$logged_level = 0;
} else {
	$logged_id    = $_SESSION['logged_id'];
	$logged_user  = strtolower($_SESSION['logged_user']);
	$logged_level = $_SESSION['logged_level'];
}

en_tete('Console Administrateur');

if ($logged_level < 4) {
    echo 'Permission denied !';
    exit();
}

$pdo = connect_db();
?>

<div class="adm-panel-body">
	<div>
	<h3>FEATURES AND SERVICES VERSIONS</h3><?php 
		foreach(get_version_listall($pdo) as $current_version) { ?>
			<div>
				<h4><?=$current_version["soft"]?></h4>
				<table class="version-item">
					<tr>
						<td>Version</td>
						<td><a>v<?=$current_version["version"]?></a></td>
					</tr>
					<tr>
						<td>Updated on</td>
						<td><a><?=$current_version["updated_on"]?></a></td>
					</tr>
				</table>
			</div><?php
		} ?>
	</div>
	<content>
		<h3>LOGS</h3>
	</content>
</div>