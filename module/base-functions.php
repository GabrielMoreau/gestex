<?php

// ---------------------------------------------------------------------

function param_post($string, $default = '') {
	if (!empty($_POST[$string]))
		return filter_var($_POST[$string], FILTER_SANITIZE_STRING);
	return $default;
}

// ---------------------------------------------------------------------

function param_get($string, $default = '') {
	if (!empty($_GET[$string]))
		return filter_var($_GET[$string], FILTER_SANITIZE_STRING);
	return $default;
}

// ---------------------------------------------------------------------

function param_post_or_get($string, $default = '') {
	if (!empty($_POST[$string]))
		return filter_var($_POST[$string], FILTER_SANITIZE_STRING);
	if (!empty($_GET[$string]))
		return filter_var($_GET[$string], FILTER_SANITIZE_STRING);
	return $default;
}

// ---------------------------------------------------------------------

function string_to_filename_snake($string) {
	# $string = strtolower($string);
	$string = str_replace(' ', '_', $string);
	$string = str_replace('à', 'a', $string);
	$string = str_replace('â', 'a', $string);
	$string = str_replace('é', 'e', $string);
	$string = str_replace('è', 'e', $string);
	$string = str_replace('ê', 'e', $string);
	$string = str_replace('ë', 'e', $string);
	$string = str_replace('î', 'i', $string);
	$string = str_replace('ï', 'i', $string);
	$string = str_replace('ô', 'o', $string);
	$string = str_replace('oe', 'oe', $string);
	$string = str_replace('û', 'u', $string);
	$string = str_replace('ù', 'u', $string);
	$string = str_replace('ç', 'c', $string);
	$string = preg_replace('/[^A-Za-z0-9-_]/', '_', $string);
	$string = preg_replace('/__+/', '_', $string);
	return $string;
}

// ---------------------------------------------------------------------

function string_to_filename_kebab($string) {
	$string = strtolower($string);
	$string = preg_replace('/[ÀÂÄàâä]/',    'a', $string);
	$string = preg_replace('/[ÉÈÊËéèêë]/',  'e', $string);
	$string = preg_replace('/[ÎÏîï]/',      'i', $string);
	$string = preg_replace('/[ÔÖôö]/',      'o', $string);
	$string = preg_replace('/[ÙÛÜùûü]/',    'u', $string);
	$string = preg_replace('/[Çç]/',        'c', $string);
	#$string = preg_replace('/[OEoe]/',       'oe', $string);
	$string = preg_replace('/[^a-z0-9-]/',  '-', $string);
	$string = preg_replace('/-+/',          '-', $string);
	return $string;
}

// ---------------------------------------------------------------------

function random_string($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$characters_length = strlen($characters);
	$random_string = '';
	for ($i = 0; $i < $length; $i++) {
		$random_string .= $characters[rand(0, $characters_length - 1)];
	}
	return $random_string;
}

// ---------------------------------------------------------------------

function theme($theme) {
	if (empty($theme) or $theme === 'random') {
		switch (rand(1, 3)) {
			case 1:
				$theme = 'clair';
				break;
			case 2:
				$theme = 'sombre';
				break;
			case 3:
				$theme = 'solarizeddark';
				break;
		}
	}
	return $theme;
}

// ---------------------------------------------------------------------

?>
