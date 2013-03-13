<?php

/**
 * The validation library provides a utility that makes it dead simple to 
 * validate forms. It allows rules to be applied to form attributes and then 
 * automagically validates the rules against each attribute.
 *
 * Classes
 * -------
 * Validation
 * ValidationError
 * 
 * @author      Chris Hayes <chayes@okd.com, chris@chrishayes.ca>
 * @link        http://okd.com, http://chrishayes.ca
 * @copyright   (c) 2012 OKD, Chris Hayes
 */

// Alias class to create a more semantic version
class_alias('Validation', 'Validate');

/**
 * Iniatiates the validation library and contains all of the validation rules.
 */
class Validation 
{
    /**
     * Store the data to be validated.
     * 
     * @var array 
     */
    public $data = array();

    /**
     * Store the attribute validation rules.
     * 
     * @var array 
     */
    private $rules = array();

    /**
     * Size related validation rules.
     * 
     * @var array
     */
    private $sizeRules = array('between', 'min', 'max');

    /**
     * Numeric related validation rules.
     * @var array
     */
    private $numericRules = array('numeric', 'integer');

    /**
     * Validation rules that contain parameter lists.
     * 
     * @var array
     */
    private $listRules = array('in', 'notin', 'filetype');

    /**
     * Store any validation errors.
     * 
     * @var array 
     */
    public $errors = array();

    /**
     * Initialize validation data.
     * 
     * @param   array   $data 
     * @param   mixed   $messages 
     */
    private function __construct($data, $messages)
    {
        $this->data = $data;

        $this->errors = new ValidationError($messages);
    }

    /**
     * Create a new validation instance.
     * 
     * @param  array   $data   Data to be validated.
     * @param  array   $messages
     * @return Validation
     */
    public static function make($data, $messages = null)
    {
        return new self($data, $messages);
    }

    /**
     * Create a new validation instance.
     * 
     * @param  array   $messages
     * @return Validation
     */
    public static function input($messages = null)
    {
        $data = array_merge($_POST, $_FILES);

        return self::make($data, $messages);
    }

    /**
     * Validate data based on a set of validation rules.
     * 
     * @return boolean 
     */
    public function valid()
    {
        // Loop through each attribute.
        foreach ($this->rules as $attribute => $rules) {
            // Loop through each of the attribute's rules.
            foreach ($rules as $rule) {
                $this->check($attribute, $rule);
            }
        }

        return ! $this->errors->exist();
    }

    /**
     * Validate data based on a set of validation rules.
     * 
     * @return  boolean 
     */
    public function invalid()
    {
        return ! $this->valid();
    }
    
    /**
     * Validate data based on a set of validation rules.
     * 
     * @return  boolean 
     */
    public function passes()
    {
        return $this->valid();
    }
    
    /**
     * Validate data based on a set of validation rules.
     * 
     * @return  boolean 
     */
    public function fails()
    {
        return $this->invalid();
    }

    /**
     * Check each attribute's data against each rule that has been applied to it.
     * 
     * @param   string  $attribute
     * @param   string  $rule 
     */
    public function check($attribute, $rule)
    {
        // Get the rule name and any parameters.
        list($rule, $parameters) = $this->parse($rule);

        // Get the value for the current attribute.
        $value = ( ! empty($this->data[$attribute])) ? $this->data[$attribute] : null;

        // Call the validation function.
        if ( ! $this->{'validate'.$rule}($attribute, $value, $parameters)) {
            // The size rules can apply to either numeric or string values, and 
            // the resulting error messages will be different. Here we determine
            // whether the error should be formatted for a string or numeric value.
            if (in_array($rule, $this->sizeRules)) {
                // If the attribute has a numeric rule we assume it's a numeric value
                if ($this->hasRule($attribute, $this->numericRules)) {
                    $rule .= '.numeric';
                // Otherwise we assume it's a string value
                } else {
                    $rule .= '.string';
                }
            // If the rule contains a list of items, we join them back into a string
            // to display in the error message
            } elseif (in_array($rule, $this->listRules)) {
                $parameters = implode(', ', $parameters);
            }

            $this->errors->addError($attribute, $rule, $parameters);
        }
    }

    /**
     * Determine if a attribute is validatable.
     * 
     * To be considered validatable, the attribute must either exist, or the rule
     * being checked must implicitly validate "required", such as the "required" rule.
     * 
     * @param   sting   $attribute
     * @param   mixed   $value
     * @param   sring   $rule
     * @return  boolean 
     */
    private function validatable($attribute, $value, $rule)
    {
        return $this->validateRequired($attribute, $value) or $rule == 'required';
    }

