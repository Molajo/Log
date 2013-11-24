<?php
/**
 * Log Connection Interface
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace CommonApi\Log;

use Exception\Log\AdapterException;

/**
 * Log Connection Interface
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 * @api
 */
interface ConnectionInterface
{
    /**
     * Connect to the Log
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    public function connect($options = array());

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  AdapterException
     * @api
     */
    public function close();
}
