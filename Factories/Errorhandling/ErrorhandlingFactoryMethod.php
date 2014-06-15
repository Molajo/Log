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
        $options['product_namespace']        = 'Molajo\\Controller\\Errorhandling';
        $options['store_instance_indicator'] = true;
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
    public function setDependencies(array $reflection = null)
    {
        parent::setDependencies($reflection);

        $this->dependencies['Logger']        = array();

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
