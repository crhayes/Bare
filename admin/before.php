<?php

/*
|--------------------------------------------------------------------------
| Before
|--------------------------------------------------------------------------
|
| This file is included on each request before the requested file is 
| included. This provides a really simple way to handle code that is 
| common to every page, such as including libraries, connecting to a 
| database, handling authentication etc.
| 
| e.x.	require LIB_PATH.'database/database.php';
|       require LIB_PATH.'validation/validation.php';
|       require LIB_PATH.'session/session.php';
|       require LIB_PATH.'cookie/cookie.php';
|       require LIB_PATH.'upload/upload.php';
|       require LIB_PATH.'export/export.php';
|
*/

load_library('session/session.php');
load_library('database/database.php');
load_library('validation/validation.php');
load_class('user.php');

Session::start();
Database::connect($credentials);

// Redirect guests to the login screen
if (User::guest() && ! request_path('login')) {
	redirect('login', 302);
}

// On logout end the session and redirect to login
if (request_path('logout')) {
	Session::delete('user');
	redirect('login', 302);
}