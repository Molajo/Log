<?php
/**
 * Logger for Log
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log;

use Psr\Log\LoggerInterface;

/**
 * Logger for Log
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Logger implements LoggerInterface
{
    use LogTrait;

    /**
     * Loggers
     *
     * @var    array
     * @since  1.0
     */
    protected $logger_adapters
        = array(
            'callback',
            'database',
            'dummy',
            'echo',
            'email',
            'errorlog',
            'file',
            'memory'
        );

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
     * Constructor
     *
     * @param   array $logger_requests
     * @param   array $logger_adapters
     *
     * @since   1.0.0
     */
    public function __construct(
        array $logger_requests = array(),
        array $logger_adapters = array()
    ) {
        $this->startLoggers($logger_requests);

        if (count($logger_adapters) > 0) {
            $this->logger_adapters = $logger_adapters;
        }
    }

    /**
     * Start Loggers
     *
     * @param   array $logger_requests
     *
     * @return  $this
     * @throws  \Psr\Log\InvalidArgumentException
     * @since   1.0.0
     */
    public function startLoggers(array $logger_requests = array())
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
     * @since   1.0.0
     */
    public function startLogger($name, $logger_type, $levels = array(), $context = array())
    {
        $logger_type = $this->editLoggerType($logger_type);
        $name        = $this->editLoggerName($name, $logger_type);

        $loggerClass = 'Molajo\\Log\\Adapter\\' . ucfirst(strtolower($logger_type)) . 'Logger';

        $loggerInstance       = new $loggerClass($context);
        $this->loggers[$name] = $loggerInstance;

        $this->registerLoggerLevels($name, $levels);

        return $this;
    }

    /**
     * Get a specified log
     *
     * @return  array()
     * @since   1.0.0
     */
    public function getLog($name)
    {
        return $this->loggers[strtolower($name)]->getLog();
    }

    /**
     * Clear a specified log
     *
     * @return  $this
     * @since   1.0.0
     */
    public function clearLog($name)
    {
        return $this->loggers[strtolower($name)]->clearLog();
    }

    /**
     * Logs with defined log level.
     *
     * @param   int    $level
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function log($level, $message, array $context = array())
    {
        if (in_array($level, $this->levels)) {
        } else {
            $level = $this->levels_by_name['info'];
        }

        $context['log_level_name'] = $this->levels_by_code[$level];

        foreach ($this->levels_by_loggers[$level] as $name) {
            $this->logLogger($name, $level, $message, $context);
        }

        return $this;
    }

    /**
     * Stop Logger
     *
     * @param   string $name
     *
     * @return  $this
     * @since   1.0.0
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
     * Edit Logger Type
     *
     * @param   string $logger_type
     *
     * @return  string
     * @since   1.0.0
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
     * @param   string $logger_type
     *
     * @return  string
     * @since   1.0.0
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
     * @since   1.0.0
     */
    protected function registerLoggerLevels($name, array $levels = array())
    {
        $new_loggers = $this->setLoggerLevels($name, $levels);

        if (count($new_loggers) === 0) {
            $this->setDefaultLoggerLevel($name);
        } else {
            $this->levels_by_loggers = $new_loggers;
        }

        return $this;
    }

    /**
     * Set Logger Levels
     *
     * @param   string $name
     * @param   array  $levels
     *
     * @return  array
     * @since   1.0.0
     */
    protected function setLoggerLevels($name, array $levels)
    {
        $found       = false;
        $new_loggers = array();

        foreach ($this->levels_by_loggers as $key => $list) {
            list($found, $new_loggers) = $this->setLoggerLevel($name, $levels, $key, $list, $new_loggers, $found);
        }

        if ($found === true) {
            return $new_loggers;
        }

        return array();
    }

    /**
     * Set Logger Level
     *
     * @param   string  $name
     * @param   array   $levels
     * @param   string  $key
     * @param   array   $list
     * @param   array   $new_loggers
     * @param   boolean $found
     *
     * @return  array
     * @since   1.0.0
     */
    protected function setLoggerLevel($name, array $levels, $key, $list, $new_loggers, $found)
    {
        if (in_array($key, $levels)) {
            $list[] = $name;
            array_unique($list);
            $found = true;
        }

        $new_loggers[$key] = $list;

        return array($found, $new_loggers);
    }

    /**
     * Level not defined for Logger -- default to info
     *
     * @param   string $name
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setDefaultLoggerLevel($name)
    {
        $default                      = $this->levels_by_loggers[200];
        $default[]                    = $name;
        $this->levels_by_loggers[200] = $default;

        return $this;
    }

    /**
     * Log to the specified logger registered for this level
     *
     * @param   string $name
     * @param   int    $level
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @throws  \Psr\Log\InvalidArgumentException
     * @since   1.0.0
     */
    protected function logLogger($name, $level, $message, array $context = array())
    {
        return $this->loggers[$name]->log($level, $message, $context);
    }
}
