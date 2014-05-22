<?php
/**
 * Bootstrap for Testing
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
$base     = substr(__DIR__, 0, strlen(__DIR__) - 5);
include_once $base . '/vendor/autoload.php';
if (function_exists('CreateClassMap')) {
} else {
    include_once __DIR__ . '/CreateClassMap.php';
}

$classmap = array();

$results  = createClassMap($base . '/Source/Adapter', 'Molajo\\Log\\Adapter\\');
$classmap = array_merge($classmap, $results);

$results  = createClassMap($base . '/Source', 'Molajo\\Log\\');
$classmap = array_merge($classmap, $results);

$results  = createClassMap($base . '/Controller', 'Molajo\\Controller\\');
$classmap = array_merge($classmap, $results);

ksort($classmap);

spl_autoload_register(
    function ($class) use ($classmap) {
        if (array_key_exists($class, $classmap)) {
            require_once $classmap[$class];
        }
    }
);
