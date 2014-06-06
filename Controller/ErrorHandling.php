<?php
/**
 * Error Handling Controller
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use CommonApi\Controller\ErrorHandlingInterface;
use ErrorException;
use Psr\Log\LoggerInterface;

/**
 * Customizable PHP Error Handling for converting PHP Errors into PSR-3 Log entries or PHP Exceptions
 *
 * Uses PHP's trigger_error functionality for logging to avoid class dependency for a Logging Class.
 *  PHP routes triggered errors to the `setError` method in this class which can be handled as a log entry,
 *  an exception, or ignored and passed on to PHP for processing. The benefit is no dependency on the Logger
 *  class within application classes. Each class can focus on its specific responsibility and this class
 *  interacts with the logger class.
 *
 * How to use:
 *
 * 1. Set the `setError` method of this class as the PHP `error_handler` for the application.
 *
 *    set_error_handler(array($this->error_handler, 'setError'));
 *
 * @link       http://us2.php.net/manual/en/function.set-error-handler.php
 *
 * 2. Inject `Logger` that implements Psr\Log\LoggerInterface (ex., `Monolog` or `Molajo Log`)
 *  as the logger_instance for this class
 *
 * @link       https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * @link       https://github.com/Seldaek/monolog
 * @link       https://github.com/Molajo/Log
 *
 * 3. Consider `log_level` assignment process for your needs.
 *
 *      a. Uses `log_level` within $options, if located.
 *      b. Uses the injected `$error_number_array` assignments injected during class construction.
 *      c. Uses existing `$error_number_array` to map the PHP Error Number to a PSR-3 Log Level.
 *      d. See `setLogLevel` method for details.
 *
 * 4. To use within the application, set errors using the PHP `trigger_error` function. There is no need
 *    to inject a logging class in the application classes. PHP will route errors here (see step 1)
 *    and this class will interact with the Log class. This separation of duties and reduction of
 *    dependencies are a big improvement to this approach.
 *
 *    trigger_error('This is the message', E_USER_NOTICE);  // NOTE: Only use E_USER_etc values
 *
 * @link       http://php.net/manual/en/function.trigger-error.php
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ErrorHandling implements ErrorHandlingInterface
{
    /**
     * Logger
     *
     * @var    object
     * @since  1.0
     */
    protected $logger_instance = null;

    /**
     * RFC 5424 syslog protocol Logging levels
     *
     * @var    array
     * @since  1.0
     * @link   http://tools.ietf.org/html/rfc5424
     */
    protected $levels_by_code
        = array(
            100 => 'debug',
            200 => 'info',
            250 => 'notice',
            300 => 'warning',
            400 => 'error',
            500 => 'critical',
            550 => 'alert',
            600 => 'emergency'
        );

    /**
     * Valid Log Levels in an array for validation
     *
     * @var    array
     * @since  1.0
     */
    protected $levels = array(0, 100, 200, 250, 300, 400, 500, 550, 600, 999);

    /**
     * Error Code Array - use default assignments or inject custom $error_number_array
     *
     * Array lists all PHP Error numbers and maps the values to a PSR-3 Log Level.
     * To allow PHP to handle the error, enter a value of '0` for the mapping. (Then, this class will ignore.)
     * To throw a PHP Error Exception for errors with a specific error number(s), set the mapped value to `999.`
     * This error number to log level assignments array can be overridden during class construction.
     *
     * @var    array
     * @since  1.0
     * @link   http://php.net/manual/en/errorfunc.constants.php
     */
    protected $error_number_array
        = array(
            E_NOTICE          => 200,
            E_DEPRECATED      => 250,
            E_STRICT          => 300,
            E_WARNING         => 300,
            E_ERROR           => 400,
            E_USER_NOTICE     => 200,
            E_USER_DEPRECATED => 250,
            E_USER_WARNING    => 300,
            E_USER_ERROR      => 400
        );

    /**
     * Log Level - used for assignment processes
     *
     * @var    integer
     * @since  1.0
     */
    protected $log_level = null;

    /**
     * Class Constructor
     *
     * @param  LoggerInterface $logger_instance    (PSR-3 compliant Logger)
     * @param  array           $error_number_array Only to override default error code to log level assignments
     *
     * @since  1.0
     */
    public function __construct(
        LoggerInterface $logger_instance,
        array $error_number_array = array()
    ) {
        $this->logger_instance = $logger_instance;

        if (count($error_number_array) > 0) {
            $this->error_number_array = $error_number_array;
        }
    }

    /**
     * Method is called by PHP when assigned as the `set_error_handler` for the application
     *
     * @param   string  $error_number
     * @param   string  $message
     * @param   string  $file
     * @param   integer $line_number
     * @param   array   $context
     *
     * @return  boolean
     * @throws  ErrorException
     * @since   1.0.0
     */
    public function setError($error_number, $message, $file, $line_number, array $context = array())
    {
        if ($this->respectErrorReporting($error_number) === false) {
            return true; // Neither this class, nor PHP will process
        }

        $this->setLogLevel($error_number, $context);

        if ($this->log_level === 0) {
            return false; // Tell PHP to handle this error
        }

        if ($this->log_level > 600) {
            $this->throwErrorException($error_number, $message, $file, $line_number);
        }

        $context = $this->createLogContextArray($file, $line_number, $context);

        $this->log($this->log_level, $message, $context);

        return true; // Tell PHP not to handle this error
    }

    /**
     * Determine if error number is within the range
     *
     * @param   int $error_number
     *
     * @return  boolean
     * @since   1.0.0
     * @link    http://us2.php.net/manual/en/function.set-error-handler.php
     */
    protected function respectErrorReporting($error_number)
    {
        if (error_reporting() & $error_number) {
            return true; // within scope
        }

        // This error code is not included in error_reporting, ignore
        return false;
    }

    /**
     * Set Log Level -- using PHP Error Code to PSR Log Level mapping
     *
     * The `log_level` associated with the `error_number` in the `error_number_array` is used. The array
     * can be customized and injected into the class during class construction.
     *
     * For finer `log_level` assignment, see `setLogLevelMoreControl`
     *
     * @param   int   $error_number
     * @param   array $context
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setLogLevel($error_number, array $context = array())
    {
        $results = $this->setLogLevelMoreControl($context);

        if ($results === false) {
            $this->setLogLevelUsingMapping($error_number);
        }

        return $this;
    }

    /**
     * Set Log Level -- more control
     *
     * For default PHP Error Code to PSR Log Level mapping, see `setLogLevel`
     *
     * For finer `log_level` assignment, set a local variable `log_level` equal to the log level desired
     *  *before* triggering the error within the local scope of the triggering method. In such a case,
     *  the `log_level` will be within the `context` array and used for logging.
     *
     * Example (within the local scope of the triggering method):
     *
     *      protected function triggerError()
     *      {
     *          $log_level = 200;   // PHP will include within the $context array.
     *          trigger_error('This is the message', E_USER_NOTICE);
     *      }
     *
     * @param   array $context
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function setLogLevelMoreControl(array $context = array())
    {
        if (isset($context['log_level'])) {
            return $this->validateLogLevel($context['log_level']);
        }

        return false;
    }

    /**
     * Set Log Level using the PHP Error Number to Log Level Array
     *
     * @param   int $error_number
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function setLogLevelUsingMapping($error_number)
    {
        $log_level = 0;

        if (isset($this->error_number_array[$error_number])) {
            $log_level = $this->error_number_array[$error_number];
        }

        return $this->validateLogLevel($log_level, true);
    }

    /**
     * Validate Log Level
     *
     * 1. Matches a valid log_level, use it.
     * 2. A value above 600 (maximum log level value), indicates a PHP Exception should be thrown.
     * 3. A value of 0 (default) means allow PHP to handle it.
     *
     * @param   integer $log_level
     * @param   boolean $set_default
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function validateLogLevel($log_level, $set_default = false)
    {
        if (in_array($log_level, $this->levels)) {
            $this->log_level = $log_level;
            return true;
        }

        if ($set_default === false) {
            return false;
        }

        $this->log_level = 0; //passes on to PHP

        return true;
    }

    /**
     * Transfer scalar properties within the $context array, along with the $file, and $line_number
     *  values to the log $context array
     *
     * Example (within the local scope of the triggering method):
     *
     *      protected function triggerError()
     *      {
     *          // scalar local variables will be passed on to the logger
     *          $log_level = 200;
     *          $employee_name = $this->employee_name;
     *          $another_thing = 'goes here';
     *
     *          trigger_error('This is the message', E_USER_NOTICE);
     *      }
     *
     * @param   array   $context
     *
     * @param   string  $file
     * @param   integer $line_number
     * @param   array   $context
     *
     * @return  array
     * @since   1.0.0
     */
    protected function createLogContextArray($file, $line_number, array $context = array())
    {
        $new_context = array();

        foreach ($context as $key => $value) {
            if ($key === 'log_level') {

            } elseif (is_scalar($value)) {
                $new_context[$key] = $value;
            }
        }

        $new_context['file']        = $file;
        $new_context['line_number'] = $line_number;

        return $new_context;
    }

    /**
     * Logs with defined log level
     *
     * @param   int    $level
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function log($level, $message, array $context = array())
    {
        $this->logger_instance->log($level, $message, $context);

        return $this;
    }

    /**
     * Throw exception for PHP Error
     *
     * @param   integer $error_number
     * @param   string  $message
     * @param   string  $file
     * @param   integer $line_number
     *
     * @return  boolean
     * @since   1.0.0
     * @throws  ErrorException
     */
    protected function throwErrorException($error_number, $message, $file, $line_number)
    {
        throw new ErrorException($message, 0, $error_number, $file, $line_number);
    }
}
