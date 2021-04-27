<?php
error_reporting(0);
ini_set('display_errors', 0);

session_start();
require_once('configs/configs.php');


// Check if path is available or not empty
if (isset($_SERVER['PATH_INFO'])) {
	$path = $_SERVER['PATH_INFO'];

	// Do a path split
	$path_split = explode('/', trim($path));
} else {
	// Set Path to '/'
	$path_split = ['', 'index', 'index'];
}

// set corresponding controller, method and params
$req_controller = $path_split[1];
$req_model = $path_split[1];
$req_method = isset($path_split[2]) ? $path_split[2] : '';
$req_param = array_slice($path_split, 3);

$req_controller_exists = __DIR__ . '/src/controllers/' . ucfirst($req_controller) . '.php';

if (file_exists($req_controller_exists)) {
	require_once($req_controller_exists);
	$controller = ucfirst($req_controller);
	$ControllerObj = new $controller();

	$method = $req_method;

	if (method_exists($ControllerObj, $method)) {

		$data = $ControllerObj->$method($req_param);
		header('Content-Type: application/json');
		die(json_encode($data));

	} else {
		header('HTTP/1.1 404 Not Found');
		die('Invalid Request!');
	}
} else {
	header('HTTP/1.1 404 Not Found');
	die('Invalid Request!');
}
