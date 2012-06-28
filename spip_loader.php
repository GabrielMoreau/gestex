<?php

	#
	# SPIP_LOADER recupere et installe la version stable de SPIP
	#

	# code de reinstallation
	// definir _FILE_CONNECT a autre chose que machin.php si on veut pas
	if (@file_exists('ecrire/inc_version.php')) {
		include_once 'ecrire/inc_version.php';
		if (defined('_FILE_CONNECT')
		&& _FILE_CONNECT && strpos(_FILE_CONNECT, '.php'))
			spip_loader_reinstalle();
	}

	######################### CONFIGURATION #
	#
	# decommenter la ligne ci-dessous pour charger la version
	# de developpement (nightly build SVN) et commenter la ligne de
	# telechargement de la version STABLE
	# define('_URL_PAQUET_ZIP','http://files.spip.org/spip/spip.zip');

	# URL du paquet de la version STABLE a telecharger
	define('_URL_PAQUET_ZIP','http://www.spip.net/spip-dev/DISTRIB/spip.zip');

	# Adresse des librairies necessaires a spip_loader
	# (pclzip et fichiers de langue)
	define('_URL_LOADER_DL',"http://www.spip.net/spip-dev/INSTALL/");
	# telecharger a travers un proxy
	define('_URL_LOADER_PROXY', '');

	# auteur(s) autorise(s) a proceder aux mises a jour : '1:2:3'
	define('_SPIP_LOADER_UPDATE_AUTEURS', '1');

	# surcharger le script
	define('_NOM_PAQUET_ZIP','spip');
	// par defaut le morceau de path a enlever est le nom : spip
	define('_REMOVE_PATH_ZIP', _NOM_PAQUET_ZIP);
	define('_SPIP_LOADER_URL_RETOUR', "ecrire/");
	define('_SPIP_LOADER_SCRIPT', "spip_loader.php");

	// "habillage" optionnel
	// liste separee par virgules de fichiers inclus dans spip_loader
	// charges a la racine comme spip_loader.php et pclzip.php
	// selon l'extension: include .php , .css et .js dans le <head> genere par spip_loader
	define('_SPIP_LOADER_EXTRA', '');

	define('_DEST_PAQUET_ZIP','');
	define('_PCL_ZIP_SIZE', 249587);
	#
	#######################################################################

	# langues disponibles
	$langues = array (
		'ar' => '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;',
		'br' => 'brezhoneg',
		'ca' => 'catal&agrave;',
		'cs' => '&#269;e&#353;tina',
		'de' => 'Deutsch',
		'en' => 'English',
		'eo' => 'Esperanto',
		'es' => 'espa&ntilde;ol',
		'fr' => 'fran&ccedil;ais',
		'gl' => 'galego',
		'id' => 'Indonesia',
		'it' => 'italiano',
		'lb' => 'L&euml;tzebuergesch',
		'oc_lnc' => '&ograve;c lengadocian',
		'pt_br' => 'Portugu&#234;s do Brasil',
		'ro' => 'rom&#226;n&#259;',
		'tr' => 'T&#252;rk&#231;e'
	);

	//
	// Traduction des textes de SPIP
	//
	function _TT($code, $args=array()) {
		global $lang;
		$code = str_replace('tradloader:', '', $code);
		$text = $GLOBALS['i18n_tradloader_'.$lang][$code];
		while (list($name, $value) = @each($args))
			$text = str_replace ("@$name@", $value, $text);
		return $text;
	}

	//
	// Ecrire un fichier de maniere un peu sure
	//
	function ecrire_fichierT ($fichier, $contenu) {

		$fp = @fopen($fichier, 'wb');
		$s = @fputs($fp, $contenu, $a = strlen($contenu));

		$ok = ($s == $a);

		@fclose($fp);

		if (!$ok) {
			@unlink($fichier);
		}

		return $ok;
	}

	function regler_langue_navigateurT() {
		$accept_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		if (is_array($accept_langs)) {
			foreach($accept_langs as $s) {
				if (eregi('^([a-z]{2,3})(-[a-z]{2,3})?(;q=[0-9.]+)?$', trim($s), $r)) {
					$lang = strtolower($r[1]);
					if (isset($GLOBALS['langues'][$lang])) return $lang;
				}
			}
		}
		return false;
	}

	function menu_languesT($lang) {
		global $dir_base;
		$r = '<div style="float:'.$GLOBALS['spip_lang_right'].';">';
		$r .= '<form action="'.$dir_base._SPIP_LOADER_SCRIPT.'" method="get"><div>';
		if(preg_match(',action=([a-z_]+),', _SPIP_LOADER_SCRIPT, $m)) {
			$r .= "<input type='hidden' name='action' value='".$m[1]."' />";
			$sep = '&amp;';
		}
		else
			$sep = '?';
		$r .= '<select name="lang"
			onchange="window.location=\''.$dir_base._SPIP_LOADER_SCRIPT.$sep.'lang=\'+this.value;">';
		
		foreach ($GLOBALS['langues'] as $l => $nom)
			$r .= '<option value="'.$l.'"' . ($l == $lang ? ' selected="selected"' : '')
				. '>'.$nom."</option>\n";
		$r .= '</select> <noscript><div><input type="submit" name="ok" value="ok" /></div></noscript></div></form>';
		$r .= '</div>';
		return $r;
	}


	//
	// Gestion des droits d'acces
	//
	function tester_repertoire() {
		global $chmod;
		
		$ok = false;
		$self = basename($_SERVER['PHP_SELF']);
		$uid = @fileowner('.');
		$uid2 = @fileowner($self);
		$gid = @filegroup('.');
		$gid2 = @filegroup($self);
		$perms = @fileperms($self);

		// Comparer l'appartenance d'un fichier cree par PHP
		// avec celle du script et du repertoire courant
		@rmdir('test');
		@unlink('test'); // effacer au cas ou
		@touch('test');
		if ($uid > 0 && $uid == $uid2 && @fileowner('test') == $uid)
			$chmod = 0700;
		else if ($gid > 0 && $gid == $gid2 && @filegroup('test') == $gid)
			$chmod = 0770;
		else
			$chmod = 0777;
		// Appliquer de plus les droits d'acces du script
		if ($perms > 0) {
			$perms = ($perms & 0777) | (($perms & 0444) >> 2);
			$chmod |= $perms;
		}
		@unlink('test');

		// Verifier que les valeurs sont correctes

		@mkdir('test', $chmod);
		@chmod('test', $chmod);
		$f = @fopen('test/test.php', 'w');
		if ($f) {
			@fputs($f, '<'.'?php $ok = true; ?'.'>');
			@fclose($f);
			@chmod('test/test.php', $chmod);
			include('test/test.php');
		}
		@unlink('test/test.php');
		@rmdir('test');

		return $ok;
	}

	//
	// Demarre une transaction HTTP (s'arrete a la fin des entetes)
	// retourne un descripteur de fichier
	//
	function init_http($get, $url, $refuse_gz=false) {
		//global $http_proxy;
		$fopen = false;
		if (!eregi("^http://", _URL_LOADER_PROXY))
			$http_proxy = '';
		else
			$http_proxy = _URL_LOADER_PROXY;

		$t = @parse_url($url);
		$host = $t['host'];
		if ($t['scheme'] == 'http') {
			$scheme = 'http'; $scheme_fsock='';
		} else {
			$scheme = $t['scheme']; $scheme_fsock=$scheme.'://';
		}
		if (!isset($t['port']) OR !($port = $t['port'])) $port = 80;
		$query = isset($t['query'])?$t['query']:"";
		if (!isset($t['path']) OR !($path = $t['path'])) $path = "/";

		if ($http_proxy) {
			$t2 = @parse_url($http_proxy);
			$proxy_host = $t2['host'];
			$proxy_user = $t2['user'];
			$proxy_pass = $t2['pass'];
			if (!($proxy_port = $t2['port'])) $proxy_port = 80;
			$f = @fsockopen($proxy_host, $proxy_port);
		} else
			$f = @fsockopen($scheme_fsock.$host, $port);

		if ($f) {
			if ($http_proxy)
				fputs($f, "$get $scheme://$host" . (($port != 80) ? ":$port" : "") . $path . ($query ? "?$query" : "") . " HTTP/1.0\r\n");
			else
				fputs($f, "$get $path" . ($query ? "?$query" : "") . " HTTP/1.0\r\n");

			$version_affichee = isset($GLOBALS['spip_version_affichee'])?$GLOBALS['spip_version_affichee']:"xx";
			fputs($f, "Host: $host\r\n");
			fputs($f, "User-Agent: SPIP-$version_affichee (http://www.spip.net/)\r\n");

			// Proxy authentifiant
			if (isset($proxy_user) AND $proxy_user) {
				fputs($f, "Proxy-Authorization: Basic "
				. base64_encode($proxy_user . ":" . $proxy_pass) . "\r\n");
			}

		}
		// fallback : fopen
		else if (!$http_proxy) {
			$f = @fopen($url, "rb");
			$fopen = true;
		}
		// echec total
		else {
			$f = false;
		}

		return array($f, $fopen);
	}

	//
	// Recupere une page sur le net
	// et au besoin l'encode dans le charset local
	//
	// options : get_headers si on veut recuperer les entetes
	function recuperer_page($url) {

		// Accepter les URLs au format feed:// ou qui ont oublie le http://
		$url = preg_replace(',^feed://,i', 'http://', $url);
		if (!preg_match(',^[a-z]+://,i', $url)) $url = 'http://'.$url;

		for ($i=0;$i<10;$i++) {	// dix tentatives maximum en cas d'entetes 301...
			list($f, $fopen) = init_http('GET', $url);

			// si on a utilise fopen() - passer a la suite
			if ($fopen) {
				break;
			} else {
				// Fin des entetes envoyees par SPIP
				fputs($f,"\r\n");

				// Reponse du serveur distant
				$s = trim(fgets($f, 16384));
				if (ereg('^HTTP/[0-9]+\.[0-9]+ ([0-9]+)', $s, $r)) {
					$status = $r[1];
				}
				else return;

				// Entetes HTTP de la page
				$headers = '';
				while ($s = trim(fgets($f, 16384))) {
					$headers .= $s."\n";
					if (eregi('^Location: (.*)', $s, $r)) {
						$location = $r[1];
					}
					if (preg_match(",^Content-Encoding: .*gzip,i", $s))
						$gz = true;
				}
				if ($status >= 300 AND $status < 400 AND $location)
					$url = $location;
				else if ($status != 200)
					return;
				else
					break; # ici on est content
				fclose($f);
				$f = false;
			}
		}

		// Contenu de la page
		if (!$f) {
			return false;
		}

		$result = '';
		while (!feof($f))
			$result .= fread($f, 16384);
		fclose($f);

		// Decompresser le flux
		if ($gz = $_GET['gz'])
			$result = gzinflate(substr($result,10));

		return $result;
	}

	function telecharger_langue($lang, $droits) {
		global $dir_base;
		$fichier = 'tradloader_'.$lang.'.php';
		$GLOBALS['idx_lang'] = 'i18n_tradloader_'.$lang;
		if(!file_exists($dir_base.$fichier)) {
			$contenu = recuperer_page(_URL_LOADER_DL.$fichier.".txt");
			if ($contenu AND $droits) {
				ecrire_fichierT($dir_base.$fichier, $contenu);
				include($dir_base.$fichier);
				return true;
			} elseif($contenu AND !$droits) {
				eval('?'.'>'.$contenu);
				return true;
			} else {
				return false;
			}
		} else {
			include($dir_base.$fichier);
			return true;
		}
	}

	function selectionner_langue($droits) {
		global $langues; # langues dispo

		if (isset($_COOKIE['spip_lang_ecrire'])) {
			$lang = $_COOKIE['spip_lang_ecrire'];
		}

		if (isset($_GET['lang']))
			$lang = $_GET['lang'];

		# reglage par defaut selon les preferences du brouteur
		if (!$lang OR !isset($langues[$lang]))
			$lang = regler_langue_navigateurT();

		# valeur par defaut
		if (!isset($langues[$lang])) $lang = 'fr';

		# memoriser dans un cookie pour l'etape d'apres *et* pour l'install
		setcookie('spip_lang_ecrire', $lang);

		# RTL
		if ($lang == 'ar' OR $lang == 'he' OR $lang == 'fa') {
			$GLOBALS['spip_lang_right']='left';
			$GLOBALS['spip_lang_dir']='rtl';
		} else {
			$GLOBALS['spip_lang_right']='right';
			$GLOBALS['spip_lang_dir']='ltr';
		}

		# code de retour = capacite a telecharger le fichier de langue
		$GLOBALS['idx_lang'] = 'i18n_tradloader_'.$lang;
		return telecharger_langue($lang,$droits) ? $lang : false;
	}

	function debut_html() {
		$titre = _TT('tradloader:titre', array('paquet'=>strtoupper(_NOM_PAQUET_ZIP)));
		$css = $js = '';
		foreach (explode(',', _SPIP_LOADER_EXTRA) as $fil) {
			switch (strrchr($fil, '.')) {
				case '.css':
					$css .= '
		<!-- css pour tuning optionnel, au premier chargement, il manquera si pas droits ... -->
		<link rel="stylesheet" href="' . basename($fil) . '" type="text/css" media="all" />';
					break;
				case '.js':
					$js .= '
		<!-- js pour tuning optionnel, au premier chargement, il manquera... -->
		<script src="' . basename($fil) . '" type="text/javascript"></script>';
					break;
			}
		}
		$h1 = _TT('tradloader:titre', array('paquet'=>strtoupper(_NOM_PAQUET_ZIP)));
		$menu_langues = menu_languesT($GLOBALS['lang']);

		echo <<<EOD
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
		<html "xml:lang='{$GLOBALS['lang']}' dir='{$GLOBALS['spip_lang_dir']}'">
		<head>
		<title>$titre</title>
		<meta http-equiv="Expires" content="0" />
		<meta http-equiv="cache-control" content="no-cache,no-store" />
		<meta http-equiv="pragma" content="no-cache" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			body {
				background-color:white;
				color:black;
				margin:50px 0 0 0;
			}
			#main {
				margin-left: auto;
				margin-right: auto;
				width:450px;
			}
			a {
				text-decoration: none;
				color: #E86519;
			}
			a:hover {
				color:#FF9900;
				text-decoration: underline;
			}
			a:visited {
				color:#6E003A;
			}
			a:active {
				color:#FF9900;
			}
			h1 {
				font-family:Verdana ,Arial,Helvetica,sans-serif;
				color:#970038;
				display:inline;
				font-size:120%;
			}
			h2 {
				font-family: Verdana,Arial,Sans,sans-serif;
				font-weigth: normal;
				font-size: 100%;
			}
		</style>$css$js
		</head>
		<body>
		<div id="main">
		$menu_langues

		<h1>$h1</h1>
		<div style="font-family:Georgia,Garamond,Times,serif; font-size:110%;">
