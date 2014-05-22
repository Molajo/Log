<?php
/**
 * Abstract Logger
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log\Adapter;

use stdClass;

/**
 * Abstract Logger
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
abstract class AbstractLogger
{
    /**
     * Context
     *
     * @var    array
     * @since  1.0
     */
    protected $context = array();

    /**
     * Datetime
     *
     * @var    object
     * @since  1.0
     */
    protected $datetime = '';

    /**
     * Timezone
     *
     * @var    string
     * @since  1.0
     */
    protected $timezone = '';

    /**
     * Time started, used to calculate time between operations
     *
     * @var    float
     * @since  1.0
     */
    protected $started_time = 0.0;

    /**
     * Elapsed time between now and started_time
     *
     * @var    float
     * @since  1.0
     */
    protected $elapsed_time_from_start = 0.0;

    /**
     * Compared to current time to determine elapsed time between operations
     *
     * @var    float
     * @since  1.0
     */
    protected $previous_time = 0.0;

    /**
     * Elapsed time between now and previous_time
     *
     * @var    float
     * @since  1.0
     */
    protected $elapsed_time_from_previous = 0.0;

    /**
     * Current time
     *
     * @var    float
     * @since  1.0
     */
    protected $current_time = 0.0;

    /**
     * Current Memory Usage
     *
     * @var    float
     * @since  1.0
     */
    protected $memory = 0.0;

    /**
     * Compared to current memory settings to determine if additional allocation was required
     *
     * @var    float
     * @since  1.0
     */
    protected $previous_memory = 0.0;

    /**
     * Difference in Memory Usage
     *
     * @var    float
     * @since  1.0
     */
    protected $memory_difference = 0.0;

    /**
     * Formatted memory
     *
     * @var    float
     * @since  1.0
     */
    protected $formatted_memory = 0.0;

    /**
     * Message
     *
     * @var    string
     * @since  1.0
     */
    protected $message;

    /**
     * Log Entry Fields
     *
     * @var    array
     * @since  1.0
     */
    protected $log_entry_fields = array();

    /**
     * Log
     *
     * @var    array
     * @since  1.0
     */
    protected $log;

    /**
     * Log Entry
     *
     * @var    object
     * @since  1.0
     */
    protected $log_entry;

    /**
     * Constructor
     *
     * @param   array $context
     *
     * @return  mixed
     * @since   1.0
     */
    public function __construct(
        $context = array()
    ) {


        $this->started_time  = $this->getMicrotimeFloat();
        $this->previous_time = $this->getMicrotimeFloat();

        return $this;
    }

    /**
     * Establish connection to the Physical Logger
     *
     * @param   array $context
     *
     * @return  $this
     * @since   1.0
     */
    public function setLogger($context = array())
    {
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
        $this->log_entry = new stdClass();

        $this->log_entry->entry_date = date("Y-m-d") . ' ' . date("H:m:s");
        $this->log_entry->level             = (int)$level;
        $this->log_entry->message           = (string)$message;
        $this->log_entry->context           = (string)$context;

        $this->calculateElapsedTime();
        $this->calculateMemoryUsage();

        if (count($this->log_entry_fields) > 0) {
            $this->setLogEntryFields($this->log_entry);
        }

        return $this;
    }

    /**
     * Log the message for the level given the data in context
     *
     * @return  $this
     * @since   1.0
     */
    public function setLogEntryFields()
    {
        foreach ($this->log_entry_fields as $field) {
            if (isset($this->context[$field])) {
                $this->log_entry->$field = $this->context[$field];
            } else {
                $this->log_entry->$field = null;
            }
        }

        return $this;
    }

    /**
     * Calculate Elapsed Time
     *
     * @return  $this
     * @since   1.0
     */
    protected function calculateElapsedTime()
    {
        $this->current_time                          = $this->getMicrotimeFloat();
        $this->log_entry->started_time               = $this->started_time;
        $this->log_entry->previous_time              = $this->previous_time;
        $this->log_entry->current_time               = $this->current_time;
        $this->log_entry->elapsed_time_from_start    = $this->current_time - $this->started_time;
        $this->log_entry->formatted_time_from_start  = sprintf(
            '%.2f seconds (+%.2f)',
            $this->elapsed_time_from_start,
            $this->elapsed_time_from_start - $this->started_time
        );
        $this->log_entry->elapsed_time_from_previous = $this->current_time - $this->previous_time;
        $this->log_entry->formatted_time_for_step    = sprintf(
            '%.2f seconds (+%.2f)',
            $this->elapsed_time_from_previous,
            $this->elapsed_time_from_previous - $this->previous_time
        );
        $this->previous_time                         = $this->current_time;

        return $this;
    }

    /**
     * Get the current time from: http://php.net/manual/en/function.microtime.php
     *
     * @return  float
     * @since   1.0
     */
    public function getMicrotimeFloat()
    {
        list ($usec, $sec) = explode(' ', microtime());

        return ((float)$usec + (float)$sec);
    }

    /**
     * Calculate Memory Usage
     *
     * @return  $this
     * @since   1.0
     */
    protected function calculateMemoryUsage()
    {
        $this->memory = 0;

        if (function_exists('memory_get_usage')) {
            $this->memory = memory_get_usage(true) / 1048576;
        }

        if ($this->memory > $this->previous_memory) {
            $this->memory_difference = $this->memory - $this->previous_memory;
        } else {
            $this->memory_difference = 0;
        }

        $this->formatted_memory = sprintf(
            '%0.2f MB (+%.3f)',
            $this->memory,
            $this->memory_difference
        );

        $this->log_entry->memory_usage      = $this->memory;
        $this->log_entry->previous_memory   = $this->previous_memory;
        $this->log_entry->memory_difference = $this->memory_difference;
        $this->log_entry->formatted_memory  = $this->memory_difference;

        return $this;
    }
}
