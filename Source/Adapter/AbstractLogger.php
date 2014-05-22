<?php
/**
 * Abstract Logger
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log\Adapter;

use DateTime;
use DateTimeZone;
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
     * File Location
     *
     * @var    string
     * @since  1.0
     */
    protected $file_location = null;

    /**
     * Log
     *
     * @var    array
     * @since  1.0
     */
    protected $log;

    /**
     * Maintain Local Log - turn off with $options['maintain_log'] = false when activating log
     *
     * @var    boolean
     * @since  1.0
     */
    protected $maintain_log = true;

    /**
     * Columns to render output
     *
     * @var    array
     * @since  1.0
     */
    protected $columns;

    /**
     * Log Entry
     *
     * @var    object
     * @since  1.0
     */
    protected $log_entry;

    /**
     * Datetime
     *
     * @var    DateTime
     * @since  1.0
     */
    protected $log_entry_key = '';

    /**
     * Datetime
     *
     * @var    DateTime
     * @since  1.0
     */
    protected $datetime = '';

    /**
     * Timezone
     *
     * @var    DateTimeZone
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
     * Compared to current time to determine elapsed time between operations
     *
     * @var    float
     * @since  1.0
     */
    protected $previous_time = 0.0;

    /**
     * Compared to current memory settings to determine if additional allocation was required
     *
     * @var    float
     * @since  1.0
     */
    protected $previous_memory = 0.0;

    /**
     * Log Entry Fields - can be defined in sub child (ex. build from $context in constructor)
     *
     * @var    array
     * @since  1.0
     */
    protected $log_entry_fields = array();

    /**
     * Constructor
     *
     * @param   array $context Use in subclass, if needed, when starting logger
     *
     * @since   1.0.0
     */
    public function __construct(
        $context = array()
    ) {
        $this->timezone = new DateTimeZone(date_default_timezone_get() ? : 'UTC');
        $this->processContextArray($context);
        $this->started_time  = $this->getMicrotimeFloat();
        $this->previous_time = $this->getMicrotimeFloat();
    }

    /**
     * Get the Log
     *
     * @return  array()
     * @since   1.0.0
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Clear the Log
     *
     * @return  $this
     * @since   1.0.0
     */
    public function clearLog()
    {
        $this->log = array();

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
     * @since   1.0.0
     */
    public function log($level, $message, array $context = array())
    {
        $this->log_entry = new stdClass();

        $this->log_entry->entry_date = $this->setLogDateTime();
        $this->log_entry->level      = (int)$level;
        $this->log_entry->level_name = (string)$context['log_level_name'];
        unset($context['log_level_name']);
        $this->log_entry->message = (string)$message;

        $this->calculateElapsedTime();

        if (function_exists('memory_get_usage')) {
            $this->calculateMemoryUsage();
        }

        $this->setLogEntryFields($context);

        $this->saveLog();

        return $this;
    }

    /**
     * Set Datetime for Log entry
     *
     * @return  DateTime
     * @since   1.0.0
     */
    protected function setLogDateTime()
    {
        $date_time = new DateTime('now');
        $date_time->setTimezone($this->timezone);

        return $date_time->format('Y-m-d H:i:s');
    }

    /**
     * Calculate Elapsed Time
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function calculateElapsedTime()
    {
        $current_time                                = $this->getMicrotimeFloat();
        $this->log_entry->started_time               = $this->started_time;
        $this->log_entry->previous_time              = $this->previous_time;
        $this->log_entry->current_time               = $current_time;
        $this->log_entry->elapsed_time_from_start    = $current_time - $this->started_time;
        $this->log_entry->formatted_time_from_start  = sprintf(
            '%.2f seconds (+%.2f)',
            $this->log_entry->elapsed_time_from_start,
            $this->log_entry->elapsed_time_from_start - $this->started_time
        );
        $this->log_entry->elapsed_time_from_previous = $current_time - $this->previous_time;
        $this->log_entry->formatted_time_for_step    = sprintf(
            '%.2f seconds (+%.2f)',
            $this->log_entry->elapsed_time_from_previous,
            $this->log_entry->elapsed_time_from_previous - $this->previous_time
        );
        $this->previous_time                         = $current_time;

        return $this;
    }

    /**
     * Get the current time from: http://php.net/manual/en/function.microtime.php
     *
     * @return  float
     * @since   1.0.0
     */
    protected function getMicrotimeFloat()
    {
        list ($usec, $sec) = explode(' ', microtime());

        return ((float)$usec + (float)$sec);
    }

    /**
     * Calculate Memory Usage
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function calculateMemoryUsage()
    {
        $this->log_entry->memory_usage    = memory_get_usage(true) / 1048576;
        $this->log_entry->previous_memory = $this->previous_memory;
        if ($this->log_entry->memory_usage > $this->previous_memory) {
            $this->log_entry->memory_difference = $this->log_entry->memory_usage - $this->previous_memory;
        } else {
            $this->log_entry->memory_difference = 0;
        }
        $this->log_entry->formatted_memory = sprintf(
            '%0.2f MB (+%.3f)',
            $this->log_entry->memory_usage,
            $this->log_entry->memory_difference
        );
        $this->previous_memory             = $this->log_entry->memory_usage;

        return $this;
    }

    /**
     * Add the custom log entry fields to the log
     *
     * @param   array $context
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setLogEntryFields($context)
    {
        if (count($this->log_entry_fields) === 0) {
            return $this;
        }

        foreach ($this->log_entry_fields as $key => $value) {

            if (isset($context[$key])) {
                $this->log_entry->$key = $context[$key];
            } else {
                $this->log_entry->$key = $value;
            }
        }

        return $this;
    }

    /**
     * Set Log
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function saveLog()
    {
        if ($this->maintain_log === true) {
            $this->log_entry_key = count($this->log);
            $this->log[]         = $this->log_entry;
        }

        return $this;
    }

    /**
     * Process the context array entries
     *
     * @param   array $context
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function processContextArray(array $context = array())
    {
        if (count($context) > 0) {
        } else {
            return $this;
        }

        $this->createLogEntryFields($context);
        $this->setMaintainLog($context);
        $this->setFileLocation($context);
        $this->setColumns($context);

        return $this;
    }

    /**
     * Log the message for the level given the data in context
     *
     * @param   array $context
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function createLogEntryFields(array $context = array())
    {
        if (isset($context['log_entry_fields'])) {
            $log_entry_fields = $context['log_entry_fields'];
        } else {
            $log_entry_fields = array();
        }

        if (count($log_entry_fields) > 0) {
            foreach ($log_entry_fields as $key => $value) {
                $log_entry_fields[$key] = $value;
            }
        }

        $this->log_entry_fields = $log_entry_fields;

        return $this;
    }

    /**
     * Set the maintain_log flag
     *
     * @param   array $context
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setMaintainLog(array $context)
    {
        if (isset($context['maintain_log']) && $context['maintain_log'] === false) {
            $this->maintain_log = false;
        } else {
            $this->maintain_log = true;
        }

        return $this;
    }

    /**
     * Set File Location
     *
     * @param   array $context
     *
     * @return  $this
     * @since   1.0.0
     */
    protected function setFileLocation(array $context)
    {
        if (isset($context['file_location'])) {
            $this->file_location = $context['file_location'];
            unset($context['file_location']);
        }

        return $this;
    }

    /**
     * Set File Location
     *
     * @param   array $context
     *
     * @return  array
     * @since   1.0.0
     */
    protected function setColumns(array $context)
    {
        if (isset($context['columns'])) {
            $columns = $context['columns'];
            unset($context['columns']);
        } else {
            $columns = array();
        }

        if (is_array($columns)) {
            $this->columns = $columns;
        } else {
            $this->columns = array();
        }

        return $this;
    }
}
