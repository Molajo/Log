<?php
/**
 * Error Handling to PSR-3 Log Testing
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use CommonApi\Controller\ErrorHandlingInterface;
use ErrorException;
use Molajo\Log\Logger;
use Psr\Log\LoggerInterface;
use stdClass;

/**
 * Error Handling to PSR-3 Log Testing
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ErrorhandlingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Logger Object
     */
    protected $logger;

    /**
     * @var Errorhandling Object
     */
    protected $error_handler;

    /**
     * @covers  Molajo\Controller\Errorhandling::__construct
     * @covers  Molajo\Controller\Errorhandling::setError
     * @covers  Molajo\Controller\Errorhandling::setErrorPHPHandles
     * @covers  Molajo\Controller\Errorhandling::setErrorLogEntry
     * @covers  Molajo\Controller\Errorhandling::respectErrorReporting
     * @covers  Molajo\Controller\Errorhandling::setLogLevel
     * @covers  Molajo\Controller\Errorhandling::setLogLevelMoreControl
     * @covers  Molajo\Controller\Errorhandling::setLogLevelUsingMapping
     * @covers  Molajo\Controller\Errorhandling::validateLogLevel
     * @covers  Molajo\Controller\Errorhandling::createLogContextArray
     * @covers  Molajo\Controller\Errorhandling::log
     * @covers  Molajo\Controller\Errorhandling::throwErrorException
     */
    protected function setUp()
    {
        $loggers                     = array();
        $logger_request              = new stdClass();
        $logger_request->name        = 'Test1';
        $logger_request->logger_type = 'Memory';
        $logger_request->levels      = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context     = array();
        $loggers[]                   = $logger_request;

        $this->logger = new MockLogger($loggers);

        $this->error_handler = new MockErrorhandling($this->logger);

        set_error_handler(array($this->error_handler, 'setError'));
    }

    /**
     * @covers  Molajo\Controller\Errorhandling::__construct
     * @covers  Molajo\Controller\Errorhandling::setError
     * @covers  Molajo\Controller\Errorhandling::setErrorPHPHandles
     * @covers  Molajo\Controller\Errorhandling::setErrorLogEntry
     * @covers  Molajo\Controller\Errorhandling::respectErrorReporting
     * @covers  Molajo\Controller\Errorhandling::setLogLevel
     * @covers  Molajo\Controller\Errorhandling::setLogLevelMoreControl
     * @covers  Molajo\Controller\Errorhandling::setLogLevelUsingMapping
     * @covers  Molajo\Controller\Errorhandling::validateLogLevel
     * @covers  Molajo\Controller\Errorhandling::createLogContextArray
     * @covers  Molajo\Controller\Errorhandling::log
     * @covers  Molajo\Controller\Errorhandling::throwErrorException
     */
    public function testTriggerErrorSetLogLevel()
    {
        $log_level = 200;
        $message   = 'Person logged on.';

        trigger_error('Person logged on.', E_USER_ERROR);

        $results = $this->logger->getLog('Test1');

        $this->assertTrue(is_array($results));
        foreach ($results as $row) {
            $this->assertEquals(19, strlen($row->entry_date));
            $this->assertEquals(1, count($results));
            $this->assertEquals($log_level, $row->level);
            $this->assertEquals($message, $row->message);
            $this->assertEquals('info', $row->level_name);
        }

        return $this;
    }

    /**
     * @covers  Molajo\Controller\Errorhandling::__construct
     * @covers  Molajo\Controller\Errorhandling::setError
     * @covers  Molajo\Controller\Errorhandling::setErrorPHPHandles
     * @covers  Molajo\Controller\Errorhandling::setErrorLogEntry
     * @covers  Molajo\Controller\Errorhandling::respectErrorReporting
     * @covers  Molajo\Controller\Errorhandling::setLogLevel
     * @covers  Molajo\Controller\Errorhandling::setLogLevelMoreControl
     * @covers  Molajo\Controller\Errorhandling::setLogLevelUsingMapping
     * @covers  Molajo\Controller\Errorhandling::validateLogLevel
     * @covers  Molajo\Controller\Errorhandling::createLogContextArray
     * @covers  Molajo\Controller\Errorhandling::log
     * @covers  Molajo\Controller\Errorhandling::throwErrorException
     */
    public function testRespectErrorReportingOff()
    {
        $log_level = 200;
        $message   = 'Person logged on.';

        error_reporting(0);
        trigger_error('Person logged on.', E_USER_NOTICE);

        $results = $this->logger->getLog('Test1');

        $this->assertEquals(null, $results);

        return $this;
    }

    /**
     * @covers  Molajo\Controller\Errorhandling::__construct
     * @covers  Molajo\Controller\Errorhandling::setError
     * @covers  Molajo\Controller\Errorhandling::setErrorPHPHandles
     * @covers  Molajo\Controller\Errorhandling::setErrorLogEntry
     * @covers  Molajo\Controller\Errorhandling::respectErrorReporting
     * @covers  Molajo\Controller\Errorhandling::setLogLevel
     * @covers  Molajo\Controller\Errorhandling::setLogLevelMoreControl
     * @covers  Molajo\Controller\Errorhandling::setLogLevelUsingMapping
     * @covers  Molajo\Controller\Errorhandling::validateLogLevel
     * @covers  Molajo\Controller\Errorhandling::createLogContextArray
     * @covers  Molajo\Controller\Errorhandling::log
     * @covers  Molajo\Controller\Errorhandling::throwErrorException
     */
    public function testRespectErrorReportingOnMapToLogLevel()
    {
        $message = 'Person logged on.';

        error_reporting(E_ALL & ~E_NOTICE);
        trigger_error('Person logged on.', E_USER_NOTICE);

        $results = $this->logger->getLog('Test1');

        $this->assertTrue(is_array($results));

        return $this;
    }

    /**
     * @covers  Molajo\Controller\Errorhandling::__construct
     * @covers  Molajo\Controller\Errorhandling::setError
     * @covers  Molajo\Controller\Errorhandling::setErrorPHPHandles
     * @covers  Molajo\Controller\Errorhandling::setErrorLogEntry
     * @covers  Molajo\Controller\Errorhandling::respectErrorReporting
     * @covers  Molajo\Controller\Errorhandling::setLogLevel
     * @covers  Molajo\Controller\Errorhandling::setLogLevelMoreControl
     * @covers  Molajo\Controller\Errorhandling::setLogLevelUsingMapping
     * @covers  Molajo\Controller\Errorhandling::validateLogLevel
     * @covers  Molajo\Controller\Errorhandling::createLogContextArray
     * @covers  Molajo\Controller\Errorhandling::log
     * @covers  Molajo\Controller\Errorhandling::throwErrorException
     */
    public function testMapWarningToWarning()
    {
        $message = 'Bad person logged on.';

        error_reporting(E_ALL & ~E_NOTICE);
        trigger_error($message, E_USER_WARNING);

        $results = $this->logger->getLog('Test1');

        $this->assertTrue(is_array($results));

        foreach ($results as $row) {
            $this->assertEquals(19, strlen($row->entry_date));
            $this->assertEquals(1, count($results));
            $this->assertEquals(300, $row->level);
            $this->assertEquals($message, $row->message);
            $this->assertEquals('warning', $row->level_name);
        }

        return $this;
    }

    /**
     * @covers  Molajo\Controller\Errorhandling::__construct
     * @covers  Molajo\Controller\Errorhandling::setError
     * @covers  Molajo\Controller\Errorhandling::setErrorPHPHandles
     * @covers  Molajo\Controller\Errorhandling::setErrorLogEntry
     * @covers  Molajo\Controller\Errorhandling::respectErrorReporting
     * @covers  Molajo\Controller\Errorhandling::setLogLevel
     * @covers  Molajo\Controller\Errorhandling::setLogLevelMoreControl
     * @covers  Molajo\Controller\Errorhandling::setLogLevelUsingMapping
     * @covers  Molajo\Controller\Errorhandling::validateLogLevel
     * @covers  Molajo\Controller\Errorhandling::createLogContextArray
     * @covers  Molajo\Controller\Errorhandling::log
     * @covers  Molajo\Controller\Errorhandling::throwErrorException
     */
    public function testLetPHPHandleLogPassIn0()
    {
        $log_level = 0;
        $message   = 'Person logged on.';

        error_reporting(E_ALL & ~E_NOTICE);
        trigger_error($message, E_USER_WARNING);

        $results = $this->logger->getLog('Test1');

        $this->assertEquals(null, $results);

        return $this;
    }

    /**
     * @covers  Molajo\Controller\Errorhandling::__construct
     * @covers  Molajo\Controller\Errorhandling::setError
     * @covers  Molajo\Controller\Errorhandling::setErrorPHPHandles
     * @covers  Molajo\Controller\Errorhandling::setErrorLogEntry
     * @covers  Molajo\Controller\Errorhandling::respectErrorReporting
     * @covers  Molajo\Controller\Errorhandling::setLogLevel
     * @covers  Molajo\Controller\Errorhandling::setLogLevelMoreControl
     * @covers  Molajo\Controller\Errorhandling::setLogLevelUsingMapping
     * @covers  Molajo\Controller\Errorhandling::validateLogLevel
     * @covers  Molajo\Controller\Errorhandling::createLogContextArray
     * @covers  Molajo\Controller\Errorhandling::log
     * @covers  Molajo\Controller\Errorhandling::throwErrorException
     */
    public function testLetPHPHandleOneTakeTheNext()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $message = 'Person logged on.';

        $loggers                     = array();
        $logger_request              = new stdClass();
        $logger_request->name        = 'Test1';
        $logger_request->logger_type = 'Memory';
        $logger_request->levels      = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context     = array();
        $loggers[]                   = $logger_request;

        $logger = new MockLogger($loggers);

        $error_number_array
            = array(
            E_NOTICE          => 200,
            E_DEPRECATED      => 0,
            E_STRICT          => 0,
            E_WARNING         => 0,
            E_ERROR           => 0,
            E_USER_NOTICE     => 200,
            E_USER_DEPRECATED => 0,
            E_USER_WARNING    => 0,
            E_USER_ERROR      => 0
        );

        $error_handler = new MockErrorhandling($logger, $error_number_array);

        set_error_handler(array($error_handler, 'setError'));

        /** Warnings are ignored */
        trigger_error($message, E_USER_WARNING);
        $results = $logger->getLog('Test1');
        $this->assertEquals(null, $results);

        /** Notices are logged */
        trigger_error($message, E_USER_NOTICE);
        $results = $logger->getLog('Test1');

        $this->assertTrue(is_array($results));
        foreach ($results as $row) {
            $this->assertEquals(19, strlen($row->entry_date));
            $this->assertEquals(1, count($results));
            $this->assertEquals(200, $row->level);
            $this->assertEquals($message, $row->message);
            $this->assertEquals('info', $row->level_name);
        }

        return $this;
    }

    /**
     * @covers  Molajo\Controller\Errorhandling::__construct
     * @covers  Molajo\Controller\Errorhandling::setError
     * @covers  Molajo\Controller\Errorhandling::setErrorPHPHandles
     * @covers  Molajo\Controller\Errorhandling::setErrorLogEntry
     * @covers  Molajo\Controller\Errorhandling::respectErrorReporting
     * @covers  Molajo\Controller\Errorhandling::setLogLevel
     * @covers  Molajo\Controller\Errorhandling::setLogLevelMoreControl
     * @covers  Molajo\Controller\Errorhandling::setLogLevelUsingMapping
     * @covers  Molajo\Controller\Errorhandling::validateLogLevel
     * @covers  Molajo\Controller\Errorhandling::createLogContextArray
     * @covers  Molajo\Controller\Errorhandling::log
     * @covers  Molajo\Controller\Errorhandling::throwErrorException
     *
     * @expectedException ErrorException
     */
    public function testThrowException()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $message = 'Person logged on.';

        $loggers                     = array();
        $logger_request              = new stdClass();
        $logger_request->name        = 'Test1';
        $logger_request->logger_type = 'Memory';
        $logger_request->levels      = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context     = array();
        $loggers[]                   = $logger_request;

        $logger = new MockLogger($loggers);

        $error_number_array
            = array(
            E_NOTICE          => 200,
            E_DEPRECATED      => 0,
            E_STRICT          => 0,
            E_WARNING         => 0,
            E_ERROR           => 0,
            E_USER_NOTICE     => 200,
            E_USER_DEPRECATED => 0,
            E_USER_WARNING    => 0,
            E_USER_ERROR      => 999
        );

        $error_handler = new MockErrorhandling($logger, $error_number_array);

        set_error_handler(array($error_handler, 'setError'));

        /** Exception */
        trigger_error($message, E_USER_ERROR);

        return $this;
    }
}

class MockErrorhandling extends Errorhandling implements ErrorHandlingInterface
{
    /**
     * Method is called by PHP for errors if it has been assigned the PHP set_error_handler in the application
     *
     * @param   integer $error_number
     * @param   string  $message
     * @param   string  $file
     * @param   integer $line_number
     * @param   array   $context
     *
     * @return  $this
     * @throws  \Molajo\Exception\ErrorThrownAsException
     * @since   1.0.0
     */
    public function setError($error_number, $message, $file, $line_number, array $context = array())
    {
        parent::setError($error_number, $message, $file, $line_number, $context);
    }
}

class MockLogger extends Logger implements LoggerInterface
{
    /**
     * Logs with defined log level.
     *
     * @param   int    $log_level
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function log($log_level, $message, array $context = array())
    {
        parent::log($log_level, $message, $context);
    }
}
