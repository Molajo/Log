<?php
/**
 * Dummy Logger
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
namespace Molajo\Log\Type;

/**
 * Dummy Logger
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @since      1.0
 */
class Dummy extends AbstractHandler
{
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
        return $this;
    }
}