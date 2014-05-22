<?php
/**
 * Exception Handling Class
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use Exception;

/**
 * Class Exception
 *
 * @package  Molajo
 * @since    1.0
 */

/**
 * Exception Handling Class
 *
 * In the ContainerEntry Class
 *  - Initialise uses set_exception_handler to set the exception_handler method as the Exception Handler.
 *  - Initialise uses set_error_handler to set the error_handler method to handle PHP errors.
 *  - error_handler method throws an ErrorException, passing those errors into the exception_handler method
 *  - exception handler instantiates this class, passing in the Exception message, code and Exception
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ExceptionHandling extends Exception
{
    /**
     * Class Constructor
     *
     * @since  1.0
     */
    public function __construct()
    {
        set_exception_handler(array($this, 'handleException'));
    }

    /**
     * Format Custom Message
     *
     * @param   null $title
     * @param   null $message
     * @param   null $code
     * @param   int  $display_file
     * @param   int  $display_line
     * @param   int  $display_stack_trace
     * @param   int  $terminate
     *
     * @return  string
     * @since   1.0
     */
    public function handleException(
        $title = null,
        $message = null,
        $code = null,
        $display_file = 1,
        $display_line = 1,
        $display_stack_trace = 1,
        $terminate = 1
    ) {

        if ($title === null) {
            $title = 'Molajo Exception Information';
        }

        if ($message === null) {
            $message = $this->getMessage();
        }

        if ($code === null) {
            $code = $this->getCode();
        }

        echo '<br /><strong>' . 'Molajo Exception Information' . '</strong> ' . '<br />';
        echo '<strong>Date: </strong>' . date('M d, Y h:iA') . '<br />';
        echo '<strong>Message: </strong>' . $title . '<br />' . $message . '<br />';

        if ($code === null) {
        } else {
            echo '<strong>Code: </strong>' . $this->getCode() . '<br />';
        }

        if ($display_file == 1) {
            echo '<strong>File: </strong>' . $this->getFile() . '<br />';
        }

        if ($display_line == 1) {
            echo '<strong>Line: </strong>' . $this->getLine() . '<br />';
        }

        if ($display_stack_trace == 1) {
            echo '<strong>Stack Trace: </strong><br />';
            echo '<pre>';
            echo $this->getTraceAsString();
            echo '</pre>';
        }

        if ($terminate == 1) {
            echo 'Application will now terminate.';
        }

        if ($terminate == 1) {
            die;
        }
    }
}
