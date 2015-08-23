<?php
/**
 * Errorlog Logger
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log\Adapter;

/**
 * Errorlog Logger
 *
 * @package    Molajo
 * @copyright  2014-2015 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class ErrorlogLogger extends AbstractLogger
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
     * @link    http://www.php.net/manual/en/function.error-log.php
     */
    public function log($level, $message, array $context = array())
    {
        parent::log($level, $message, $context);

        $output = '';

        foreach ($this->columns as $column) {
            $output .= $this->log_entry->$column . "\t";
        }
        $output .= PHP_EOL;

        $output = addslashes($output);

        if ($this->file_location === null) {
            error_log((string)$output);
        } else {
            error_log((string)$output, 3, $this->file_location);
        }

        return $this;
    }
}
