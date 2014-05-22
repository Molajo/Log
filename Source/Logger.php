<?php
/**
 * Adapter for Log
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log;

use DateTime;
use DateTimeZone;
use Exception;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use stdClass;

/**
 * Adapter for Log
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Adapter implements LoggerInterface
{
    /**
     * Logger Adapters
     *
     * @var    array
     * @since  1.0
     */
    protected $logger_adapters = array('dummy', 'display', 'memory');

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
     * Level Names
     *
     * @var    array
     * @since  1.0
     */
    protected $levels_by_name
        = array(
            'debug'     => 100,
            'info'      => 200,
            'notice'    => 250,
            'warning'   => 300,
            'error'     => 400,
            'critical'  => 500,
            'alert'     => 550,
            'emergency' => 600
        );

    /**
     * Levels
     *
     * @var    array
     * @since  1.0
     */
    protected $levels = array(100, 200, 250, 300, 400, 500, 550, 600);

    /**
     * Loggers
     *
     * @var    array
     * @since  1.0
     */
    protected $loggers = array();

    /**
     * Loggers by Level
     *
     * @var    array
     * @since  1.0
     */
    protected $levels_by_loggers
        = array(
            100 => array(),
            200 => array(),
            250 => array(),
            300 => array(),
            400 => array(),
            500 => array(),
            550 => array(),
            600 => array()
        );

    /**
     * Timezone
     *
     * @var    DateTimeZone
     * @since  1.0
     */
    protected $timezone = '';

    /**
     * Constructor
     *
     * @param   array $logger_requests
     *
     * @since   1.0
     */
    public function __construct(
        array $logger_requests = array()
    ) {
        $this->timezone = new DateTimeZone(date_default_timezone_get() ? : 'UTC');
        $this->startLoggers($logger_requests);
    }

    /**
     * Start Loggers
     *
     * @param   array $logger_requests
     *
     * @return  $this
     * @throws  \Psr\Log\InvalidArgumentException
     * @since   1.0
     */
    protected function startLoggers(array $logger_requests = array())
    {
        if (count($logger_requests) > 0) {
            foreach ($logger_requests as $logger_request) {
                $this->startLogger(
                    $logger_request->name,
                    $logger_request->logger_type,
                    $logger_request->levels,
                    $logger_request->context
                );
            }
        }

        return $this;
    }

    /**
     * Start Logger and set log levels
     *
     * @param   string $name
     * @param   string $logger_type
     * @param   array  $levels
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function startLogger($name, $logger_type, $levels = array(), $context = array())
    {
        $logger_type = $this->editLoggerType($logger_type);
        $name        = $this->editLoggerName($name, $logger_type);

        $loggerClass    = 'Molajo\\Log\\Adapter\\' . ucfirst(strtolower($logger_type));
        $loggerInstance = new $loggerClass($context);

        $this->loggers[$name] = $loggerInstance;

        $this->registerLoggerLevels($levels, $name);

        return $this;
    }

    /**
     * Edit Logger Type
     *
     * @param   string $logger_type
     *
     * @return  string
     * @since   1.0
     */
    protected function editLoggerType($logger_type)
    {
        $logger_type = strtolower($logger_type);

        if (in_array($logger_type, $this->logger_adapters)) {
        } else {
            $logger_type = 'memory';
        }

        return $logger_type;
    }

    /**
     * Edit Logger Name
     *
     * @param   string $name
     *
     * @return  string
     * @since   1.0
     */
    protected function editLoggerName($name, $logger_type)
    {
        $name = strtolower($name);

        if ($name === '') {
            $name = strtolower($logger_type);
        }

        return $name;
    }

    /**
     * Edit Logger Levels
     *
     * @param   string $name
     * @param   array  $levels
     *
     * @return  $this
     * @since   1.0
     */
    protected function registerLoggerLevels($name, array $levels = array())
    {
        if (is_array($levels) && count($levels) > 0) {
            foreach ($this->levels_by_loggers as $key => $list) {
                if (in_array($key, $levels)) {
                    $list[] = $name;
                }
            }
        }

        return $this;
    }

    /**
     * Stop Logger
     *
     * @param   string $name
     *
     * @return  $this
     * @throws  \Psr\Log\InvalidArgumentException
     * @since   1.0
     */
    public function stopLogger($name)
    {
        $name = strtolower($name);

        if (isset($this->loggers[$name])) {
        } else {
            return $this;
        }

        unset($this->loggers[$name]);

        foreach ($this->levels_by_loggers as $key => $list) {
            if (in_array($name, $list)) {
                unset($list[$name]);
            }
        }

        return $this;
    }

    /**
     * Logs with defined log level.
     *
     * @param   int    $level
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @throws  \Psr\Log\InvalidArgumentException
     * @since   1.0
     */
    public function log($level, $message, array $context = array())
    {
        if (in_array($level, $this->levels)) {
        } else {
            $level = $this->levels_by_name['info'];
        }

        $log_entry             = new stdClass();
        $log_entry->level      = $level;
        $log_entry->level_name = $this->levels_by_code[$level];
        $log_entry->message    = $message;
        $log_entry->context    = $context;
        $log_entry             = $this->setLogDateTime($log_entry);

        $context['log_entry'] = $log_entry;

        foreach ($this->levels_by_loggers[$level] as $name) {
            $this->logLogger($name, $level, $message, $context);
        }

        return $this;
    }

    /**
     * Set Datetime for Log entry
     *
     * @param  object $log_entry
     *
     * @return  $this
     * @since   1.0
     */
    protected function setLogDateTime($log_entry)
    {
        $date_time = new DateTime('now');
        $date_time->setTimezone($this->timezone);
        $log_entry->datetime = $date_time->format($date_time, 'Y-m-d H:i:s');

        return $log_entry;
    }

    /**
     * Log to a single logger registered for this level
     *
     * @param   string $name
     * @param   int    $level
     * @param   string $message
     * @param   array  $context
     *
     *
     * @return  $this
     * @throws  \Psr\Log\InvalidArgumentException
     * @since   1.0
     */
    protected function logLogger($name, $level, $message, array $context = array())
    {
        try {
            $this->loggers[$name]->log($message, $level, $context);

        } catch (Exception $e) {

            throw new InvalidArgumentException(
                'Log: log Failed for ' . $name . ' with Message: ' . $e->getMessage()
            );
        }
    }

    /**
     * System is unusable.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function emergency($message, array $context = array())
    {
        return $this->log($this->levels_by_name['emergency'], $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function alert($message, array $context = array())
    {
        return $this->log($this->levels_by_name['alert'], $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function critical($message, array $context = array())
    {
        return $this->log($this->levels_by_name['critical'], $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function error($message, array $context = array())
    {
        return $this->log($this->levels_by_name['error'], $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function warning($message, array $context = array())
    {
        return $this->log($this->levels_by_name['warning'], $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function notice($message, array $context = array())
    {
        return $this->log($this->levels_by_name['notice'], $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function info($message, array $context = array())
    {
        return $this->log($this->levels_by_name['info'], $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function debug($message, array $context = array())
    {
        return $this->log($this->levels_by_name['debug'], $message, $context);
    }
}
