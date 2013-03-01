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

ob_start();
include_once ROOT_DIR.DS.'before.php';

/*
|--------------------------------------------------------------------------
| Determine Which View to Load
|--------------------------------------------------------------------------
|
| Here the requested file is determined.
|
| If a route for the path exists we include that file. If not, we
| check if a file with the same name as the route exists and 
| attempt to include that. If that fails we throw a 404.
|
*/
if (isset($routes[$path])) {
    $view = $routes[$path];
} elseif (file_exists(str_append(VIEW_PATH, '/').str_append($path, '.php'))) {
	$view = $path;
} else {
	$view = $routes[404];
    $GLOBALS['response']->setStatusCode(404);
}

/*
|--------------------------------------------------------------------------
| Create a New View
|--------------------------------------------------------------------------
|
| We use the built-in View class to create a new View instance.
|
| The View class enables us to use Template Inheritance to create
| views that manage their own content.
|
*/
load_library('view/view');
View::setViewPath(str_append(VIEW_PATH, '/'));
$view = new View($view);
$view->render();

include_once ROOT_DIR.'after.php';
$GLOBALS['response']->setContent(ob_get_clean());
$GLOBALS['response']->send();