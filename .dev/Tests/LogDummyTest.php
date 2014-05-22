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
class DummyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Log Object
     */
    protected $logger;

    /**
     * @covers Molajo\Log\Logger::__construct
     * @covers Molajo\Log\Logger::startLoggers
     * @covers Molajo\Log\Logger::startLogger
     * @covers Molajo\Log\Logger::editLoggerType
     * @covers Molajo\Log\Logger::editLoggerName
     * @covers Molajo\Log\Logger::registerLoggerLevels
     * @covers Molajo\Log\Logger::stopLogger
     * @covers Molajo\Log\Logger::log
     * @covers Molajo\Log\Logger::setLogDateTime
     * @covers Molajo\Log\Logger::logLogger
     * @covers Molajo\Log\Logger::emergency
     * @covers Molajo\Log\Logger::alert
     * @covers Molajo\Log\Logger::critical
     * @covers Molajo\Log\Logger::error
     * @covers Molajo\Log\Logger::warning
     * @covers Molajo\Log\Logger::notice
     * @covers Molajo\Log\Logger::info
     * @covers Molajo\Log\Logger::debug
     */
    protected function setUp()
    {
        $loggers                     = array();
        $logger_request              = new stdClass();
        $logger_request->name        = 'Test1';
        $logger_request->logger_type = 'Dummy';
        $logger_request->levels      = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context     = array();
        $loggers[]                   = $logger_request;

        $class                       = 'Molajo\\Log\\Logger';
        $this->logger                = new $class($loggers);

        return;
    }

    /**
     * Emergency - 600
     *
     * @covers Molajo\Log\Logger::emergency
     */
    public function testEmergency()
    {
        $level   = 600;
        $message = 'Hello';
        $context = array();

        $this->logger->emergency($message, $context);


        return $this;
    }

    /**
     * Alert - 550
     *
     * @covers Molajo\Log\Logger::alert
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
     * Critical - 500
     *
     * @covers Molajo\Log\Logger::critical
     */
    public function testCritical()
    {
        $level   = 550;
        $message = 'Hello';
        $context = array();

        $this->logger->critical($message, $context);

        return $this;
    }

    /**
     * Error - 400
     *
     * @covers Molajo\Log\Logger::error
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
     * Warning - 300
     *
     * @covers Molajo\Log\Logger::warning
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
     * Notice - 250
     *
     * @covers Molajo\Log\Logger::notice
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
     * Info - 200
     *
     * @covers Molajo\Log\Logger::info
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
     * Debug - 100
     *
     * @covers Molajo\Log\Logger::info
     */
    public function debugInfo()
    {
        $level   = 100;
        $message = 'Hello';
        $context = array();

        $this->logger->debug($message, $context);

        return $this;
    }

    /**
     * Debug - 100
     *
     * @covers Molajo\Log\Logger::log
     */
    public function log()
    {
        $level   = 200;
        $message = 'Hello';
        $context = array();

        $this->logger->log($level, $message, $context);

        return $this;
    }
}
