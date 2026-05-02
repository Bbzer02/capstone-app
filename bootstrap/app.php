<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Suppress proc_open warnings from sebastian/version
// Note: Error handler should already be set in artisan/public/index.php before this file is loaded
// This is a backup handler in case bootstrap/app.php is loaded directly
if (!isset($GLOBALS['sebastian_error_handler_set'])) {
    $GLOBALS['sebastian_error_handler_set'] = true;
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
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
