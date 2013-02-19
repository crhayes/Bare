<?php

class SessionNative extends Session implements SessionDriver
{	
	/**
	 * Encrypt the data that is to be stored in the session and then create the session.
	 * 
	 * @param  string 	$key
	 * @param  mixed 	$value
	 * @return void
	 */
	public function set($key, $value)
	{
		$_SESSION[$key] = $this->aes->encrypt(serialize($value));
	}
	
	/**
	 * Decrypt the data from the session and return it.
	 *
	 * @param  string 	$key
	 * @return mixed
	 */
	public function get($key)
	{		
		return unserialize($this->aes->decrypt($_SESSION[$key]));
	}
	
	/**
	 * Delete a session variable.
	 * 
	 * @param  string 	$key
	 * @return void
	 */
	public function delete($key)
	{
		unset($_SESSION[$key]);	
	}
}