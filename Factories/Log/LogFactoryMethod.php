<?php
/**
 * Log Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Log;

use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use Molajo\Exception\RuntimeException;
use Exception;
use Molajo\IoC\FactoryMethodBase;

/**
 * Log Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class LogFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
{
    /**
     * Constructor
     *
     * @param  $options
     *
     * @since  1.0.0
     */
    public function __construct(array $options = array())
    {
        $options['product_name']             = basename(__DIR__);
        $options['store_instance_indicator'] = true;
        $options['product_namespace']        = 'Molajo\\Log\\Logger';

        parent::__construct($options);
    }

    /**
     * Instantiate Class
     *
     * @return  $this
     * @since   1.0.0
     * @throws  \Molajo\Exception\RuntimeException
     */
    public function instantiateClass()
    {
        $loggers = array();

        $logger_request                           = new stdClass();
        $logger_request->name                     = 'Full Logging to File';
        $logger_request->logger_type              = 'File';
        $logger_request->levels                   = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context                  = array();
        $logger_request->context['file_location'] = __DIR__ . '/FileLog.json';
        $loggers[]                                = $logger_request;

        $class = $this->product_namespace;

        try {
            $this->product_result = new $class($loggers);

        } catch (Exception $e) {
            throw new RuntimeException(
                'LogFactoryMethod: Could not instantiate Class: ' . $class
            );
        }

        return $this;
    }
}
