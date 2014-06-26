<?php
/**
 * Log Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Log\Adapter;

use DateTime;
use stdClass;

/**
 * Log Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class LogTest extends \PHPUnit_Framework_TestCase
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
     * @covers Molajo\Log\Adapter\FileLogger::log
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
        $loggers                                  = array();
        $logger_request                           = new stdClass();
        $logger_request->name                     = 'Test1';
        $logger_request->logger_type              = 'File';
        $logger_request->levels                   = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context                  = array();
        $logger_request->context['file_location'] = __DIR__ . '/FileLog.json';
        $loggers[]                                = $logger_request;
        $class                                    = 'Molajo\\Log\\Logger';
        $this->logger                             = new $class($loggers);

        return;
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
     * @covers Molajo\Log\Adapter\FileLogger::log
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
    public function testSendInAdapterList()
    {
        $loggers                                  = array();
        $logger_request                           = new stdClass();
        $logger_request->name                     = 'Test1';
        $logger_request->logger_type              = 'File';
        $logger_request->levels                   = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context                  = array();
        $logger_request->context['file_location'] = __DIR__ . '/FileLog.json';
        $loggers[]                                = $logger_request;
        $logger_adapters                          = array('dummy', 'memory');
        $class                                    = 'Molajo\\Log\\Logger';
        $this->logger                             = new $class($loggers, $logger_adapters);

        $level   = 600;
        $message = 'Hello';
        $context = array();

        $this->logger->emergency($message, $context);

        $results = json_decode(file_get_contents(__DIR__ . '/FileLog.json'));

        $this->assertTrue(is_array($results));
        foreach ($results as $row) {
            $this->assertEquals(19, strlen($row->entry_date));
            $this->assertEquals(1, count($results));
            $this->assertEquals($level, $row->level);
            $this->assertEquals($message, $row->message);
            $this->assertEquals('emergency', $row->level_name);
        }
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
     * @covers Molajo\Log\Adapter\MemoryLogger::log
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
    public function testStopLoggerDoesNotExist()
    {
        $this->logger->stopLogger('this-does-not-exist');

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
     * @covers Molajo\Log\Adapter\MemoryLogger::log
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
    public function testLogEntryFields()
    {
        $loggers                                     = array();
        $logger_request                              = new stdClass();
        $logger_request->name                        = 'Test1';
        $logger_request->logger_type                 = 'Memory';
        $logger_request->levels                      = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context                     = array();
        $log_entry_fields                            = array();
        $log_entry_fields['column1']                 = 1;
        $log_entry_fields['column2']                 = 2;
        $log_entry_fields['column3']                 = 3;
        $logger_request->context['log_entry_fields'] = $log_entry_fields;

        $loggers[]    = $logger_request;
        $class        = 'Molajo\\Log\\Logger';
        $this->logger = new $class($loggers);


        $level              = 200;
        $message            = 'Hello';
        $context            = array();
        $context['column1'] = 'this is sent in with the log record';

        $this->logger->log($level, $message, $context);

        $results = $this->logger->getLog('Test1');

        $this->assertTrue(is_array($results));
        foreach ($results as $row) {
            $this->assertEquals(19, strlen($row->entry_date));
            $this->assertEquals(1, count($results));
            $this->assertEquals($level, $row->level);
            $this->assertEquals($message, $row->message);
            $this->assertEquals('info', $row->level_name);
            $this->assertEquals('this is sent in with the log record', $row->column1);
            $this->assertEquals(2, $row->column2);
            $this->assertEquals(3, $row->column3);
        }
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
     * @covers Molajo\Log\Adapter\MemoryLogger::log
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
    public function testDoNotMaintainLog()
    {
        $loggers                                 = array();
        $logger_request                          = new stdClass();
        $logger_request->name                    = 'Test1';
        $logger_request->logger_type             = 'File';
        $logger_request->levels                  = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context                 = array();
        $logger_request->context['maintain_log'] = false;

        $loggers[]    = $logger_request;
        $class        = 'Molajo\\Log\\Logger';
        $this->logger = new $class($loggers);

        $level              = 200;
        $message            = 'Hello';
        $context            = array();
        $context['column1'] = 'this is sent in with the log record';

        $this->logger->log($level, $message, $context);

        $results = $this->logger->getLog('Test1');

        $this->assertEquals(0, count($results));

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
     * @covers Molajo\Log\Adapter\MemoryLogger::log
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
    public function testDoMaintainLog()
    {
        $loggers                                 = array();
        $logger_request                          = new stdClass();
        $logger_request->name                    = 'Test1';
        $logger_request->logger_type             = 'File';
        $logger_request->levels                  = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context                 = array();
        $logger_request->context['maintain_log'] = true;

        $loggers[]    = $logger_request;
        $class        = 'Molajo\\Log\\Logger';
        $this->logger = new $class($loggers);

        $level              = 200;
        $message            = 'Hello';
        $context            = array();
        $context['column1'] = 'this is sent in with the log record';

        $this->logger->log($level, $message, $context);

        $results = $this->logger->getLog('Test1');

        $this->assertEquals(1, count($results));

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
     * @covers Molajo\Log\Logger::setLoggerLevels
     * @covers Molajo\Log\Logger::setDefaultLoggerLevel
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
     * @covers Molajo\Log\Adapter\MemoryLogger::log
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
    public function testNoLogLevel()
    {
        $loggers                     = array();
        $logger_request              = new stdClass();
        $logger_request->name        = 'Test1';
        $logger_request->logger_type = 'File';
        $logger_request->levels      = array();
        $logger_request->context     = array();

        $loggers[]    = $logger_request;
        $class        = 'Molajo\\Log\\Logger';
        $this->logger = new $class($loggers);

        $message = 'Hello';
        $context = array();

        $this->logger->info($message);

        $results = $this->logger->getLog('Test1');

        $this->assertEquals(1, count($results));

        return $this;
    }
}
