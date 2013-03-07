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
| e.x.	load_library('database/database');
|       load_library('validation/validation');
|       load_library('session/session');
|       load_library('cookie/cookie');
|       load_library('upload/upload');
|       load_library('export/export');
|
*/

load_library('session/session');
load_library('database/database');
load_library('validation/validation');
load_class('user');

Session::start();
Database::connect($credentials);

// Redirect guests to the login screen
if (User::guest() && ! (request_path('login') || request_path('install'))) {
	redirect('login', 302);
}

// On logout end the session and redirect to login
if (request_path('logout')) {
	Session::delete('user');
	redirect('login', 302);
}