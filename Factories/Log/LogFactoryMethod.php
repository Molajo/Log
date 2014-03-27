<?php
/**
 * Log Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Log;

use Exception;
use CommonApi\Exception\RuntimeException;
use Molajo\IoC\FactoryMethodBase;
use CommonApi\IoC\FactoryInterface;

/**
 * Log Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class LogFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0
     */
    public function __construct(array $options = array())
    {
        $options['product_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = true;
        $options['product_namespace']        = 'Molajo\\Log\\Adapter';
        $options['product_namespace']        = null;

        parent::__construct($options);
    }

    /**
     * Retrieve a list of Interface dependencies and return the data ot the controller.
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function setDependencies(array $reflection = null)
    {
        $this->dependencies                = array();
        $this->dependencies['Runtimedata'] = array();

        return $this->dependencies;
    }

    /**
     * Instantiate Class
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function instantiateClass()
    {
        $log_service = $this->dependencies['Runtimedata']->application->parameters->log_service;
        $log_service = 0;
        if ((int)$log_service === 0) {
            return $this;
        }

        return;
        $name        = 'System Log';
        $logger_type = 'Echo';
        $levels      = array(100, 200, 250, 300, 400, 500, 550, 600);
        $context     = array();

        $this->setContent($context);

        try {

            $service              = $this->product_namespace;
            $this->product_result = new $service($name, $logger_type, $levels, $context);
        } catch (Exception $e) {

            throw new RuntimeException
            ('IoC Factory Method Adapter Instance Failed for ' . $this->product_namespace
            . ' failed.' . $e->getMessage());
        }

        return $this->product_result;
    }

    /**
     * Set the Context Array
     *
     * @param   array $context
     *
     * @return  $this
     * @since   1.0
     */
    public function setContent($context = array())
    {
        if (is_array($context) && count($context) > 0) {
        } else {
            return $this;
        }
        /**
         * if (isset($context['userid'])) {
         * $this->timezone = $context['userid'];
         * }
         *
         * if (isset($context['ip_address'])) {
         * $this->timezone = $context['ip_address'];
         * }
         *
         * if (isset($context['url'])) {
         * $this->url = $context['url'];
         * }
         *
         * if (isset($context['method'])) {
         * $this->method = $context['method'];
         * }
         *
         * if (isset($context['type'])) {
         * $this->method = $context['type'];
         * }
         *
         * if (is_array($context['levels']) && count($context['levels']) > 0) {
         * $levels = $context['levels'];
         * }
         */
        return $this;
    }

    /**
     * Set Callback options
     *
     * @param   array $context
     *
     * @return  array
     * @since   1.0
     */
    public function setCallbackOptions($context = array())
    {
        // $context['line_separator'] <br /> or /n (default)
        return $context;
    }

    /**
     * Set ChromePHP options
     *
     * @param   array $context
     *
     * @return  array
     * @since   1.0
     */
    public function setChromePHPOptions($context = array())
    {
        //No addition $option[] values. However, this option requires using Google Chrome and installing this
        //Google Chrome extension: https://chrome.google.com/webstore/detail/noaneddfkdjfnfdakjjmocngnfkfehhd
        //and https://github.com/ccampbell/chromephp
        return $context;
    }

    /**
     * Set Database options
     *
     * @param   array $context
     *
     * @return  array
     * @since   1.0
     */
    public function connectOptions($context = array())
    {
        // $context['dbo'] - Services::Database();
        // $context['db_table'] - #__log
        return $context;
    }

    /**
     * Set Dummy options
     *
     * @param   array $context
     *
     * @return  array
     * @since   1.0
     */
    public function setDummyOptions($context = array())
    {
        //
        return $context;
    }

    /**
     * Set Echo options
     *
     * @param   array $context
     *
     * @return  array
     * @since   1.0
     */
    public function setEchoOptions($context = array())
    {
        // $context['line_separator'] <br /> or /n (default)
        return $context;
    }

    /**
     * Set Fire PHP options
     *
     * @param   array $context
     *
     * @return  array
     * @since   1.0
     */
    public function setFirePHPOptions($context = array())
    {
        // $context['line_separator'] <br /> or /n (default)
        return $context;
    }

    /**
     * Set Email options
     *
     * @param   array $context
     *
     * @return  array
     * @since   1.0
     */
    public function setEmailOptions($context = array())
    {
        // Services::Application()->get('mailer_mail_from'),
        // Services::Application()->get('mailer_mail_from_name')
        // $this->options['recipient'] = Services::Application()->get('mailer_mail_from_email_address');
        // $this->options['subject'] = $this->language->translate('LOG_ALERT_EMAIL_SUBJECT'));
        // $this->options['mailer'] = Services::Mail();
        return $context;
    }

    /**
     * Set Syslog options
     *
     * @param   array $context
     *
     * @return  array
     * @since   1.0
     */
    public function setSyslogOptions($context = array())
    {
        // $context['text_file'] ex. error.php (default)
        // $context['text_file_path'] ex. /users/amystephen/sites/molajo/source/site/1/logs (default SITES_LOGS_FOLDER)
        // $context['text_file_no_php'] false - adds die('Forbidden') to top of file (true prevents the precaution)
        // $context['text_entry_format'] - can be used to specify a custom log format
        return $context;
    }

    /**
     * Set Text file options
     *
     * @param   array $context
     *
     * @return  array
     * @since   1.0
     */
    public function setTextOptions($context = array())
    {
        // $context['text_file'] ex. error.php (default)
        // $context['text_file_path'] ex. /users/amystephen/sites/molajo/source/site/1/logs (default SITES_LOGS_FOLDER)
        // $context['text_file_no_php'] false - adds die('Forbidden') to top of file (true prevents the precaution)
        // $context['text_entry_format'] - can be used to specify a custom log format
        return $context;
    }
}
