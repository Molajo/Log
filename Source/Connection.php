<?php
/**
 * Connection to Log Adapter
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log;

use Exception;
use Exception\Log\ConnectionException;
use CommonApi\Log\LoggerAwareInterface;
use CommonApi\Log\LoggerInterface;

/**
 * Connection to Log Adapter
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
 */
class Connection // implements LoggerAwareInterface, LoggerInterface
{
    /**
     * Adapter Instance
     *
     * @var     object
     * @since   1.0
     */
    public $adapter;

    /**
     * Adapter Handler Instance
     *
     * @var     object
     * @since   1.0
     */
    public $adapter_handler;

    /**
     * Constructor
     *
     * @param   string $adapter_handler
     * @param   array  $options
     *
     * @since   1.0
     * @api
     */
    public function __construct($adapter_handler = 'Display', $options = array())
    {
        if ($adapter_handler == '') {
            $adapter_handler = 'Display';
        }

        $this->getAdapterHandler($adapter_handler);
        $this->getAdapter($adapter_handler);
        $this->connect($options);
    }

    /**
     * Get the Log specific Adapter Handler
     *
     * @param   string $adapter_handler
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     * @api
     */
    protected function getAdapterHandler($adapter_handler = 'Display')
    {
        $class = 'Molajo\\Log\\Handler\\' . $adapter_handler;

        try {
            $this->adapter_handler = new $class($adapter_handler);
        } catch (Exception $e) {
            throw new ConnectionException
            ('Log: Could not instantiate Log Adapter Handler: ' . $adapter_handler);
        }

        return;
    }

    /**
     * Get Log Adapter, inject with specific Log Adapter Handler
     *
     * @param   string $adapter_handler
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     * @api
     */
    protected function getAdapter($adapter_handler)
    {
        $class = 'Molajo\\Log\\Adapter';

        try {
            $this->adapter = new $class($this->adapter_handler);
        } catch (Exception $e) {
            throw new ConnectionException
            ('Log: Could not instantiate Adapter for Log Type: ' . $adapter_handler);
        }

        return $this;
    }

    /**
     * Connect to the Log Type
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     * @api
     */
    public function connect($options = array())
    {
        try {
            $this->adapter->setLogger($options);
        } catch (Exception $e) {

            throw new ConnectionException
            ('Log: Caught Exception: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Log the message for the level given the data in context
     *
     * @param   mixed  $level
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function log($level, $message, array $context = array())
    {
        return $this->adapter->setLogger($level, $message, $context);
    }

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  ConnectionException
     * @api
     */
    public function close()
    {
        $this->adapter->close();

        return $this;
    }
}
