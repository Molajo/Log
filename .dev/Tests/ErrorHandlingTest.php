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
use Molajo\Log\Logger;
use stdClass;
use Psr\Log\LoggerInterface;

/**
 * Error Handling to PSR-3 Log Testing
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class ErrorHandlingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Logger Object
     */
    protected $logger;

    /**
     * @var ErrorHandling Object
     */
    protected $error_handler;

    /**
     * @covers Molajo\Controller\ErrorHandling::__construct
     * @covers Molajo\Controller\ErrorHandling::setError
     * @covers Molajo\Controller\ErrorHandling::respectErrorReporting
     * @covers Molajo\Controller\ErrorHandling::throwErrorException
     * @covers Molajo\Controller\ErrorHandling::setLogLevel
     * @covers Molajo\Controller\ErrorHandling::setSpecificLogLevel
     * @covers Molajo\Controller\ErrorHandling::setMappedLogLevel
     * @covers Molajo\Controller\ErrorHandling::createLogContextArray
     * @covers Molajo\Controller\ErrorHandling::log
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

        $this->error_handler = new MockErrorHandling($this->logger);

        set_error_handler(array($this->error_handler, 'setError'));
    }

    /**
     * @covers Molajo\Controller\ErrorHandling::__construct
     * @covers Molajo\Controller\ErrorHandling::setError
     * @covers Molajo\Controller\ErrorHandling::respectErrorReporting
     * @covers Molajo\Controller\ErrorHandling::throwErrorException
     * @covers Molajo\Controller\ErrorHandling::setLogLevel
     * @covers Molajo\Controller\ErrorHandling::setSpecificLogLevel
     * @covers Molajo\Controller\ErrorHandling::setMappedLogLevel
     * @covers Molajo\Controller\ErrorHandling::createLogContextArray
     * @covers Molajo\Controller\ErrorHandling::log
     */
    public function testTriggerError()
    {
        $level          = 200;
        $message        = 'Person logged on.';
        $context        = array();
        $context['dog'] = 'food';

        trigger_error('Person logged on.', E_USER_NOTICE);

        $results = $this->logger->getLog('Test1');

        $this->assertTrue(is_array($results));
        foreach ($results as $row) {
            $this->assertEquals(19, strlen($row->entry_date));
            $this->assertEquals(1, count($results));
            $this->assertEquals($level, $row->level);
            $this->assertEquals($message, $row->message);
            $this->assertEquals('info', $row->level_name);
        }

        return $this;
    }

}

class MockErrorHandling extends ErrorHandling implements ErrorHandlingInterface
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
     * @throws  \CommonApi\Exception\ErrorThrownAsException
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
     * @param   int    $level
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function log($level, $message, array $context = array())
    {
        parent::log($level, $message, $context);
    }
}
