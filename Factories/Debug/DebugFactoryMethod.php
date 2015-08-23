<?php
/**
 * Error Handling Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Debug;

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
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class DebugFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
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
        $options['store_instance_indicator'] = false;
        $options['product_namespace']        = 'Molajo\\Factory\\DebugFactoryMethod';

        parent::__construct($options);
    }

    /**
     * Instantiate a new handler and inject it into the Adapter for the FactoryInterface
     *
     * @return  array
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function setDependencies(array $reflection = array())
    {
        parent::setDependencies(array());

        $logger_request                           = new stdClass();
        $logger_request->name                     = 'Debug Logging to File';
        $logger_request->logger_type              = 'File';
        $logger_request->levels                   = array(100, 200, 300, 400, 500, 550, 600);
        $logger_request->context                  = array();
        $logger_request->context['file_location'] = $this->base_path . '/Sites/2/Logs/DebugLogger.json';

        $options                      = array();
        $options['logger_requests']   = $logger_request;
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
        $this->product_result = $this->dependencies['Logger'];

        return $this;
    }
}
