<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Suppress proc_open warnings from sebastian/version BEFORE autoload
$originalErrorHandler = set_error_handler(function ($errno, $errstr, $errfile, $errline) use (&$originalErrorHandler) {
    // Suppress proc_open CreateProcess warnings from sebastian/version
    if (($errno === E_WARNING || $errno === E_USER_WARNING) && 
        strpos($errstr, 'proc_open(): CreateProcess failed') !== false &&
        (strpos($errfile, 'sebastian/version') !== false || strpos($errfile, 'Version.php') !== false)) {
        return true; // Suppress this warning completely
    }
    // Pass other errors to original handler if it exists
    if ($originalErrorHandler) {
        return call_user_func($originalErrorHandler, $errno, $errstr, $errfile, $errline);
    }
    return false;
}, E_WARNING | E_USER_WARNING);

// Note: For web requests, warnings are typically not displayed to users
// The error handler above will suppress sebastian/version warnings

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
