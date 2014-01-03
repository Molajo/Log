<?php
/**
 * Log Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Log\Test;

use DateTime;

/**
 * Log Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class EchoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Log Object
     */
    protected $log;

    /**
     * Initialises Adapter
     */
    protected function setUp()
    {
        $name        = 'Test1';
        $logger_type = 'Echo';
        $levels      = array(100, 200, 250, 300, 400, 500, 550, 600);
        $context     = array();

        $class     = 'Molajo\\Log\\Adapter';
        $this->log = new $class($name, $logger_type, $levels, $context);

        return;
    }

    /**
     * Testing the start logger function from contructor
     *
     * @covers Molajo\Log\Adapter::__construct
     */
    public function testStartLoggerFromConstruct()
    {
        $loggers = $this->log->getLoggers();

        $this->assertEquals(1, count($loggers));
        $this->assertTrue(isset($loggers['test1']));
    }

    /**
     * Emergency - 600
     *
     * @covers Molajo\Log\Adapter::emergency
     */
    public function testEmergency()
    {
        $level   = 600;
        $message = 'Hello';
        $context = array();

        $this->log->emergency($message, $context);

        return $this;
    }

    /**
     * Alert - 550
     *
     * @covers Molajo\Log\Adapter::alert
     */
    public function testAlert()
    {
        $level   = 550;
        $message = 'Hello';
        $context = array();

        $this->log->alert($message, $context);

        return $this;
    }

    /**
     * Critical - 500
     *
     * @covers Molajo\Log\Adapter::critical
     */
    public function testCritical()
    {
        $level   = 550;
        $message = 'Hello';
        $context = array();

        $this->log->critical($message, $context);

        return $this;
    }

    /**
     * Error - 400
     *
     * @covers Molajo\Log\Adapter::error
     */
    public function testError()
    {
        $level   = 400;
        $message = 'Hello';
        $context = array();

        $this->log->error($message, $context);

        return $this;
    }

    /**
     * Warning - 300
     *
     * @covers Molajo\Log\Adapter::warning
     */
    public function testWarning()
    {
        $level   = 300;
        $message = 'Hello';
        $context = array();

        $this->log->warning($message, $context);

        return $this;
    }

    /**
     * Notice - 250
     *
     * @covers Molajo\Log\Adapter::notice
     */
    public function testNotice()
    {
        $level   = 250;
        $message = 'Hello';
        $context = array();

        $this->log->notice($message, $context);

        return $this;
    }

    /**
     * Info - 200
     *
     * @covers Molajo\Log\Adapter::info
     */
    public function testInfo()
    {
        $level   = 200;
        $message = 'Hello';
        $context = array();

        $this->log->info($message, $context);

        return $this;
    }

    /**
     * Debug - 100
     *
     * @covers Molajo\Log\Adapter::info
     */
    public function debugInfo()
    {
        $level   = 100;
        $message = 'Hello';
        $context = array();

        $this->log->debug($message, $context);

        return $this;
    }

    /**
     * Debug - 100
     *
     * @covers Molajo\Log\Adapter::log
     */
    public function log()
    {
        $level   = 200;
        $message = 'Hello';
        $context = array();

        $this->log->log($level, $message, $context);

        return $this;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
}
