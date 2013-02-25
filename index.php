<?php

/*
|--------------------------------------------------------------------------
| App Constants
|--------------------------------------------------------------------------
|
| These constants are used throughout the application.
|
*/
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(dirname(__FILE__)).DS);
define('BASE_PATH', substr(ROOT_DIR, strlen($_SERVER['DOCUMENT_ROOT'])));

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
|
| Get the application bootstrap, which takes care of loading the 
| configuration settings
|
*/
require_once ROOT_DIR.'bootstrap.php';