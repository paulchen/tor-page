<?php

$default_lang = 'en';
if(!isset($_REQUEST['lang'])) {
	header("Location: /$default_lang/");
	die();
}
else {
	$lang = $_REQUEST['lang'];
}

$templates_dir = dirname(__FILE__) . '/../templates';

$dir = opendir("$templates_dir");
$languages = array();
while(($file = readdir($dir)) !== false) {
	if(preg_match('/^index_([a-z]+)\.php$/', $file, $matches)) {
		$languages[] = $matches[1];
	}
}
closedir($dir);

$language_links = array();
foreach($languages as $language) {
	$language_links[$language] = preg_replace("+/$lang/+", "/$language/", $_SERVER['REQUEST_URI'], 1);
}

switch($_REQUEST['request']) {
	case '':
		if(!file_exists("$templates_dir/index_$lang.php")) {
			header("Location: /$default_lang/");
			die();
		}

		require_once("$templates_dir/index.php");
		break;

	default:
		http_send_status(404);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://' . $_SERVER['SERVER_NAME'] . '/XXX');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($curl);
		$data = str_replace('/XXX', $_SERVER['REQUEST_URI'], $data);
		curl_close($curl);
		die($data);
}
