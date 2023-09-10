<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('APP_PATH', realpath(__DIR__) . '/..');

require_once './vendor/autoload.php';

try {

    $router = new Eva\Library\Router([
        'paths' => [
            'controllers' => APP_PATH . '/eva/Controller',
            'middlewares' => APP_PATH . '/eva/Middleware'
        ],
        'namespaces' => [
            'controllers' => 'Eva\Controller',
            'middlewares' => 'Eva\Middleware'
        ],
        'debug' => true
    ]);

    require_once APP_PATH . '/routes/web.php';

    $router->run();


} catch (\Throwable $throwable) {

    debug($throwable);
    /**
        echo 'Message: ' . $throwable->getMessage();
        echo '<br>';
        echo 'File: '. $throwable->getFile();
        echo '<br>';
        echo 'Line: ' . $throwable->getLine();
     */
}