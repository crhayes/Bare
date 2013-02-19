<?php

// Load necessary files
require 'config.php';
require_once ROOT_DIR.DS.'libraries/vendor/autoload.php';
require_once ROOT_DIR.DS.'libraries/helpers.php';

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

include ROOT_DIR.DS.'after.php';

$response->setContent(ob_get_clean());
$response->send();