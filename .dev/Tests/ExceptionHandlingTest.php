<?php
/**
 * Exception Handling to PSR-3 Log Testing
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use CommonApi\Exception\ExceptionInterface;
use Exception;
use Molajo\Controller\ExceptionHandling;
use Molajo\Log\Logger;
use Psr\Log\LoggerInterface;
use stdClass;

/**
 * Exception Handling to PSR-3 Log Testing
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ExceptionHandlingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Logger Object
     */
    protected $logger;

    /**
     * @var ExceptionHandling Object
     */
    protected $exception_handler;

    /**
     * @covers Molajo\Controller\ExceptionHandling::__construct
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

        $this->logger = new MockLoggerExceptions($loggers);

        $this->exception_handler = new ExceptionHandling($this->logger);
    }

    /**
     * @covers Molajo\Controller\ExceptionHandling::__construct
     * @expectedException Exception
     */
    public function testTriggerExceptionSetLogLevel()
    {
        set_exception_handler(array($this->exception_handler, 'handleException'));
        $hurt = new ThisIsGoingToHurt();
        $hurt->bam();
        throw new Exception('now what?');
    }
}

class MockLoggerExceptions extends Logger implements LoggerInterface
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

class ThisIsGoingToHurt
{
    public function bam()
    {
        try
        {
            $x = 10/0;
        }
        catch (Exception $e)
        {
            throw new Exception('now what?');
        }
    }
}
