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
 * By using PHP's trigger_error functionality and providing a mapping from the Error Codes to Log Levels,
 *  an application can avoid adding a dependency for a Logging Class in those classes that require logging.
 *  Instead, this class can be used to intercept and convert the trigger_error data to either a Log Entry
 *  or to a PHP Exception.
 *
 * 1. Set ErrorHandling::setError as the PHP error_handler
 *
 * @link       http://us2.php.net/manual/en/function.set-error-handler.php
 *
 * 2. Inject this ErrorHandling class with any PSR-3 compliant Logger Interface
 *
 * @link       https://github.com/Molajo/Log
 *
 * 3. Review $error_number_array which can be used, as is, or customized, as needed
 *
 *      a. Use the existing $error_number_array, as defined below (and ignore the next step, 3b.)
 *
 *      b. -or- Modify the $error_number_array assignments and inject the array during class construction.
 *          The array map PHP Error Numbers (ex. E_NOTICE, E_USER_WARNING) to either the PSR-3 Log Level or to
 *          instead throw a PHP Error Exception. Each PHP Error number in the $error_number_array
 *          must be assigned to a PSR-3 Log Levels or to '999' for a PHP Exception
 *
 * 4. To use within the application, set errors using the `trigger_error` function.
 *
 * @link       http://php.net/manual/en/function.trigger-error.php
 *
 *       PHP will direct errors to the function defined as the error_handler (i.e., ErrorHandling::setError)
 *
 *       Example 1 simple message and error code:
 *
 *       trigger_error('This is the message', E_USER_NOTICE);
 *
 *       Example 2 use the $context array to assign the Log Level and provide data values to be used in log
 *
 *       $context = array();
 *       $context['log_level'] = 200;
 *       $context['userid']    = 1;
 *       $context['action']    = 'logon;
 *       trigger_error('This is another message', E_ERROR, $context=$value);
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
     * Levels
     *
     * @var    array
     * @since  1.0
     */
    protected $levels = array(100, 200, 250, 300, 400, 500, 550, 600);

    /**
     * Error Code Array - use default assignments or inject custom $error_number_array
     *
     * @var    boolean
     * @since  1.0
     * @link   http://php.net/manual/en/errorfunc.constants.php
     */
    protected $error_number_array
        = array(
            E_NOTICE          => 200,
            E_DEPRECATED      => 250,
            E_STRICT          => 300,
            E_WARNING         => 300,
            E_ERROR           => 999,
            E_USER_NOTICE     => 200,
            E_USER_DEPRECATED => 250,
            E_USER_WARNING    => 300,
            E_USER_ERROR      => 400
        );

    /**
     * Class Constructor
     *
     * @param  LoggerInterface $logger_instance
     * @param  array           $error_number_array
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
     * Method is called by PHP for errors if it has been assigned the PHP set_error_handler in the application
     *
     * @param   integer $error_number
     * @param   string  $message
     * @param   string  $file
     * @param   integer $line_number
     * @param   array   $context
     *
     * @return  boolean
     * @throws  \CommonApi\Exception\ErrorThrownAsException
     * @since   1.0.0
     */
    public function setError($error_number, $message, $file, $line_number, array $context = array())
    {
        if ($this->respectErrorReporting($error_number) === false) {
            return true; // Bypass PHP Error Handling
        }

        $level   = $this->setLogLevel($error_number, $context);
        $message = (string)$message;
        $context = $this->createLogContextArray($file, $line_number, $context);

        if ($level > 600) {
            $this->throwErrorException($level, $message, $file, $line_number);
        }

        $this->log($level, $message, $context);

        return true; // Bypass PHP Error Handling
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
        if (!(error_reporting() & $error_number)) {
            // This error code is not included in error_reporting
            return false;
        }

        return true;
    }

    /**
     * Determine if error number is within the range
     *
     * @param   integer $level
     * @param   string  $message
     * @param   string  $file
     * @param   integer $line_number
     *
     * @return  boolean
     * @since   1.0.0
     * @throws  ErrorException
     */
    protected function throwErrorException($level, $message, $file, $line_number)
    {
        throw new ErrorException($message, 0, $level, $file, $line_number);
    }

    /**
     * Set Log Level
     *
     * @param   int   $error_number
     * @param   array $context
     *
     * @return  integer
     * @since   1.0.0
     */
    protected function setLogLevel($error_number, array $context = array())
    {
        $log_level = $this->setSpecificLogLevel($context);

        if ($log_level === false) {
        } else {
            return $log_level;
        }

        return $this->setMappedLogLevel($error_number);
    }

    /**
     * Set Log Level using trigger_error $context['log_level'], if exists
     *
     * @param   array $context
     *
     * @return  mixed|boolean|integer
     * @since   1.0.0
     */
    protected function setSpecificLogLevel(array $context = array())
    {
        if (isset($context['log_level'])) {
        } else {
            return false;
        }

        $log_level = $context['log_level'];

        if (in_array($log_level, $this->levels)) {
            return $log_level;
        }

        return false;
    }

    /**
     * Set Log Level using the PHP Error Number to Log Level Array
     *
     * @param   int $error_number
     *
     * @return  integer
     * @since   1.0.0
     */
    protected function setMappedLogLevel($error_number)
    {
        if (isset($this->error_number_array[$error_number])) {
        } else {
            return $this->error_number_array[400];
        }

        return $this->error_number_array[$error_number];
    }

    /**
     * Transfer existing context, $file, and $line_number to log $context array
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

        if (count($context) > 0) {
            foreach ($context as $key => $value) {
                if ($key === 'log_level') {
                } else {
                    $new_context[$key] = $value;
                }
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
}
