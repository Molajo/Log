<?php
/**
 * Log
 *
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    MIT
 */

if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
define('BASE_FOLDER', $base);

$classMap = array(
    'Molajo\\Log\\Adapter'                  => BASE_FOLDER . '/Adapter.php',
    'Molajo\\Log\\Connection'               => BASE_FOLDER . '/Connection.php',
    'Molajo\\Log\\CommonApi\\AdapterInterface'    => BASE_FOLDER . '/Api/AdapterInterface.php',
    'Psr\\Log\\LoggerInterface'             => BASE_FOLDER . '/Api/LoggerInterface.php',
    'Psr\\Log\\LoggerAwareInterface'        => BASE_FOLDER . '/Api/LoggerAwareInterface.php',
    'Molajo\\Log\\CommonApi\\LoggerTypeInterface' => BASE_FOLDER . '/Api/LoggerTypeInterface.php',
    'Exception\\Log\\ExceptionInterface'    => BASE_FOLDER . '/Exception/ExceptionInterface.php',
    'Exception\\Log\\LogException'          => BASE_FOLDER . '/Exception/LogException.php',
    'Molajo\\Log\\Type\\AbstractLogger'     => BASE_FOLDER . '/Type/AbstractLogger.php',
    'Molajo\\Log\\Type\\Callback'           => BASE_FOLDER . '/Type/Callback.php',
    'Molajo\\Log\\Type\\Database'           => BASE_FOLDER . '/Type/Database.php',
    'Molajo\\Log\\Type\\Dummy'              => BASE_FOLDER . '/Type/Dummy.php',
    'Molajo\\Log\\Type\\Echo'               => BASE_FOLDER . '/Type/Echo.php',
    'Molajo\\Log\\Type\\Email'              => BASE_FOLDER . '/Type/Email.php',
    'Molajo\\Log\\Type\\Text'               => BASE_FOLDER . '/Type/Text.php'
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
