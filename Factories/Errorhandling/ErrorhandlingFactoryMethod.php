<?php
/**
 * Error Handling Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Errorhandling;

use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use CommonApi\Exception\RuntimeException;
use Exception;
use Molajo\IoC\FactoryMethod\Base as FactoryMethodBase;
use stdClass;

/**
 * Error Handling Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class ErrorhandlingFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
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
        $options['product_namespace']        = 'Molajo\\Handler\\Error';
        $options['store_instance_indicator'] = false;
        $options['product_name']             = basename(__DIR__);

        parent::__construct($options);
    }

    /**
     * Instantiate a new handler and inject it into the Adapter for the FactoryInterface
     *
     * @return  array
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function setDependencies(array $reflection = array())
    {
        parent::setDependencies(array());

        $options                                  = array();
        $logger_request                           = new stdClass();
        $logger_request->name                     = 'Error Logging to File';
        $logger_request->logger_type              = 'File';
        $logger_request->levels                   = array(100, 200, 250, 300, 400, 500, 550, 600);
        $logger_request->context                  = array();
        $logger_request->context['file_location'] = $this->base_path . '/Sites/2/Logs/FileLog.json';

        $options['logger_requests'] = $logger_request;
        $options['base_path']       = $this->base_path;

        $this->dependencies['Logger'] = $options;

        return $this->dependencies;
    }

    /**
     * Instantiate Class
     *
     * @return  $this
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function instantiateClass()
    {
        $class = $this->product_namespace;

        try {
            $this->product_result = new $class($this->dependencies['Logger']);

        } catch (Exception $e) {
            throw new RuntimeException(
                'ErrorhandlingFactoryMethod: Could not instantiate Class: ' . $class
            );
        }
    }
}
