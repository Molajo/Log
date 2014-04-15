<?php
/**
 * Adapter for Log
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log;

use Exception;
use Exception\Log\RuntimeException;
use CommonApi\Log\LoggerInterface;

/**
 * Adapter for Log
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Adapter implements LoggerInterface
{
    /**
     * Log levels
     *
     * @var    array
     * @since  1.0
     */
    protected $levels = array(
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
     * Logger Types
     *
     * @var    array
     * @since  1.0
     */
    protected $logger_types = array('callback', 'database', 'dummy', 'echo', 'email', 'text');

    /**
     * Loggers - current loggers and an array of log levels
     *
     * @var    array
     * @since  1.0
     */
    protected $loggers = array();

    /**
     * Emergency Logger
     *
     * @var    array
     * @since  1.0
     */
    protected $emergency_loggers = array();

    /**
     * Alert Logger
     *
     * @var    array
     * @since  1.0
     */
    protected $alert_loggers = array();

    /**
     * Critical Logger
     *
     * @var    array
     * @since  1.0
     */
    protected $critical_loggers = array();

    /**
     * Error Logger
     *
     * @var    array
     * @since  1.0
     */
    protected $error_loggers = array();

    /**
     * Warning Logger
     *
     * @var    array
     * @since  1.0
     */
    protected $warning_loggers = array();

    /**
     * Notice Logger
     *
     * @var    array
     * @since  1.0
     */
    protected $notice_loggers = array();

    /**
     * Info Logger
     *
     * @var    array
     * @since  1.0
     */
    protected $info_loggers = array();

    /**
     * Debug Logger
     *
     * @var    array
     * @since  1.0
     */
    protected $debug_loggers = array();

    /**
     * Context
     *
     * @var    array
     * @since  1.0
     */
    protected $context = array();

    /**
     * Timezone
     *
     * @var    string
     * @since  1.0
     */
    protected $timezone = '';

    /**
     * Log Adapter Handler
     *
     * @var     object
     * @since   1.0
     */
    public $adapterHandler;

    /**
     * Constructor
     *
     * @param   LoggerInterface $log
     * @param   string          $name
     * @param   string          $logger_type
     * @param   array           $levels
     * @param   array           $context
     *
     * @since   1.0
     */
    public function __construct(
        LoggerInterface $log,
        $name = '',
        $logger_type = '',
        $levels = array(),
        $context = array()
    ) {
        $this->setDefaultTimezone();

        $this->adapterHandler = $log;

        if ($name == '') {
        } else {
            $this->startLogger($name, $logger_type, $levels, $context);
        }

        return $this;
    }

    /**
     * Connect to the Log Adapter Handler
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  RuntimeException
     * @api
     */
    public function setLogger(LoggerInterface $logger)
    {
        try {
            $this->adapterHandler->setLogger($options);
        } catch (Exception $e) {

            throw new RuntimeException
            ('Log: Caught Exception: ' . $e->getMessage());
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
     * @throws  RuntimeException
     * @since   1.0
     */
    public function startLogger($name, $logger_type = 'echo', $levels = array(), $context = array())
    {
        $logger_type = strtolower($logger_type);
        if ($logger_type == '') {
            $logger_type = 'echo';
        }

        $name = strtolower($name);
        if ($name == '') {
            $name = $logger_type;
        }

        if (is_array($levels) && count($levels) > 0) {
        } else {
            foreach ($this->levels as $key => $value) {
                $levels[] = $key;
            }
        }

        foreach ($levels as $level) {

            switch ($level) {
                case 100:
                    if (in_array($name, $this->debug_loggers)) {
                    } else {
                        $this->debug_loggers[] = $name;
                    }
                    break;

                case 200:
                    if (in_array($name, $this->info_loggers)) {
                    } else {
                        $this->info_loggers[] = $name;
                    }
                    break;

                case 250:
                    if (in_array($name, $this->notice_loggers)) {
                    } else {
                        $this->notice_loggers[] = $name;
                    }
                    break;

                case 300:
                    if (in_array($name, $this->warning_loggers)) {
                    } else {
                        $this->warning_loggers[] = $name;
                    }
                    break;

                case 400:
                    if (in_array($name, $this->error_loggers)) {
                    } else {
                        $this->error_loggers[] = $name;
                    }
                    break;

                case 500:
                    if (in_array($name, $this->critical_loggers)) {
                    } else {
                        $this->critical_loggers[] = $name;
                    }
                    break;

                case 550:
                    if (in_array($name, $this->alert_loggers)) {
                    } else {
                        $this->alert_loggers[] = $name;
                    }
                    break;

                case 600:
                    if (in_array($name, $this->emergency_loggers)) {
                    } else {
                        $this->emergency_loggers[] = $name;
                    }
                    break;

                default:
                    throw new RuntimeException
                    ('Log startLogger: Logger Level is Invalid: ' . $level);
            }
        }

        $loggerClass          = 'Molajo\\Log\\Handler\\' . ucfirst(strtolower($logger_type)) . 'Logger';
        $loggerInstance       = new $loggerClass($context);
        $this->loggers[$name] = $loggerInstance;

        return $this;
    }

    /**
     * Stop Logger
     *
     * @param   string $name
     *
     * @return  $this
     * @throws  RuntimeException
     * @since   1.0
     */
    public function stopLogger($name)
    {
        $name = strtolower($name);

        if (isset($this->loggers[$name])) {
        } else {
            return $this;
        }

        if (isset($this->debug_loggers[$name])) {
            unset($this->debug_loggers[$name]);
        }
        if (isset($this->info_loggers[$name])) {
            unset($this->info_loggers[$name]);
        }
        if (isset($this->notice_loggers[$name])) {
            unset($this->notice_loggers[$name]);
        }
        if (isset($this->warning_loggers[$name])) {
            unset($this->warning_loggers[$name]);
        }
        if (isset($this->error_loggers[$name])) {
            unset($this->error_loggers[$name]);
        }
        if (isset($this->critical_loggers[$name])) {
            unset($this->critical_loggers[$name]);
        }
        if (isset($this->alert_loggers[$name])) {
            unset($this->alert_loggers[$name]);
        }
        if (isset($this->emergency_loggers[$name])) {
            unset($this->emergency_loggers[$name]);
        }

        unset($this->loggers[$name]);

        return $this;
    }

    /**
     * Start Logger and set log levels
     *
     * @return  array
     * @throws  RuntimeException
     * @since   1.0
     */
    public function getLoggers()
    {
        return $this->loggers;
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
        if (is_array($context)) {
        } else {
            $context = array();
        }

        $level = 600;

        $this->log($level, $message, $context);

        return $this;
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
        if (is_array($context)) {
        } else {
            $context = array();
        }

        $level = 550;

        $this->log($level, $message, $context);

        return $this;
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
        if (is_array($context)) {
        } else {
            $context = array();
        }

        $level = 500;

        $this->log($level, $message, $context);

        return $this;
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
        if (is_array($context)) {
        } else {
            $context = array();
        }

        $this->context = $context;

        $level = 400;

        $this->log($level, $message, $context);

        return $this;
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
        if (is_array($context)) {
        } else {
            $context = array();
        }

        $level = 300;

        $this->log($level, $message, $context);

        return $this;
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
        if (is_array($context)) {
        } else {
            $context = array();
        }

        $level = 250;

        $this->log($level, $message, $context);

        return $this;
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
        if (is_array($context)) {
        } else {
            $context = array();
        }

        $level = 200;

        $this->log($level, $message, $context);

        return $this;
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
        if (is_array($context)) {
        } else {
            $context = array();
        }

        $level = 100;

        $this->log($level, $message, $context);

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
     * @throws  RuntimeException
     * @since   1.0
     */
    public function log($level, $message, array $context = array())
    {
        if ((int)$level === 0) {
            $level = 200;
        }

        $level = (int)$level;

        if (isset($this->levels[$level])) {
        } else {
            throw new RuntimeException
            ('Log log: Logger Level is Invalid: ' . $level);
        }

        $this->levels[$level];

        switch ($level) {

            case 100:
                $log_to_array = $this->debug_loggers;
                break;

            case 200:
                $log_to_array = $this->info_loggers;
                break;

            case 250:
                $log_to_array = $this->notice_loggers;
                break;

            case 300:
                $log_to_array = $this->warning_loggers;
                break;

            case 400:
                $log_to_array = $this->error_loggers;
                break;

            case 500:
                $log_to_array = $this->critical_loggers;
                break;

            case 550:
                $log_to_array = $this->alert_loggers;
                break;

            case 600:
                $log_to_array = $this->emergency_loggers;
                break;

            default:
                throw new RuntimeException
                ('Log log: Logger Level is Invalid: ' . $level);
        }

        if (is_array($context)) {
        } else {
            $context = array();
        }

        $unique = array_unique($log_to_array);

        foreach ($unique as $name) {

            if (isset($this->loggers[$name])) {
            } else {
                throw new RuntimeException
                ('Log log: Logger Instance not available: ' . $name);
            }

            $this->adapterHandler = $this->loggers[$name];

            if (isset($context['datetime'])) {
            } else {
                $context['datetime'] = new DateTime($this->timezone);
            }

            try {
                $this->adapterHandler->log($message, $level, $context);
            } catch (Exception $e) {

                throw new RuntimeException
                ('Log: log Failed for ' . $name . ' with Message: ' . $e->getMessage());
            }
        }

        return $this;
    }

    /**
     * Set the Timezone
     *
     * @param   string $timezone
     *
     * @return  $this
     * @since   1.0
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get the Timezone
     *
     * @return  string
     * @since   1.0
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set timezone given default options
     *
     * @return  $this
     * @since   1.0
     */
    protected function setDefaultTimezone()
    {
        $timezone = '';

        if (is_array($this->context)) {
        } else {
            return $this;
        }

        if (isset($this->context['timezone'])) {
            $timezone = $this->context['timezone'];
        }

        if ($timezone === '') {
            if (ini_get('date.timezone')) {
                $timezone = ini_get('date.timezone');
            }
        }

        if ($timezone === '') {
            $timezone = 'UTC';
        }

        ini_set('date.timezone', $timezone);
        $this->context['timezone'] = $timezone;

        $this->setTimezone($timezone);

        return $this;
    }
}