    /**
     * Add a set of rules to a attribute.
     * 
     * @param   mixed   $attribute  Either a attribute name (string) or an array with 
     *                          attributes as keys and strings (rules) as values.
     * @param   string  $rules  Rules are added as a string separated by '|'.
     * @return  Validation 
     */
    public function rules($attribute, $rules = null)
    {
        // If they sent in an array we iterate over each attribute and apply the rules.
        if (is_array($attribute)) {
            foreach ($attribute as $attribute => $rule) {
                $this->rule($attribute, $rule);
            }

            return $this;
        }

        $this->rule($attribute, $rules);

        return $this;
    }

    /**
     * Convert a attribute rule string to an array and store it.
     * 
     * @param   string  $attribute
     * @param   string  $rule 
     */
    public function rule($attribute, $rule)
    {
        // Get an array of the rules.
        $rules = (is_string($rule)) ? explode('|', $rule) : $rule;

        // Trim each rule and then store it.
        $this->rules[$attribute] = array_map('trim', $rules);
        
        return $this;
    }

    /**
     * Parse a rule that requires a parameter into an array that contains the
     * rule name and the paramater passed to it.
     * 
     * i.e. min:5 will be parsed to array('min','5')
     * 
     * @param   string  $rule
     * @return  array 
     */
    public function parse($rule)
    {
        $parameters = array();

        // If the rule has parameters we parse them out.
        if (strstr($rule, ':')) {
            list($rule, $parameters) = explode(':', $rule);

            // Trim off any whitespace.
            $rule = trim($rule);
            $parameters = trim($parameters);

            // If there are multiple parameters they'll be separated by a
            // comma, so we'll parse those out as well.
            if (strstr($parameters, ',')) {
                $parameters = explode(',', $parameters);
                $parameters = array_map('trim', $parameters);
            }
        }

        return array($rule, (array) $parameters);
    }

    /**
     * Determine if an attribute has a rule assigned to it.
     * 
     * @param  string   $attribute
     * @param  array    $rules
     * @return boolean
     */
    private function hasRule($attribute, $rules)
    {
        foreach ($this->rules[$attribute] as $rule) {
            list ($rule, $parameters) = $this->parse($rule);

            if (in_array($rule, $rules)) {
                return true;
            }
        }

        return false;
    }

    /**
     * [Validation Rule] Validate that a value exists.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateRequired($attribute, $value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) and trim($value) === '') {
            return false; 
        } elseif (is_array($value) && $value['name'] == '' || is_array($value) && $value['error']) {
            return false;
        }

        return true;
    }

    /**
     * [Validate Rule] Validate that a attribute was "accepted".
     * 
     * This validation rule implies the attribute is "required".
     * 
     * @param  string   $attribute
     * @param  mixed    $value
     * @return boolean
     */
    private function validateAccepted($attribute, $value)
    {
        return $this->validateRequired($attribute, $value) and ($value == 'yes' or $value == '1');
    }

    /**
     * [Validation Rule] Validate that a value is a date.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateDate($attribute, $value)
    {
        try {
            $dt = new DateTime(trim($value));
        } catch (Exception $e) {
            return false;
        }

        $month = $dt->format('m');
        $day = $dt->format('d');
        $year = $dt->format('Y');

        return checkdate($month, $day, $year);
    }

    /**
     * [Validation Rule] Validate that a value is an email address.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateEmail($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * [Validation Rule] Validate that a value is an IP address.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateIp($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * [Validation Rule] Validate that a value is a URL.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateUrl($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * [Validation Rule] Validate that a value is between two values.
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateBetween($attribute, $value, $parameters)
    {
        if (is_numeric($value)) {
            return $value >= $parameters[0] && $value <= $parameters[1];
        }

        return strlen($value) >= $parameters[0] && strlen($value) <= $parameters[1];
    }

    /**
     * [Validation Rule] Validate the size of a value is greater than a
     * minimum value.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $paramaters
     * @return  boolean 
     */
    private function validateMin($attribute, $value, $paramaters)
    {
        if (is_numeric($value)) {
            return $value >= $paramaters[0];
        }

        return strlen(trim($value)) >= $paramaters[0];
    }

