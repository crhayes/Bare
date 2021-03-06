<?php
/**
 * Swift Template PHP
 *
 * View Inheritance allows HTML views to manage themselves, saving the
 * controllers from worrying about display logic. In short, inheritance in
 * your views offers the same decoupling benefits as standard PHP objects
 * making it easier to manage presentation.
 *
 * This class is more powerful than it looks.
 *
 * This class is based off work by David Pennington
 * <https://github.com/Xeoncross/PHP-Template>. It has been modified to 
 * create a more intuitive and easier to use API.
 *
 * @package		Swift Template PHP
 * @author 		Chris Hayes <chris@chrishayes.ca>
 * @author		http://github.com/Xeoncross/php-template
 * @copyright	(c) 2013 Chris Hayes <http://chrishayes.ca>
 * @copyright	(c) 2011 David Pennington <http://xeoncross.com>
 * @license		MIT License
 */

/**
 * Helper functions to make it easier to work with views.
 */
function view($view)
{
	return new View($view);
}

function extend($name)
{
	View::getInstance()->extend($name);
}

function section($name)
{
	View::getInstance()->section($name);
}

function close()
{
	echo View::getInstance()->close();
}

function show($name)
{
	echo View::getInstance()->show($name);
}

function partial($name)
{
	echo View::getInstance()->load($name);
}

/**
 * Swift Template PHP View Class
 */
class View
{
	/**
	 * Store an instance of the View class.
	 * 
	 * @var View
	 */
	private static $instance;

	/**
	 * Store the path to the views folder.
	 * 
	 * @var string
	 */
	private $viewPath = 'views/';

	/**
	 * The name of the view we are loading.
	 * 
	 * @var string
	 */
	private $viewName;

	/**
	 * The view we are extending.
	 * 
	 * @var string
	 */
	private $extendedView;

	/**
	 * Store the contents of sections.
	 * 
	 * @var array
	 */
	private $sections;

	/**
	 * The currently opened (started) section.
	 * 
	 * @var string
	 */
	private $openSection;

	/**
	 * Returns a new template object
	 *
	 * @param string 	$view
	 */
	private function __construct($view)
	{
		$this->viewName = $view;
	}

	/**
	 * Return an instance of the view class.
	 * 
	 * @param  string 	$view
	 * @return View
	 */
	public static function make($view) {
		return self::getInstance($view);
	}

	/**
	 * Static interface to return an instance of the View class.
	 *
	 * This is used to return an instance of the view class to the helper
	 * methods that would otherwise have no access to the View data.
	 *
	 * @param  mixed 	$view
	 * @return View
	 */
	public static function getInstance($view = null)
	{
		if ( ! isset(self::$instance)) {
			self::$instance = new View($view);
		}

		return self::$instance;
	}

	/**
	 * Set the view path.
	 * 
	 * @param string 	$path
	 */
	public function setViewPath($path)
	{
		$this->viewPath = $path;
	}

	/**
	 * Allows setting template values while still returning the object instance
	 * $template->title($title)->text($text);
	 *
	 * @return this
	 */
	public function __call($key, $args)
	{
		$this->$key = $args[0];
		return $this;
	}

	/**
	 * Render View HTML.
	 *
	 * @return mixed
	 */
	public function render()
	{
		try {
			$view = $this->load($this->viewName);

			if ($this->extendedView) {
				$view = $this->load($this->extendedView);
			}

			echo $view;
		}
		catch(\Exception $e)
		{
			return (string) $e;
		}
	}

	/**
	 * Load the given template and return the contents.
	 *
	 * @param  string 	$view
	 * @return string
	 */
	public function load($view)
	{
		ob_start();

		extract((array) $this);
		require $this->viewPath . $view . '.php';

		$definedVars = get_defined_vars();
		$this->populateProperties($definedVars);

		return ob_get_clean();
	}

	/**
	 * This is a helper method that takes variables defined in the 
	 * local scope of the load method and sets them as properties of the
	 * View object instance. This is provided purely for convenience.
	 *
	 * In essence this allows us to, within a view, set values to be used in
	 * a parent view like so:
	 * 		$value = 'Hello, world!';
	 * rather than like so:
	 * 		$this->value = 'Hello, world!';
	 * 		
	 * @param  array 	$definedVars
	 * @return void
	 */
	public function populateProperties($definedVars)
	{
		foreach($definedVars as $key => $value) {
			// Filter values we don't want to set as properties
			if ( ! in_array($key, array('this', 'view'))){
				$this->{$key} = $value;
			}
		}
	}

	/**
	 * Extend a parent View.
	 *
	 * @param  string 	$view
	 * @return void
	 */
	public function extend($view)
	{
		$this->extendedView = $view;
		ob_end_clean(); // Ignore this child class and load the parent!
		ob_start();
	}

	/**
	 * Start a new section.
	 * 
	 * @param  string 	$name
	 * @return void
	 */
	public function section($name)
	{
		$this->openSection = $name;
		ob_start();
	}

	/**
	 * Close a section and return the buffered contents.
	 *
	 * @return string
	 */
	public function close()
	{
		$name = $this->openSection;

		$buffer = ob_get_clean();

		if ( ! isset($this->sections[$name])) {
			$this->sections[$name] = $buffer;
		} elseif (isset($this->sections[$name])) {
			$this->sections[$name] = str_replace('@parent', $buffer, $this->sections[$name]);
		}

		return $this->sections[$name];
	}

	/**
	 * show the contents of a section.
	 *
	 * @param  string 	$name
	 * @return string
	 */
	public function show($name)
	{
		if(isset($this->sections[$name]))
		{
			return $this->sections[$name];
		}
	}
}