<?php

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
|
| This file includes several helper functions for often used tasks.
|
*/

/**
 * Helper to convert special characters to HTML entities
 * and echo the result.
 * 
 * @param  mixed 	$message
 * @param  boolean 	$return
 * @return mixed
 */
function e($value, $return = false)
{
	if (($value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8')) && $return) {
		return $value;
	}

	echo $value;
}

/**
 * Get an item from an array. If the item doesn't exist
 * return a default value.
 * 
 * @param  string 	$key
 * @param  array 	$array
 * @param  mixed 	$default
 * @return mixed
 */
function array_get($key, $array, $default = null)
{
	if (array_key_exists($key, $array)) {
		return $array[$key];
	}

	return $default;
}

/**
 * Load a view.
 * 
 * @param  string 	$name
 * @return void
 */
function load_view($name)
{
	require str_append(VIEW_PATH, '/').str_append($name, '.php');
}

/**
 * Load a class.
 * 
 * @param  string 	$name
 * @return void
 */
function load_class($name)
{
	require str_append(CLASS_PATH, '/').str_append($name, '.php');
}

/**
 * Redirect to a URL.
 * 
 * @param  string 	$url
 * @param  int 		$responseCode
 * @return void
 */
function redirect($url, $responseCode = null)
{
	header("Location: $url", true, $responseCode);
	exit(1);
}

/**
 * Check if a path matches the current request path.
 * 
 * @param  string 	$path
 * @return boolean
 */
function request_path($path)
{
	if ($GLOBALS['requestPath'] == $path) {
		return true;
	}

	return false;
}

/**
 * Get a path to an asset relative to the root.
 * 
 * @param  string 	$path
 * @return mixed
 */
function asset($path, $return = false)
{
	if (($path = str_append(ASSET_PATH, '/').$path) && $return) {
		return $path;
	}

	echo $path;
}

/**
 * Get a path to a url relative to the root.
 * 
 * @param  string 	$path
 * @return mixed
 */
function url($path, $return = false)
{
	if (($path = str_append(BASE_PATH, '/').$path) && $return) {
		return $path;
	}

	echo $path;
}

/**
 * Create a SHA-256 hash of a value with an added salt.
 * 
 * @param  string 	$value
 * @param  string 	$salt
 * @return string
 */
function make_hash($value, $salt = null)
{
	if ($salt === null) {
		$salt = substr(md5(uniqid(rand(), true)), 0, 12);
	} else {
		$salt = substr($salt, 0, 12);
	}
 
	return hash('sha256', ($salt.$value));
}


/**
 * Dump the given value and kill the script.
 *
 * @param  mixed  $value
 * @return void
 */
 function dd($value)
 {
 	die(var_dump($value));
 }

 /**
 * Determine if a given string begins with a given value.
 *
 * @param  string  $haystack
 * @param  string  $needle
 * @return bool
 */
function str_starts_with($haystack, $needle)
{
	return strpos($haystack, $needle) === 0;
}

/**
 * Determine if a given string ends with a given value.
 *
 * @param  string  $haystack
 * @param  string  $needle
 * @return bool
 */
function str_ends_with($haystack, $needle)
{
	return $needle == substr($haystack, strlen($haystack) - strlen($needle));
}

/**
 * Determine if a given string contains a given sub-string.
 *
 * @param  string        $haystack
 * @param  string|array  $needle
 * @return bool
 */
function str_contains($haystack, $needle)
{
	foreach ((array) $needle as $n)
	{
		if (strpos($haystack, $n) !== false) return true;
	}

	return false;
}

/**
 * value a string with a single instance of the given string.
 *
 * @param  string  $value
 * @param  string  $cap
 * @return string
 */
function str_append($value, $cap)
{
	return rtrim($value, $cap).$cap;
}

/**
 * Cap a string with a single instance of the given string.
 *
 * @param  string  $value
 * @param  string  $cap
 * @return string
 */
function str_prepend($value, $cap)
{
	return $cap.ltrim($value, $cap);
}