    /**
     * [Validation Rule] Validate the size of a value is less than a
     * maximum value.
     * 
     * @param   string  $attribute
     * @param   sring   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateMax($attribute, $value, $parameters)
    {
        if (is_numeric($value)) {
            return $value <= $parameters[0];
        }

        return strlen(trim($value)) <= $parameters[0];
    }

    /**
     * [Validation Rule] Validate that the value of one attribute is the same as
     * the value of another attribute.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateMatch($attribute, $value, $parameters)
    {
        $other = $parameters[0];

        return isset($this->data[$other]) and $value == $this->data[$other];
    }

    /**
     * [Validation Rule] Validate that the value of one attribute is different from
     * the value of another attribute.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateMismatch($attribute, $value, $parameters)
    {
        return ! $this->validateMatch($attribute, $value, $parameters);
    }

    /**
     * [Validation Rule] Validate that a value is the same as a given value.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateSame($attribute, $value, $parameters)
    {
        if (is_numeric($attribute) && is_numeric($value)) {
            return $value != $parameters[0];
        }

        // Compare two strings.
        return (strcasecmp($value, $parameters[0]) == 0) ? true : false;
    }

    /**
     * [Validation Rule] Validate that a value is different from a given value.
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateDifferent($attribute, $value, $parameters)
    {
        return ! $this->validateSame($attribute, $value, $parameters);
    }

    /**
     * [Validation Rule] Validate a person's age given their birthdate
     * (formatted YYYY-MM-DD).
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateAge($attribute, $value, $parameters)
    {
        return strtotime("-$parameters[0] year") >= strtotime($value);
    }

    /**
     * [Validation Rule] Validate that a attribute is numeric.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateNumeric($attribute, $value)
    {
        return is_numeric($value);
    }

    /**
     * [Validation Rule] Validate that a attribute is an integer.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateInteger($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * [Validation Rule] Validate that a value contains only
     * alphabetic characters.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateAlpha($attribute, $value)
    {
        return preg_match('/^([a-z])+$/i', $value);
    }

    /**
     * [Validation Rule] Validate that a value contains only
     * alpha-numeric characters.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateAlphaNum($attribute, $value)
    {
        return preg_match('/^([a-z0-9])+$/i', $value);
    }

    /**
     * [Validation Rule] Validate that a value contains only alpha-numeric
     * characters, dashes, and underscores.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateAlphaDash($attribute, $value)
    {
        return preg_match('/^([-a-z0-9_-])+$/i', $value);
    }

    /**
     * [Validation Rule] Validate that a value is a Canadian postal code.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validatePostalCode($attribute, $value)
    {
        return preg_match('/[ABCEGHJKLMNPRSTVXY]\d[A-Z] \d[A-Z]\d/', $value);
    }

    /**
     * [Validation Rule] Validate that a value is an American zip code.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @return  boolean 
     */
    private function validateZipCode($attribute, $value)
    {
        return preg_match('/\d{5}(?(?=-)-\d{4})/', $value);
    }

    /**
     * [Validation Rule] Validate that a value is in a comma-delimated list.
     * Case insensitive.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateIn($attribute, $value, $parameters)
    {
        return preg_grep("/$value/i" , $parameters);
    }

    /**
     * [Validation Rule] Validate that a value is not in a comma-delimated list.
     * Case insensitive.
     * 
     * @param   string  $attribute
     * @param   mixed   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateNotIn($attribute, $value, $parameters)
    {
        return ! $this->validateIn($attribute, $value, $parameters);
    }

    /**
     * [Validation Rule] Validate that a file has an allowed extension.
     * 
     * @param   string  $attribute
     * @param   array   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateFileType($attribute, $value, $parameters)
    {
        // Get the file extension
        $ext = pathinfo($value['name'], PATHINFO_EXTENSION);

        return $this->validateIn($attribute, $ext, $parameters);
    }

    /**
     * [Validation Rule] Validate that the size of a file is not too large.
     * 
     * @param   string  $attribute
     * @param   array   $value
     * @param   array   $parameters
     * @return  boolean 
     */
    private function validateFileSize($attribute, $value, $parameters)
    {
        // If the rule is specified in KB convert to bytes
        if ($size = stristr($parameters[0], 'kb', true)){
            $size = $size * 1024;
        // If the rule is specified in MB convert to bytes
        } else if ($size = stristr($parameters[0], 'mb')) {
            $size = $size * (1024 * 2);
        }

        return $value['size'] <= $size;
    }

}

/**
 * Validation Error class for easily working with validation errors.
 */
class ValidationError
{
    /**
     * Store the validation errors.
     * 
     * @var array
     */
    private $errors = array();

    /**
     * Default error messages.
     * 
     * @var array
     */
    private $messages = array(
        'accepted'      => 'You must accept the terms',
        'required'      => 'This attribute is required',
        'date'          => 'Must be a valid date',
        'email'         => 'Must be a valid email address',
        'ip'            => 'Must be a valid IP address',
        'url'           => 'Must be a valid URL',
        'between'       => array(
            'numeric'       => 'Must be between %s and %s',
            'string'        => 'Must be between %s and %s characters long'
        ),
        'min'           => array(
            'numeric'       => 'Must be at least %s',
            'string'        => 'Must be at least %s characters long'
        ),
        'max'           => array(
            'numeric'       => 'Must not be greater than %s',
            'string'        => 'Must not be greater than %s characters long'
        ),
        'match'         => 'Must match the %s attribute',
        'mismatch'      => 'Must not match the %s attribute',
        'same'          => 'Must equal %s',
        'different'     => 'Must not equal %s',
        'age'           => 'You must be of age %s',
        'numeric'       => 'Must be numeric',
        'integer'       => 'Must be an integer',
        'alpha'         => 'Must contain only alphabetic characters',
        'alpha'         => 'Must contain only alphanumeric characters',
        'alphadash'     => 'Must contain only alphanumeric characters or dashes',
        'postalcode'    => 'Must be a valid postal code',
        'zipcode'       => 'Must be a valid zip code',
        'in'            => 'Must be one of: %s',
        'notin'         => 'Must not be one of: %s',
        'filetype'      => 'Must be of file type: %s',
        'filesize'      => 'Maximum file size is %s'
    );
    
