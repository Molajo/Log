<?php
/**
 * Logger Factory Method
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\Factories\Logger;

use CommonApi\IoC\FactoryInterface;
use CommonApi\IoC\FactoryBatchInterface;
use CommonApi\Exception\RuntimeException;
use Exception;
use Molajo\IoC\FactoryMethod\Base as FactoryMethodBase;
use stdClass;

/**
 * Logger Factory Method
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class LoggerFactoryMethod extends FactoryMethodBase implements FactoryInterface, FactoryBatchInterface
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
        $options['product_namespace']        = 'Molajo\\Log\\Logger';

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
        $loggers   = array();
        $loggers[] = $this->options['logger_requests'];
        $class     = $this->product_namespace;

        try {
            $this->product_result = new $class($loggers);

        } catch (Exception $e) {
            throw new RuntimeException(
                'LoggerFactoryMethod: Could not instantiate Class: ' . $class
            );
        }

        return $this;
    }
}
