<?php
/**
 * Exception Handling Controller
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use CommonApi\Exception\ExceptionInterface;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * PHP Exception Handling Class
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ExceptionHandling
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
            400 => 'exception',
            500 => 'critical',
            550 => 'alert',
            600 => 'emergency'
        );

    /**
     * RFC 5424 syslog protocol Logging levels
     *
     * @var    array
     * @since  1.0
     * @link   http://tools.ietf.org/html/rfc5424
     */
    protected $exception_to_log_level
        = array(
            'BadFunctionCallException' => 500,
            'BadMethodCallException'   => 500,
            'DomainException'          => 500,
            'ErrorThrownAsException'   => 500,
            'InvalidArgumentException' => 500,
            'LengthException'          => 500,
            'LogicException'           => 500,
            'OutOfBoundsException'     => 500,
            'OutOfRangeException'      => 500,
            'OverflowException'        => 500,
            'RangeException'           => 500,
            'RuntimeException'         => 500,
            'UnderflowException'       => 500,
            'UnexpectedValueException' => 500
        );

    /**
     * Valid Log Levels in an array for validation
     *
     * @var    array
     * @since  1.0
     */
    protected $levels = array(0, 100, 200, 250, 300, 400, 500, 550, 600, 999);

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
     * @param  LoggerInterface $logger_instance (PSR-3 compliant Logger)
     * @param  array           $exception_number_array
     *
     * @since  1.0
     */
    public function __construct(
        LoggerInterface $logger_instance,
        array $exception_number_array = array()
    ) {
        $this->logger_instance = $logger_instance;
        if (count($exception_number_array) > 0) {
            $this->exception_to_log_level = $exception_number_array;
        }
    }

    /**
     * Method is called by PHP when assigned as the `set_exception_handler` for the application
     *
     * @param   Exception $exception
     *
     * @return  boolean
     * @since   1.0.0
     */
    public function handleException(Exception $e)
    {
        $this->setLogLevelUsingMapping($e->getCode());

        if ($this->log_level === 0) {
        } else {
            $context = $this->createLogContextArray($e);
            $this->log($this->log_level, $e->getMessage(), $context);
        }

        return $this->displayException($e);
    }

    /**
     * Set Log Level using the PHP Error Number to Log Level Array
     *
     * @param   int $exception_code
     *
     * @return  boolean
     * @since   1.0.0
     */
    protected function setLogLevelUsingMapping($exception_code)
    {
        $this->log_level = 0;

        if (isset($this->exception_to_log_level[$exception_code])) {

            $log_level = $this->exception_to_log_level[$exception_code];

            if (in_array($log_level, $this->levels)) {
                $this->log_level = $log_level;
            }
        }

        return true;
    }

    /**
     * Transfer $file, $line_number and trace to $context array
     *
     * @param   Exception $e
     *
     * @return  array
     * @since   1.0.0
     */
    protected function createLogContextArray(Exception $e)
    {
        $new_context = array();

        $new_context['file']            = $e->getFile();
        $new_context['line_number']     = $e->getLine();
        $new_context['trace_as_string'] = $e->getTraceAsString();

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
     * Method is called by PHP when assigned as the `set_exception_handler` for the application
     *
     * @param   Exception $exception
     *
     * @return  boolean
     * @since   1.0.0
     */
    public function displayException(Exception $e)
    {
        echo "Message: " . $e->getMessage() . "\n\n";
        echo "File: " . $e->getFile() . "\n\n";
        echo "Line: " . $e->getLine() . "\n\n";
        echo "Trace: \n" . $e->getTraceAsString() . "\n";

    }
}
