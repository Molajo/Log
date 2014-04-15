<?php
/**
 * Log Connection Interface
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace CommonApi\Log;

use Exception\Log\RuntimeException;

/**
 * Log Connection Interface
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0.0
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
     * @throws  RuntimeException
     * @api
     */
    public function connect($options = array());

    /**
     * Close the Connection
     *
     * @return  $this
     * @since   1.0
     * @throws  RuntimeException
     * @api
     */
    public function close();
}
