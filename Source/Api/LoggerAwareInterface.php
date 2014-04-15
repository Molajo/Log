<?php
/**
 * Logger Aware Interface
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace CommonApi\Log;

use Exception\Log\LogException;
use CommonApi\Log\LoggerInterface;

/**
 * Logger Aware Interface
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
interface LoggerAwareInterface
{
    /**
     * Sets a Log Instance on the object
     *
     * @param   $logger \CommonApi\Log\LoggerInterface
     *
     * @return  $this
     * @since   1.0
     * @throws  LogException
     */
    public function setLogger(LoggerInterface $logger);
}
