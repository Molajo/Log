<?php
/**
 * Log
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   MIT
 */
define('MOLAJO', 'This is a Molajo Distribution');

if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $base);

$classMap = array(
    'Molajo\\Log\\Api\\AdapterInterface'     => BASE_FOLDER . '/Api/AdapterInterface.php',
    'Psr\\Log\\LoggerInterface'                  => BASE_FOLDER . '/Api/LoggerInterface.php',
    'Molajo\\Log\\Api\\LoggerTypeInterface'  => BASE_FOLDER . '/Api/LoggerTypeInterface.php',
    'Molajo\\Log\\Exception\\ExceptionInterface' => BASE_FOLDER . '/Exception/ExceptionInterface.php',
    'Molajo\\Log\\Exception\\LogException'       => BASE_FOLDER . '/Exception/LogException.php',
    'Molajo\\Log\\Type\\AbstractLogger'          => BASE_FOLDER . '/Type/AbstractLogger.php',
    'Molajo\\Log\\Type\\CallbackLogger'          => BASE_FOLDER . '/Type/CallbackLogger.php',
    'Molajo\\Log\\Type\\DatabaseLogger'          => BASE_FOLDER . '/Type/DatabaseLogger.php',
    'Molajo\\Log\\Type\\DummyLogger'             => BASE_FOLDER . '/Type/DummyLogger.php',
    'Molajo\\Log\\Type\\EchoLogger'              => BASE_FOLDER . '/Type/EchoLogger.php',
    'Molajo\\Log\\Type\\EmailLogger'             => BASE_FOLDER . '/Type/EmailLogger.php',
    'Molajo\\Log\\Type\\TextLogger'              => BASE_FOLDER . '/Type/TextLogger.php',
    'Molajo\\Log\\Adapter'                       => BASE_FOLDER . '/Adapter.php'
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
