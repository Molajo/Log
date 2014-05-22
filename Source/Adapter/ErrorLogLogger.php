<?php
/**
 * Errorlog Logger
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log\Adapter;

/**
 * Errorlog Logger
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class ErrorlogLogger extends AbstractLogger
{
    /**
     * Columns for Log
     *
     * @var    string
     * @since  1.0
     */
    protected $columns = array(
        'entry_date',
        'level',
        'level_name',
        'message',
        'formatted_time_from_start',
        'formatted_memory'
    );

    /**
     * Constructor
     *
     * @param   array $context  Use in subclass, if needed, when starting logger
     *
     * @since   1.0.0
     */
    public function __construct(
        $context = array()
    ) {
        if (isset($context['columns'])) {
            $this->file_location = $context['columns'];
            unset($context['columns']);
        }

        parent::__construct($context);
    }

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

        $output = '';
        foreach ($this->columns as $column) {
            $output .= $this->log_entry->$column . "\t";
        }
        $output .= PHP_EOL;


        if ($this->file_location === null) {
            error_log((string) $output);
        } else {
            error_log((string) $output, 3, $this->file_location);
        }

        return $this;
    }
}