EOD;
	}
	function fin_html()
	{
		global $taux;
		echo ($taux ? '
		<div id="taux" style="display: block;">' . $taux . '</div>' : '') .
		'
		</div>
		</div>
		</body>

		</html>
		';
	}

	function nettoyer_racine($fichier) {
		global $dir_base;
		@unlink($dir_base.$fichier);
		@unlink($dir_base.'pclzip.php');
		$d = opendir($dir_base);
		while (false !== ($f = readdir($d))) {
			if(preg_match('/^tradloader_(.+).php$/', $f)) @unlink($dir_base.$f);
		}
		closedir($d);
		return true;
	}
	// un essai pour parer le probleme incomprehensible des fichiers pourris
	function touchCallBack($p_event, &$p_header)
	{
		// bien extrait ?
		if ($p_header['status'] == 'ok') {
		    // allez, on touche le fichier
		    touch($p_header['filename']);
		}
		return 1;
	}
	function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}

///////////////////////////////////////////////
// debut du process
//

	error_reporting(E_ALL ^ E_NOTICE);

	$dir_base = './'; //repertoire d'installation
	$taux = 0; // calcul eventuel du taux de transfert+dezippage

	$droits = tester_repertoire();

	if ($lang = selectionner_langue($droits)) {
		if(!$droits) {
			//on ne peut pas ecrire
			debut_html();
			echo _TT('tradloader:texte_preliminaire', array('paquet'=>strtoupper(_NOM_PAQUET_ZIP), 'chmod'=>sprintf('%04o',$chmod)));
			fin_html();
			exit;
		}
		else {
			//on telecharge, on ecrit, au fait, on peut dezipper ?
			//
			// Verifier si la ZLib est utilisable
			//
			$gz = function_exists("gzopen");
			if ($gz) {
				if(!file_exists($f = $dir_base . 'pclzip.php')) {
					$taux = microtime_float();
					$contenu = recuperer_page(_URL_LOADER_DL . 'pclzip.php.txt');
					if ($contenu) {
						ecrire_fichierT($f, $contenu);
					}
					$taux = _PCL_ZIP_SIZE / (microtime_float() - $taux);
				}
				include $f;
				$necessaire = array();
				foreach (explode(',', _SPIP_LOADER_EXTRA) as $fil) {
					$necessaire[$fil] = strrchr($fil, '.') == '.php' ? '.txt' : '';
				}
				foreach ($necessaire as $fil=>$php) {
					if(!file_exists($f = $dir_base . basename($fil))) {
						$contenu = recuperer_page(_URL_LOADER_DL . $fil . $php);
						if ($contenu) {
							ecrire_fichierT($f, $contenu);
						}
					}
					if ($php){
						include $f;
					}
				}
			}
			else
				die ('fonctions zip non disponibles');

			$fichier = basename(_URL_PAQUET_ZIP);
			$paquet = (isset($_GET['paquet']) AND preg_match(',[a-zA-Z0-9_]+,', $_GET['paquet'])) ? $_GET['paquet'] : '';

			//
			// deploiement de l'archive
			//
			if ($_GET['fichier'] == 'oui'
			AND file_exists($dir_base.$fichier)) {
				$zip = new PclZip($dir_base.$fichier);
				$ok = $zip->extract(
					PCLZIP_OPT_PATH, $dir_base._DEST_PAQUET_ZIP,
					PCLZIP_OPT_SET_CHMOD, $chmod,
					PCLZIP_OPT_REPLACE_NEWER,
					PCLZIP_OPT_REMOVE_PATH, _REMOVE_PATH_ZIP."/",
					PCLZIP_CB_POST_EXTRACT, 'touchCallBack');
				if ($zip->error_code<0) {
					debut_html();
					echo _TT('tradloader:donnees_incorrectes',
						array('erreur' => $zip->errorInfo()));
					fin_html();
					exit;
				}
				nettoyer_racine($fichier);
				header("Location: ".$dir_base._SPIP_LOADER_URL_RETOUR);
				exit;
			}
			//
			// Si pas encore fait, afficher la page de presentation
			//
			if ($_GET['charger'] != 'oui') {
				debut_html();
				$dest = (_DEST_PAQUET_ZIP == '') ? 
					_TT('tradloader:ce_repertoire') :
					_TT('tradloader:du_repertoire').' <tt>'._DEST_PAQUET_ZIP.'</tt>';   
				echo _TT('tradloader:texte_intro', array('paquet'=>strtoupper(_NOM_PAQUET_ZIP),'dest'=> $dest));
				echo "<div style='text-align:".$GLOBALS['spip_lang_right']."'>";
				echo "<form action='".$dir_base._SPIP_LOADER_SCRIPT."' method='get'><div>";
				if(preg_match(',action=([a-z_]+),', _SPIP_LOADER_SCRIPT, $m))
					echo "<input type='hidden' name='action' value='".$m[1]."' />";
				if($paquet != '')
					echo "<input type='hidden' name='paquet' value='$paquet' />";
				if(file_exists($dir_base.$fichier))
					echo "<input type='hidden' name='fichier' value='oui' />";
				else
					echo "<input type='hidden' name='charger' value='oui'>";
				echo '<input type="submit" name="Valider" value="'._TT('tradloader:bouton_suivant').'" />';
				echo "</div></form>";

				fin_html();
				exit;
			}

			$contenu = recuperer_page(_URL_PAQUET_ZIP);

			if(!($contenu AND ecrire_fichierT($dir_base.$fichier, $contenu))) {
				debut_html();
				echo _TT('tradloader:echec_chargement');
				fin_html();
				exit;
			}

			// Passer a l'etape suivante (desarchivage)
			$sep = strpos(_SPIP_LOADER_SCRIPT, '?') ? '&' : '?';
			header("Location: ".$dir_base._SPIP_LOADER_SCRIPT.$sep."fichier=oui".($paquet?"&paquet=".$paquet:''));
			exit;
		}
	}
	else {
		//on ne peut pas telecharger, c'est foutu.
		$lang = 'fr'; //francais par defaut
		$GLOBALS['i18n_tradloader_fr']['titre'] = 'T&eacute;l&eacute;chargement de SPIP';
		$GLOBALS['i18n_tradloader_fr']['echec_chargement'] = '<h4>Le chargement a &eacute;chou&eacute;. Veuillez r&eacute;essayer, ou utiliser l\'installation manuelle.</h4>';
		debut_html();
		echo _TT('tradloader:echec_chargement');
		fin_html();
		exit;
	}

	function spip_loader_reinstalle() {
		if(!defined(_SPIP_LOADER_UPDATE_AUTEURS))
			define('_SPIP_LOADER_UPDATE_AUTEURS', '1');
		if ($GLOBALS['auteur_session']['statut'] != '0minirezo'
		OR !in_array($GLOBALS['auteur_session']['id_auteur'],
		explode(':', _SPIP_LOADER_UPDATE_AUTEURS))) {
			include_spip('inc/headers');
			include_spip('inc/minipres');
			http_status('403');
			install_debut_html();
			echo _T('ecrire:avis_non_acces_page');
			install_fin_html();
			exit;
		}
	}

?>
