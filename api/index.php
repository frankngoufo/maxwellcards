<?php
/*
API ROUTER

This file is used to route all requests to the API.
Map request endpoint keywords to existing files in the system
*/

header('Access-Control-Allow-Origin: *');
if (empty($_GET['x-www-form-urlencoded'])) { // For access through iFrames
	header('Content-type: application/json');
}

$requests = array(
	'register' 				=> 'register.php',
	'login' 				=> 'login.php',
	);

require_once($_SERVER['DOCUMENT_ROOT'].'/sitewide-page.inc.php');

if (isset($_REQUEST['apiKey']) && $_REQUEST['apiKey'] === API_KEY) {
	if (isset($_REQUEST['rPath']) && array_key_exists($_REQUEST['rPath'], $requests)) {
		define('API', TRUE);
		require_once( PATH_ROOT.$requests[$_REQUEST['rPath']] );
	}
}