<?php if (!$web_page) exit() ?>

<?php
require_once('db-functions.php');
require_once('base-functions.php');

// ---------------------------------------------------------------------

function paswd_old_hash($password) {
	return md5(filter_var($password, FILTER_SANITIZE_STRING));
}

// ---------------------------------------------------------------------

/*
	define('GESTEX_LDAP_URI',    'ldaps://ldap.mondomaine.fr');
	define('GESTEX_LDAP_PORT',   636);
	define('GESTEX_LDAP_BASEDN', 'ou=people,dc=mondomaine,dc=fr');
	define('GESTEX_LDAP_BINDDN', 'cn=reader,ou=services,dc=mondomaine,dc=fr');
	define('GESTEX_LDAP_BINDPW', '...');
*/

function ldap_authenticate($login, $password) {
	if ($password === '') {
		error_log('Error: empty password for user '.$login);
		return false;
	}

	$ldap = ldap_connect(GESTEX_LDAP_URI, GESTEX_LDAP_PORT);
	if (!$ldap) {
		error_log('Error: ldap connect '.GESTEX_LDAP_URI);
		return false;
	}
	ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
	// LDAP Bind
	if (!@ldap_bind($ldap, GESTEX_LDAP_BINDDN, GESTEX_LDAP_BINDPW)) {
		error_log('Error: ldap bind');
		return false;
	}

	$filter = sprintf(
		"(uid=%s)",
		ldap_escape($login, "", LDAP_ESCAPE_FILTER)
	);
	$search = ldap_search($ldap, GESTEX_LDAP_BASEDN, $filter);
	$entries = ldap_get_entries($ldap, $search);
	if ($entries["count"] != 1) {
		error_log('Error: ldap no one response entry '.$entries["count"].' for user '.$login);
		return false;
	}

	$dn = $entries[0]["dn"];

	// Check password
	error_log('Warn: LDAP DN = ' . $dn);
	if (!@ldap_bind($ldap, $dn, $password)) {
		error_log(sprintf(
			'Error: LDAP bind failed for %s: [%d] %s',
			$login,
			ldap_errno($ldap),
			ldap_error($ldap)
        ));
        // 'Error: ldap bad check password for user '.$login);
		return false;
	}

	$result = [
		'uid'        => $entries[0]['uid'][0] ?? '',
		'sn'         => $entries[0]['sn'][0] ?? '',
		'givenname'  => $entries[0]['givenname'][0] ?? '',
		'mail'       => $entries[0]['mail'][0] ?? '',
		'telephone'  => (int)($entries[0]['telephonenumber'][0] ?? 0),
	];
	ldap_unbind($ldap);
	return $result;
}

// ---------------------------------------------------------------------

/* authentication function: this is called by
   each page to ensure that there is an authenticated user
*/
function auth($reqlevel, $logged_user='', $password='') {

	// start/continue the session
	session_start();
	if (!empty($_SESSION['logged_user']))
		return true;

	$check = !empty($logged_user);

	if (empty($logged_user)) {
		// unset all the variables
		session_unset();
		// destroy the session
		session_destroy();
		return false;
	}

	$pdo = connect_db_minimal();
	$user = get_user_all_by_login($pdo, $logged_user);

	// test password
	$is_local = false;
	if (!empty($user) && $user['password'] !== 'ldap') {
		error_log('Warn: local account for user '.$logged_user);
		if (password_verify($password, $user['password'])) {
			error_log('Warn: local auth for user '.$logged_user);
			$is_local = true;
			if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
				// update hash in database
				$new_pwhash = password_hash($password, PASSWORD_DEFAULT);
				set_user_password_by_id($pdo, $user['id'], $new_pwhash);
			}
		} elseif ($user['password'] === paswd_old_hash($password)) {
			error_log('Warn: local auth for user '.$logged_user);
			$is_local = true;
			// update hash in database
			$new_pwhash = password_hash($password, PASSWORD_DEFAULT);
			set_user_password_by_id($pdo, $user['id'], $new_pwhash);
		}
	}

	if (!$is_local) {
		$ldap_user = ldap_authenticate($logged_user, $password);
		if ($ldap_user === false) {
			error_log('Error: no ldap auth for user '.$logged_user);
			return false;
		}
		error_log('Warn: ldap auth for user '.$logged_user);
		// check user in database or create it
		if (empty($user)) {
			set_user_new($pdo,
				$ldap_user['sn'],        /* familyname */
				$ldap_user['givenname'], /* firstname */
				$ldap_user['uid'],       /* login */
				'ldap',                  /* password */
				$ldap_user['mail'],      /* email */
				4,                       /* level */
				$ldap_user['telephone'], /* tel */
				0,                       /* team_id */
				'clair',                 /* theme */
			);
			$user = get_user_all_by_login($pdo, $logged_user);
		}
	}
	if ($reqlevel > $user['level']) {
		// pas le niveau d'autorisation requis
		return false;
	}
	
	// tout ok
	// down the level for disable user
	$level = $user['level'];
	if ($user['valid'] == 0 && $level > 1)
		$level = 1;
	// set session variables
	$_SESSION['logged_id']    = $user['id'];
	$_SESSION['logged_user']  = $logged_user;
	$_SESSION['logged_level'] = $level;
	$_SESSION['logged_theme'] = theme($user['theme']);
	return true;
}

// ---------------------------------------------------------------------

function logout() {
	// continue the session
	session_start();
	// unset all the variables
	session_unset();
	// destroy the session
	session_destroy();
}

// ---------------------------------------------------------------------

function level($reqlevel) {
	$level = $_SESSION['logged_level'];
	if ($reqlevel > $level)
		return false;
	return true;
}

// ---------------------------------------------------------------------

function level_or_alert($reqlevel, $message_alert='') {
	if (level($reqlevel))
		return true;

	$web_page = true;
	include_once('include/alert-auth.php');
	exit();
}

// ---------------------------------------------------------------------

function auth_or_login($referer='index.php') {
	// start or continue the session
	session_start();

	if (!empty($_SESSION['logged_user']))
		return true;

	$url = $referer;
	if (!empty($_SERVER['QUERY_STRING']))
		$url .= '?' . $_SERVER['QUERY_STRING'];

	Header('Location: login.php?referer='.urlencode($url));
	exit();
}


?>
