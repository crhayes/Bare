<?php

/**
 * Cookie Utility. Provides a wrapper for PHP cookie handling that
 * provides session encryption.
 *
 * @author      Chris Hayes <chayes@okd.com, chris@chrishayes.ca>
 * @link        http://okd.com, http://chrishayes.ca
 * @copyright   (c) 2012 OKD, Chris Hayes
 */

class Cookie
{	
	/**
	 * Store an instance of the Session class.
	 * 
	 * @var Session
	 */
	private static $instance;

	/**
	 * Key to be used by the encryption library.
	 * 
	 * @var string
	 */
	private $cryptKey = 'okdcookieencryptionmanagement';
	
	/**
	 * Stored instance of the phpSec AES encruption library.
	 * 	
	 * @var Crypt_AES
	 */
	public $aes;
	
	/**
	 * Create a new instance of the Session class.
	 *
	 * The phpSec AES cryptography library is instantiated in order
	 * to allow us to encrypt the session data for extra security.
	 *
	 * @return void
	 */
	public function __construct()
	{		
		// Load the Cryptography class
		require_once(__DIR__.'/../vendor/crypt/AES.php');
		
		// Create a new AES cryptography object
		$this->aes = new Crypt_AES(); 
		
		// Set the encryption key
		$this->setKey($this->cryptKey);
	}

	/**
	 * Set the encryption key for the phpSec library.
	 * 
	 * @param string $key
	 */
	public function setKey($key)
	{	
		$this->aes->setKey($key);
	}

	/**
	 * Allow static interface for setting, getting, and
	 * deleting sessions.
	 * 
	 * @param  string 	$name
	 * @param  array 	$arguments
	 * @return mixed
	 */
	public static function __callStatic($name, $arguments)
	{
		switch($name) {
			case 'set':
				list($key, $value) = $arguments;
				self::$instance->set($key, $value);
				break;
			case 'get':
				list($key) = $arguments;
				return self::$instance->get($key);
			case 'delete':
				list($key) = $arguments;
				self::$instance->delete($key);
				break;
		}
	}
}