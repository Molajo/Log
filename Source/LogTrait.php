<?php
/**
 * Log Trait
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
namespace Molajo\Log;

/**
 * Log Trait
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
trait LogTrait
{
    /**
     * System is unusable.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function emergency($message, array $context = array())
    {
        return $this->log($this->levels_by_name['emergency'], $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function alert($message, array $context = array())
    {
        return $this->log($this->levels_by_name['alert'], $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function critical($message, array $context = array())
    {
        return $this->log($this->levels_by_name['critical'], $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function error($message, array $context = array())
    {
        return $this->log($this->levels_by_name['error'], $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function warning($message, array $context = array())
    {
        return $this->log($this->levels_by_name['warning'], $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function notice($message, array $context = array())
    {
        return $this->log($this->levels_by_name['notice'], $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function info($message, array $context = array())
    {
        return $this->log($this->levels_by_name['info'], $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param   string $message
     * @param   array  $context
     *
     * @return  $this
     * @since   1.0.0
     */
    public function debug($message, array $context = array())
    {
        return $this->log($this->levels_by_name['debug'], $message, $context);
    }
}
