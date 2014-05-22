<?php
/**
 * File Logger
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log\Adapter;

/**
 * File Logger
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class FileLogger extends AbstractLogger
{
    /**
     * Log the message for the level given the data in context
     *
     * @param   mixed  $level
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function log($level, $message, array $context = array())
    {
        parent::log($level, $message, $context);

        if ($this->file_location === null) {
            $this->file_location = __DIR__ . '/FileLogger.json';
        }

        $json = json_encode($this->log, JSON_BIGINT_AS_STRING | JSON_PRETTY_PRINT);
        file_put_contents($this->file_location, $json);

        return $this;
    }

    /**
     * Clear the Log
     *
     * @return  $this
     * @since   1.0.0
     */
    public function clearLog()
    {
        parent::clearLog();

        $json = json_encode($this->log, JSON_BIGINT_AS_STRING | JSON_PRETTY_PRINT);
        file_put_contents($this->file_location, $json);

        return $this;
    }
}
