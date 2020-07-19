<?php

require("db-functions.php");

/* authentication function: this is called by
   each page to ensure that there is an authenticated user
*/
function auth($reqlevel, $logged_in_user='', $password='') {

	// start/continue the session
	session_start();
	if (!empty($_SESSION['logged_in_user']))
		return true;

	$check = !empty($logged_in_user);

	if ($check) {
		$pdo = connect_db();
		$sql = 'SELECT password, id, level, valid FROM users WHERE loggin = ?;';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($logged_in_user));
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// is the password correct
		if ($user[0]['password'] != md5($password)) {
			// pas le bon ppasswd
			return 0; // false;
		} else if ($reqlevel > $user[0]['level']){
			// pas le niveau d'autorisation requis
			return 0;//false;
		} else { // tout ok
			// down the level for disable user
			$level = $user[0]['level'];
			if ($user[0]['valid'] == 0 && $level > 1)
				$level = 1;
			// set session variables
			$_SESSION['user_id'] = $user[0]['id'];
			$_SESSION['logged_in_user'] = $logged_in_user;
			$_SESSION['level'] = $level;
			return 1;
		}
	} else {
		// unset all the variables
		session_unset();

		// destroy the session
		session_destroy();

		return 0; ///false;
	}
}

////////////////////////////////////////////////////////////////////////////

function logout() {
	// continue the session
	session_start();
	// unset all the variables
	session_unset();
	// destroy the session
	session_destroy();
}

////////////////////////////////////////////////////////////////////////////

function level($reqlevel) {
	$level = $_SESSION['level'];
	if ($reqlevel > $level)
		return false;
	return true;
}

////////////////////////////////////////////////////////////////////////////

function level_or_alert($reqlevel, $msg_alert='') {
	if (level($reqlevel))
		return true;

	$web_page = true;
	include_once('include/alert.php');
	exit();
}

////////////////////////////////////////////////////////////////////////////

function auth_or_login($referer='index.php') {
	// start or continue the session
	session_start();

	if (!empty($_SESSION['logged_in_user']))
		return true;

	$url = $referer;
	if (!empty($_SERVER['QUERY_STRING']))
		$url .= '?' . $_SERVER['QUERY_STRING'];

	Header('Location: login.php?referer='.urlencode($url));
	exit();
}

?>
