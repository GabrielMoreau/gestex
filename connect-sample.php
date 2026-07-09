<?php if (!$web_page) exit() ?>

<?php
// connect-sample.php

// Do not modify directly this file
// Rename and adapt this file under the name connect.php

exit(); // Comment this line

// Informations de connexions a la base mySQL
define('GESTEX_DB_USER',     "gestex-service");
define('GESTEX_DB_PASSWORD', "gestex-magic-password");
define('GESTEX_DB_SERVER',   "localhost");
define('GESTEX_DB_DATABASE', "gestex");

// Information concernant l'annuaire LDAP
define('GESTEX_LDAP_URI',    "ldap://ldap.mondomaine.fr");
define('GESTEX_LDAP_PORT',   636);
define('GESTEX_LDAP_BASEDN', "ou=people,dc=mondomaine,dc=fr");
define('GESTEX_LDAP_BINDDN', "cn=reader,ou=services,dc=mondomaine,dc=fr");
define('GESTEX_LDAP_BINDPW', "...");

// Parametres generaux
define('GESTEX_ADMIN_MAIL',  "webmaster@your-entity.sample");
define('GESTEX_ENTITY_NAME', "YOUR ENTITY");
define('GESTEX_ENTITY_URL',  "http://www.your-entity.sample/");
define('GESTEX_ENTITY_LOGO', "your-entity.jpg");
?>
