<?php

/**
 * Database Utility. Provides a wrapper around PHP's PDO extension to 
 * simplify database querying.
 *
 * Classes
 * -------
 * Database
 * DatabaseQuery
 *
 * @author      Chris Hayes <chayes@okd.com, chris@chrishayes.ca>
 * @link        http://okd.com, http://chrishayes.ca
 * @copyright   (c) 2012 OKD, Chris Hayes
 */

// Alias the Database class for ease-of-use
class_alias('Database', 'DB');

/**
 * Initiates the PDO connection and creates a DatabaseQuery object.
 */
class Database
{
	/**
	 * Store a Database instance.
	 * 
	 * @var Database
	 */
	private static $instance;

	/**
	 * Store a PDO instance.
	 * 
	 * @var PDO
	 */
	private $connection;

	/**
	 * Methods allowed for fetching results.
	 * 
	 * @var array
	 */
	private $fetchMethods = array('query', 'row', 'field');

	/**
	 * Create a new PDO connection with the given credentials.
	 * 
	 * @param  array 	$credentials
	 * @return void
	 */
	public function __construct($credentials)
	{
		extract($credentials);

		if (($database AND $username AND $password) == '') {
			die('Database credentials required in config.php');
		}

		try {
			$this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
		} catch (PDOException $e) {
		    die("Database Error: " . $e->getMessage());
		}
	}

	/**
	 * Create a singleton instance of the Database class.
	 * @param  array 	$credentials
	 * @return Database
	 */
	public static function connect($credentials)
	{
		if ( ! self::$instance) {
			self::$instance = new Database($credentials);
		}

		return self::$instance;
	}

	/**
	 * Create a new Database_Result object.
	 *
	 * Called by an instantiated Database object.
	 * 
	 * @param  int 		$fetchMethod
	 * @param  string 	$query
	 * @param  array 	$bindings
	 * @param  int 		$fetchMode
	 * @return DatabaseQuery
	 */
	public function queryObject($fetchMethod, $query, $bindings = null, $fetchMode = null)
	{
		$databaseQuery = new DatabaseQuery($this->connection, $query, $bindings, $fetchMethod, $fetchMode);

		return $databaseQuery;
	}

	/**
	 * Create a new Database_Result object.
	 *
	 * Called statically. If a PDO connection has not been previously
	 * made it can include the credentials, and a connection will be
	 * made on the fly.
	 * 
	 * @param  int 		$fetchMethod
	 * @param  string 	$query
	 * @param  array 	$bindings
	 * @param  int 		$fetchMode
	 * @param  array 	$credentials
	 * @return DatabaseQuery
	 */
	public static function queryStatic($fetchMethod, $query, $bindings = null, $fetchMode = null)
	{
		$databaseQuery = new DatabaseQuery(self::$instance->connection, $query, $bindings, $fetchMethod, $fetchMode);

		return $databaseQuery;
	}

	/**
	 * Magic method to capture undefined instantiated object method calls.
	 * 
	 * @param  string 	$name
	 * @param  array 	$arguments
	 * @return DatabaseQuery
	 */
	public function __call($name, $arguments)
	{
		// Add the name of the function called as the first argument (fetchMethod)
		array_unshift($arguments, $name);

		if (in_array($name, $this->fetchMethods)) {
			return call_user_func_array(array($this, 'queryObject'), $arguments);
		}
	}

	/**
	 * Magic method to capture undefined static method calls.
	 * 
	 * @param  string 	$name
	 * @param  array 	$arguments
	 * @return DatabaseQuery
	 */
	public static function __callStatic($name, $arguments)
	{
		// Add the name of the function called as the first argument (fetchMethod)
		array_unshift($arguments, $name);

		if (in_array($name, self::$instance->fetchMethods)) {
			return call_user_func_array(array('Database', 'queryStatic'), $arguments);
		}
	}
}

/**
 * Stores the details of a query, including it's connection, 
 * the query itself, and it's results.
 */
class DatabaseQuery implements ArrayAccess, IteratorAggregate
{
	/**
	 * Store a PDO instance.
	 * 
	 * @var PDO
	 */
	private $connection;

