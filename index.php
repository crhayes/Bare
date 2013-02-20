<?php

// Useful constants
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(dirname(__FILE__)).DS);
define('BASE_PATH', substr(ROOT_DIR, strlen($_SERVER['DOCUMENT_ROOT'])));

// Load necessary files
require_once ROOT_DIR.'config.php';
require_once ROOT_DIR.'libraries/vendor/autoload.php';
require_once ROOT_DIR.'libraries/helpers.php';

// Create new HTTP Request and Response objects to handle the response
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
$request = Request::createFromGlobals();
$response = new Response();

// Get the request path
$path = $request->getPathInfo();
if ($path != '/') {
	$path = ltrim($path, '/');
}

ob_start();

include ROOT_DIR.DS.'before.php';

/**
 * If a route for the path exists we include that file. If not, we
 * check if a file with the same name as the route exists and 
 * attempy to include that. If that fails we throw a 404.
 */
if (isset($routes[$path])) {
    include str_append(VIEW_PATH, '/').$routes[$path];
} elseif (($file = str_append(VIEW_PATH, '/').$path.'.php') && file_exists($file)) {
	include $file;
} else {
	include str_append(VIEW_PATH, '/').$routes[404];
    $response->setStatusCode(404);
}

include ROOT_DIR.'after.php';

$response->setContent(ob_get_clean());
$response->send();