<?php
/**
 * Log Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Log\Adapter;

use stdClass;

/**
 * Log Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class CallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Log Object
     */
    protected $logger;

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    protected function setUp()
    {
        $loggers                     = array();
        $logger_request              = new stdClass();
        $logger_request->name        = 'Test1';
        $logger_request->logger_type = 'Callback';
        $logger_request->levels      = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context     = array();

        $logging = function ($log_entry) {
            $this->assertEquals(19, strlen($log_entry->entry_date));
        };

        $logger_request->context['callback'] = $logging;
        $loggers[]                           = $logger_request;

        $class        = 'Molajo\\Log\\Logger';
        $this->logger = new $class($loggers);
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testEmergency()
    {
        $level   = 600;
        $message = 'Hello';
        $context = array();

        $results = $this->logger->emergency($message, $context);

        return $this;
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testAlert()
    {
        $level   = 550;
        $message = 'Hello';
        $context = array();

        $this->logger->alert($message, $context);

        return $this;
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testCritical()
    {
        $level   = 500;
        $message = 'Hello';
        $context = array();

        $this->logger->critical($message, $context);

        return $this;
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testError()
    {
        $level   = 400;
        $message = 'Hello';
        $context = array();

        $this->logger->error($message, $context);

        return $this;
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testWarning()
    {
        $level   = 300;
        $message = 'Hello';
        $context = array();

        $this->logger->warning($message, $context);

        return $this;
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testNotice()
    {
        $level   = 250;
        $message = 'Hello';
        $context = array();

        $this->logger->notice($message, $context);

        return $this;
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testInfo()
    {
        $level   = 200;
        $message = 'Hello';
        $context = array();

        $this->logger->info($message, $context);

        return $this;
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testDebugInfo()
    {
        $level   = 100;
        $message = 'Hello';
        $context = array();

        $this->logger->debug($message, $context);

        return $this;
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testLog()
    {
        $level   = 200;
        $message = 'Hello';
        $context = array();

        $this->logger->log($level, $message, $context);

        return $this;
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testClear()
    {
        $level   = 200;
        $message = 'Hello';
        $context = array();

        $this->logger->log($level, 'Hello 1', $context);
        $this->logger->log($level, 'Hello 2', $context);
        $this->logger->log($level, 'Hello 3', $context);
        $this->logger->log($level, 'Hello 4', $context);
        $this->logger->log($level, 'Hello 5', $context);

        return $this;
    }

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::getLog
     * @covers Molajo\Log\Logger::clearLog
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     *
     * @covers Molajo\Log\Adapter\CallbackLogger::log
     *
     * @covers Molajo\Log\Adapter\AbstractLogger::__construct
     * @covers Molajo\Log\Adapter\AbstractLogger::getLog
     * @covers Molajo\Log\Adapter\AbstractLogger::clearLog
     * @covers Molajo\Log\Adapter\AbstractLogger::log
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogDateTime
     * @covers Molajo\Log\Adapter\AbstractLogger::setLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateElapsedTime
     * @covers Molajo\Log\Adapter\AbstractLogger::getMicrotimeFloat
     * @covers Molajo\Log\Adapter\AbstractLogger::calculateMemoryUsage
     * @covers Molajo\Log\Adapter\AbstractLogger::processContextArray
     * @covers Molajo\Log\Adapter\AbstractLogger::createLogEntryFields
     * @covers Molajo\Log\Adapter\AbstractLogger::setMaintainLog
     * @covers Molajo\Log\Adapter\AbstractLogger::setFileLocation
     * @covers Molajo\Log\Adapter\AbstractLogger::setColumns
     * @covers Molajo\Log\Adapter\AbstractLogger::saveLog
     */
    public function testStopLogger()
    {
        $this->logger->stopLogger('test1');

        return $this;
    }
}
