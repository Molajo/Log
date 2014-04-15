<?php
/**
 * Log Interface
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace CommonApi\Log;

use Exception\Log\LogException;

/**
 * Log Interface
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
interface AdapterInterface
{
    /**
     * Start Logger and Set Log levels
     *
     * @param   string $name
     * @param   string $logger_type
     * @param   array  $levels
     *
     * @return  $this
     * @throws  LogException
     * @since   1.0
     */
    public function startLogger($name, $logger_type, $levels = array());

    /**
     * Stop Logger
     *
     * @param   string $name
     *
     * @return  $this
     * @throws  LogException
     * @since   1.0
     */
    public function stopLogger($name);

    /**
     * Logs with defined log level.
     *
     * @param   int    $level
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0
     */
    public function log($level, $message, array $context = array());

    /**
     * Set the Timezone
     *
     * @param   string $timezone
     *
     * @return  $this
     * @since   1.0
     */
    public function setTimezone($timezone);

    /**
     * Get the Timezone
     *
     * @return  string
     * @since   1.0
     */
    public function getTimezone();
}
