<?php

/*
|--------------------------------------------------------------------------
| Required Application Files
|--------------------------------------------------------------------------
|
| These files are required by the application.
|
| These files include the application configuration, the composer 
| autoloader (for autoloading some of the libraries used by Base) and
| some useful helper functions.
|
*/
require_once ROOT_DIR.'config.php';
require_once LIB_PATH.'vendor/autoload.php';
require_once LIB_PATH.'helpers.php';

/*
|--------------------------------------------------------------------------
| Request and Response Objects
|--------------------------------------------------------------------------
|
| Create new HTTP Request and Response objects to handle the response.
|
*/
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
$GLOBALS['request'] = Request::createFromGlobals();
$GLOBALS['response'] = new Response();

// Get the request path
$path = $GLOBALS['request']->getPathInfo();
$GLOBALS['requestPath'] = ($path != '/') ? ltrim($path, '/') : $path;

/*
|--------------------------------------------------------------------------
| Include Requested Files & Send Response
|--------------------------------------------------------------------------
|
| Here the requested file is loaded.
|
| If a route for the path exists we include that file. If not, we
| check if a file with the same name as the route exists and 
| attempt to include that. If that fails we throw a 404.
|
| Before.php is loaded before the requested
| file, and after.php after the requested file, which allows us to 
| easily execute code required on every request.
|
*/
ob_start();

include_once ROOT_DIR.DS.'before.php';

if (isset($routes[$path])) {
    include_once str_append(VIEW_PATH, '/').$routes[$path];
} elseif (($file = str_append(VIEW_PATH, '/').$path.'.php') && file_exists($file)) {
	include_once $file;
} else {
	include_once str_append(VIEW_PATH, '/').$routes[404];
    $GLOBALS['response']->setStatusCode(404);
}

include_once ROOT_DIR.'after.php';

$GLOBALS['response']->setContent(ob_get_clean());
$GLOBALS['response']->send();