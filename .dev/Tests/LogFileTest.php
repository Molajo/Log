<?php
/**
 * Log Test
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Log\Test;

defined('MOLAJO') or die;

use DateTime;

/**
 * Log Test
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class LogTest extends \PHPUnit_Framework_TestCase
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
        $name = 'Test1';
        $logger_type = 'Dummy';
        $levels = array(100, 200, 250, 300, 400, 500, 550, 600);
        $context = array();

        $class = 'Molajo\\Log\\Adapter';
        $this->log = new $class($name, $logger_type, $levels, $context);

        return;
    }

    /**
     * Testing the start logger function from contructor
     *
     * @covers Molajo\Log\Adapter::__construct
     */
    public function testStartLoggerFromConstruct ()
    {
        $loggers = $this->log->getLoggers();

        $this->assertEquals(1, count($loggers));
        $this->assertTrue(isset($loggers['test1']));
    }

    /**
     * Testing the start logger function thru the startlogger
     *
     * @covers Molajo\Log\Adapter::startLogger
     */
    public function testStartLoggerNormally ()
    {
        $name = 'Test2';
        $logger_type = 'Echo';
        $levels = array(100, 200, 250, 300, 400, 500, 550, 600);
        $context = array();

        $this->log->startLogger($name, $logger_type, $levels, $context);

        $loggers = $this->log->getLoggers();

        $this->assertEquals(2, count($loggers));
        $this->assertTrue(isset($loggers['test2']));
    }

    /**
     * Testing the start logger function thru the startlogger
     *
     * @covers Molajo\Log\Adapter::startLogger
     */
    public function testGetLoggers ()
    {
        $loggers = $this->log->getLoggers();

        $this->assertEquals(1, count($loggers));
        $this->assertTrue(isset($loggers['test1']));

    }

    /**
     * Testing the start logger function thru the startlogger
     *
     * @covers Molajo\Log\Adapter::stopLogger
     */
    public function testStopLogger ()
    {
        $this->log->stopLogger('Test1');

        $loggers = $this->log->getLoggers();

        $this->assertEquals(0, count($loggers));
        $this->assertFalse(isset($loggers['test1']));
    }

    /**
     * Testing the start logger function thru the startlogger
     *
     * @covers Molajo\Log\Adapter::stopLogger
     */
    public function testSetTimezone ()
    {
        $this->log->setTimezone('America/Chicago');
        $results = $this->log->getTimezone();
        $this->assertEquals('America/Chicago', $results);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }
}
