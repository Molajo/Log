<?php
/**
 * Log Test
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Log\Test;

use Molajo\Log\Connection;

/**
 * Log Test
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class LogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Adapter
     *
     * @var    object  Molajo/Log/Adapter
     * @since  1.0
     */
    protected $adapter;

    /**
     * Options
     *
     * @var    array
     * @since  1.0
     */
    protected $options;

    /**
     * Setup testing
     *
     * @return  $this
     * @since   1.0
     */
    protected function setUp()
    {
        $this->options = array();

        $this->options['log_service'] = 1;
        $this->options['log_folder']  = BASE_FOLDER . '/.dev/Log';
        $this->options['log_time']    = 900;

        $this->adapter = new Connection('File', $this->options);

        return $this;
    }

    /**
     * Connect to the Log
     *
     * @param   array $options
     *
     * @return  $this
     * @since   1.0
     */
    public function testConnect()
    {
        $this->assertTrue(file_exists($this->options['log_folder']));

        return $this;
    }

    /**
     * Create a log entry
     *
     * @covers  Molajo\Log\Handler\File::connect
     *
     * @return  $this
     * @since   1.0
     */
    public function testSet()
    {
        $value = 'Stuff';
        $key   = serialize($value);

        $this->adapter->set($key, $value, $ttl = 0);

        $this->assertTrue(file_exists($this->options['log_folder'] . '/' . $key));
    }

    /**
     * Create a log entry
     *
     * @covers  Molajo\Log\Handler\File::get
     *
     * @return  $this
     * @since   1.0
     */
    public function testGet()
    {
        $value = 'Stuff';
        $key   = serialize($value);

        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff';
        $key   = serialize($value);

        $results = $this->adapter->get($key);

        $this->assertEquals($value, $results->getValue());
    }

    /**
     * Delete log for specified $key value or expired log
     *
     * @param   string $key
     *
     * @return  bool
     * @since   1.0
     */
    public function testRemove()
    {
        $value = 'Stuff';
        $key   = serialize($value);
        $this->adapter->remove($key);

        $this->assertFalse(file_exists($this->options['log_folder'] . '/' . $key));
    }

    /**
     * Clear all log
     *
     * @return  bool
     * @since   1.0
     */
    public function testClear()
    {
        $keys = array();

        $value = 'Stuff1';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff2';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff3';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff4';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff5';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff6';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff7';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff8';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff9';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff10';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $this->adapter->clear();

        foreach ($keys as $key) {
            $this->assertFalse(file_exists($this->options['log_folder'] . '/' . $key));
        }
    }

    /**
     * Get multiple LogItems by Key
     *
     * @param   array $keys
     *
     * @return  array
     * @since   1.0
     */
    public function testGetMultiple()
    {
        $keys = array();

        $value = 'Stuff1';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff2';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff3';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff4';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff5';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff6';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff7';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff8';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff9';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff10';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $items = $this->adapter->getMultiple($keys);

        $this->assertEquals(2, count($keys));
    }

    /**
     * Create a set of log entries
     *
     * @param   array        $items
     * @param   null|integer $ttl
     *
     * @return  $this
     * @since   1.0
     */
    public function testSetMultiple($items = array(), $ttl = null)
    {
        $items = array();

        $value = 'dog';

        $items['stuff1'] = serialize($value);
        $items['stuff2'] = serialize($value);
        $items['stuff3'] = serialize($value);
        $items['stuff4'] = serialize($value);
        $items['stuff5'] = serialize($value);
        $items['stuff6'] = serialize($value);

        $this->adapter->setMultiple($items);

        $count = 0;
        foreach (new \DirectoryIterator($this->options['log_folder']) as $file) {
            if ($file->isDot()) {
            } else {
                $count ++;
            }
        }

        $this->assertEquals(6, $count);
    }

    /**
     * Remove a set of log entries
     *
     * @param   array $keys
     *
     * @return  $this
     * @since   1.0
     */
    public function testRemoveMultiple()
    {
        $keys = array();

        $value = 'Stuff1';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $value = 'Stuff2';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff3';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff4';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff5';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff6';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff7';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff8';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff9';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);

        $value = 'Stuff10';
        $key   = serialize($value);
        $this->adapter->set($key, $value, $ttl = 0);
        $keys[] = $key;

        $this->adapter->removeMultiple($keys);

        $count = 0;
        foreach (new \DirectoryIterator($this->options['log_folder']) as $file) {
            if ($file->isDot()) {
            } else {
                $count ++;
            }
        }

        $this->assertEquals(8, $count);
    }

    /**
     * Tears Down
     *
     * @return $this
     * @since 1.0
     */
    protected function tearDown()
    {
        foreach (new \DirectoryIterator($this->options['log_folder']) as $file) {
            if ($file->isDot()) {
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($this->options['log_folder']);
    }
}