    /**
     * Class constructor. Accepts a messages parameter that allows 
     * overridding of default messages.
     * 
     * @param  mixed    $messages
     * @return void
     */
    public function __construct($messages)
    {
        if ($messages) {
            $this->messages = array_merge($this->messages, $messages);
        }
    }
    
    /**
     * Check if an error for a attribute exists.
     * 
     * @param  string   $attribute
     * @return boolean
     */
    public function exist($attribute = null)
    {
        if ($attribute and ! empty($this->errors[$attribute])) {
            return true;
        } elseif ( ! $attribute and ! empty($this->errors)) {
            return true;
        }

        return false;
    }

    /**
     * Get the first error for a attribute.
     * 
     * @param  string   $attribute
     * @param  string   $format
     * @return string
     */
    public function first($attribute, $format = null)
    {
        if ($this->exist($attribute)) {
            // Format the message
            $message = $this->formatMessages($this->errors[$attribute], $format);

            // Get the first error message for the attribute
            return array_shift($message);
        }
    }

    /**
     * Get all the errors for a attribute.
     * 
     * @param  string   $attribute
     * @param  string   $format
     * @param  boolean  $returnString
     * @return array
     */
    public function get($attribute, $format = null, $returnString = false)
    {
        if ($this->exist($attribute)) {
            // Format the message
            $messages = $this->formatMessages($this->errors[$attribute], $format);

            return ($returnString) ? $this->compressMessages($messages) : $messages;
        }
    }

    /**
     * Get all the errors.
     * 
     * @param  string   $format
     * @param  boolean  $returnString
     * @return array
     */
    public function all($format = null, $returnString = false)
    {
        // Format the message
        $messages = $this->formatMessages($this->errors, $format);

        return ($returnString) ? $this->compressMessages($messages) : $messages;
    }

    /**
     * Store an error for a attribute.
     * 
     * @param  string   $attribute
     * @param  string   $rule
     * @param  array    $parameters
     * @return void
     */
    public function addError($attribute, $rule, $parameters = null)
    {
        $message = $this->getFromString($rule, $this->messages);

        if ($parameters) {
            $message = vsprintf($message, $parameters);
        }

        $this->errors[$attribute][$rule] = $message;
    }

    /**
     * Remove an error for a attribute.
     * 
     * @param  string   $attribute
     * @param  string   $rule
     * @param  boolean  $all
     * @return void
     */
    public function removeError($attribute, $rule, $all = false)
    {
        if ($all) {
            unset($this->errors[$attribute]);
        } else {
            unset($this->errors[$attribute][$rule]);
        }
    }

    /**
     * Format error messages.
     * 
     * @param  mixed    $messages
     * @param  string   $format
     * @return mixed
     */
    private function formatMessages($messages, $format)
    {
        // If formatting is specified apply it
        if ($format) {
            // Recursively loop through errors
            if (is_array($messages)) {
                foreach ($messages as &$message) {
                    $message = $this->formatMessages(&$message, &$format);
                }
            // Once we have reached an error message we format it            
            } else {
                return str_replace(':message', $messages, $format);
            }
        }

        return $messages;
    }

    /**
     * Convert an array of messages into a string. This is useful for easily printing
     * all error messages to the screen.
     * 
     * @param  array    $messages
     * @return string
     */
    private function compressMessages($messages)
    {
        if (is_array($messages)) {
            foreach ($messages as &$message) {
                $message = $this->compressMessages($message);
            }
            
            $messages = join('', $messages);
        }

        return $messages;
    }

    /**
     * Given an array key in "dot notation" get an array value if it 
     * exists, otherwise return a default value.
     * 
     * @param   string  $keys   Array key as a dot notated string.
     * @param   array   $array  Array to search through.
     * @return  string
     */
    public static function getFromString($keys, $array, $default = null)
    {
        foreach (explode('.', $keys) as $key)
        {
            if ( ! is_array($array) or ! array_key_exists($key, $array))
            {
                return $default;
            }

            $array = $array[$key];
        }
        
        return $array;
    }
}