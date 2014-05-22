<?php
/**
 * Error Handling Controller
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Controller;

use stdClass;
use CommonApi\Controller\ErrorHandlingInterface;
use CommonApi\Exception\ErrorThrownAsException;

/**
 * Error Handling Controller
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ErrorHandling implements ErrorHandlingInterface
{
    /**
     * Logger
     *
     * @var    object
     * @since  1.0
     */
    protected $logger_instance = null;

    /**
     * Error Code Array
     *
     * @var    boolean
     * @since  1.0
     */
    protected $error_number_array
        = array(
            E_NOTICE          => 'NOTICE',
            E_DEPRECATED      => 'NOTICE',
            E_STRICT          => 'NOTICE',
            E_WARNING         => 'WARNING',
            E_ERROR           => 'FATAL',

            E_USER_NOTICE     => 'NOTICE',
            E_USER_DEPRECATED => 'NOTICE',
            E_USER_WARNING    => 'WARNING',
            E_USER_ERROR      => 'FATAL',
        );

    /**
     * Error Code to Message Array
     *
     * @var    string
     * @since  1.0
     */
    protected $error_number_to_message
        = array(
            403 => 'message_not_authorised',
            404 => 'message_not_found',
            500 => 'message_internal_server_error',
            503 => 'message_offline_number_switch'
        );

    /**
     * Class Constructor
     *
     * @param  string $error_theme
     *
     * @since  1.0
     */
    public function __construct(
        $error_theme = 'Molajo\\Themes\\System')
    {
        //respect error_reporting() and $code;

        $this->message_offline_number_switch = $message_offline_number_switch;
    }

    /**
     * Set Error Message
     *
     * @param   int    $error_number
     * @param   string $message
     * @param   string $file
     * @param   string $line_number
     *
     * @return  object|stdClass
     * @throws  \CommonApi\Exception\ErrorThrownAsException
     * @since   1.0
     */
    public function setError($error_number = 0, $message = '', $file = '', $line_number = '')
    {
        $error_object               = new stdClass();
        $error_object->error_number = $error_number;
        if ($message === '') {
            $error_object->message = $this->setErrorMessage($error_number);
        } else {
            $error_object->message = $message;
        }
        $error_object->file        = $file;
        $error_object->line_number = $line_number;

        $error_object = $this->setThemePageView($error_number, $error_object);

        return $error_object;
    }

    /**
     * Set Error Message
     *
     * @param   integer $error_number
     *
     * @returns string
     * @since   1.0.0
     * @throws  \CommonApi\Exception\ErrorThrownAsException
     */
    protected function setErrorMessage($error_number)
    {
        if (isset($this->error_number_to_message[$error_number])) {
        } else {
            throw new ErrorThrownAsException($this->message_internal_server_error, $error_number);
        }

        $value = $this->error_number_to_message[$error_number];

        return $this->$value;
    }

    /**
     * Set Theme and Page View
     *
     * @param   integer  $error_number
     * @param   stdClass $error_object
     *
     * @returns object
     * @since   1.0.0
     */
    protected function setThemePageView($error_number, $error_object)
    {
        if ($error_number == 503) {
            $error_object->theme_namespace = $this->error_theme;
            $error_object->page_namespace  = $this->error_page_offline_number_view;
        } else {
            $error_object->theme_namespace = $this->error_theme;
            $error_object->page_namespace  = $this->error_page_view;
        }

        return $error_object;
    }
}
