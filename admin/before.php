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

require_once LIB_PATH.'session/session.php';
require_once LIB_PATH.'database/database.php';
require_once LIB_PATH.'validation/validation.php';
require_once ROOT_DIR.'classes/user.php';

Session::start();
Database::connect($credentials);

if (User::guest() && ! request_path('login')) {
	redirect('login', 302);
}