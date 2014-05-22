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
use CommonApi\Exception\RuntimeException;
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
        $options['product_namespace']        = 'Molajo\\Log\\Request';

        parent::__construct($options);
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
        $adapter = $this->getAdapter($type = 'Memory');

        $class = $this->product_namespace;

        try {
            $this->product_result = new $class($adapter);

        } catch (Exception $e) {
            throw new RuntimeException(
                'Log: Could not instantiate Adapter: ' . $class
            );
        }

        return $this;
    }

    /**
     * Instantiate Log Adapter
     *
     * @return  $this
     * @since   1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     */
    public function getAdapter($type)
    {
        $class = 'Molajo\\Log\\Logger\\' . ucfirst(strtolower($type));

        try {
            return new $class();

        } catch (Exception $e) {
            throw new RuntimeException(
                'Log: Could not instantiate Log Adapter: ' . $class
            );
        }
    }
}
