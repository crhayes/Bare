<?php

/*
|--------------------------------------------------------------------------
| App Constants
|--------------------------------------------------------------------------
|
| Define some application constants for ease-of-use.
|
*/
define('VIEW_PATH', ROOT_DIR.'views/');
define('UPLOAD_PATH', ROOT_DIR.'assets/uploads/');

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| Routes provide a simple way to match a request URI to a PHP file.
|
| Each route is an array item where the key is the URI without the 
| preceding '/' (although the index route is defined by '/') and the 
| value is the PHP file it maps to.
|
*/
$routes = array(
	'/' => 'home.php',
	'404' => '404.php',
);

/*
|--------------------------------------------------------------------------
| Database Credentials
|--------------------------------------------------------------------------
|
| These credentials are later used to connect to the database.
|
| i.e. Database::connect($credentials);
|
*/
$credentials = array(
	'host' 	   => 'localhost',
	'username' => '',
	'password' => '',
	'database' => ''
);