	/**
	 * Store the PDO statement.
	 * 
	 * @var PDOStatement
	 */
	private $query;

	/**
	 * Store the parameters we'll use for the prepared statements.
	 * 
	 * @var array
	 */
	private $params = array();

	/**
	 * The type of fetch we are doing; i.e. fetching an array result set, a row, or a field
	 * 
	 * @var string
	 */
	private $fetchMethod;

	/**
	 * Set the PDO fetch mode. Allows us to return data as objects or associative arrays.
	 * 
	 * @var int 
	 */
	private $fetchMode = PDO::FETCH_OBJ;

	/**
	 * Store the result of the query.
	 * 
	 * @var array
	 */
	private $result = array();

	/**
	 * Prepare the query, apply bindings and execute.
	 * 
	 * @param  PDO 		$connection
	 * @param  string 	$query
	 * @param  array 	$bindings
	 * @param  int 		$fetchMethod
	 * @param  int 		$fetchMode
	 * @return void
	 */
	public function __construct($connection, $query, $bindings, $fetchMethod, $fetchMode)
	{
		$this->connection = $connection;

		$this->query = $this->connection->prepare($query);

		// If there are any bindings for the prepared statement we store them
		if ($bindings) {
			$this->params = $this->params + $bindings;
		}

		$this->fetchMethod = $fetchMethod;

		// If a fetch more was specified we store it
		if ($fetchMode) {
			$this->fetchMode = $fetchMode;
		}

		$this->execute();
	}

	/**
	 * Execute the query and store the result.
	 * 
	 * @return void
	 */
	public function execute()
	{
		$this->query->setFetchMode($this->fetchMode);

		$this->query->execute($this->params);	

		// Fetch the data in the appropriate format
		switch ($this->fetchMethod) {
			case 'row':
				$this->result = $this->query->fetch();
				break;
			case 'field':
				$this->result = $this->query->fetchColumn();
				break;
			default:
				$this->result = $this->query->fetchAll();
				break;
		}
	}

	/**
	 * Return the number of affected rows.
	 * 
	 * @return integer
	 */
	public function count()
	{
		return $this->query->rowCount();
	}

	/**
	 * Return the last inserted id.
	 * 
	 * @return integer
	 */
	public function lastInsertId()
	{
		return $this->connection->lastInsertId();
	}

 	/**
 	 * Create a new iterator.
 	 * 
 	 * @return ArrayIterator
 	 */
    public function getIterator()
    {
        return new ArrayIterator($this->result);
    }

    /**
     * Magic Method to allow us to read unaccessible properties.
     *
     * When using Database::row() a DatabaseQuery object is returned,
     * but consudering we are querying for one row it's likely we will want to use the returned
     * value as if it's a result set. This Magic Method allow us to capture undefined properties and
     * return the corresponding value from our stored query result. 
     * 
     * @param  string 	$name
     * @return string
     */
    public function __get($name)
    {
    	if ($this->result) {
    		return $this->result->{$name};
    	}
    }

    /**
     * Magic Method to allow us to use the returned object like a string.
     *
     * When using Database::field() a DatabaseQuery object is returned,
     * but considering we are querying one field it's likely we will want to use the returned
     * value as if it were a string. This Magic Method allows us to 'convert' the object to a string
     * by returning the query result value.
     * 
     * @return string
     */
    public function __toString()
    {
    	if ($this->result) {
    		return $this->result;
    	}
    }

    /**
     * Magic method to return the data that should be serialized when attempting
     * to serialize an object instance.
     * 
     * @return array
     */
    public function __sleep()
    {
    	return array('result');
    }

    /**
     * Magic method that is called when calling isset() on an object instance.
     * 
     * @param  string  $name
     * @return boolean
     */
    public function __isset($name)
    {
    	if (is_array($this->result)) {
    		return ! empty($this->result[$name]);
    	}

    	return ! empty($this->result);
    }

 	public function offsetSet($offset, $value)
 	{
        if (is_null($offset)) {
            $this->result[] = $value;
        } else {
            $this->result[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->result[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->result[$offset]);
    }
    
    public function offsetGet($offset)
    {
        return isset($this->result[$offset]) ? $this->result[$offset] : null;
    }
}