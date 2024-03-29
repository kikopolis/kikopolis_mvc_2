<?php

declare(strict_types=1);

namespace Kikopolis\Core;

use Kikopolis\App\Config\Config;
use Kikopolis\App\Framework\Aurora\View;

defined('_KIKOPOLIS') or die('No direct script access!');

/**
 * Error handler.
 * Part of the Kikopolis MVC Framework.
 * @author Kristo Leas <admin@kikopolis.com>
 * @version 0.0.0.1000
 * PHP Version 7.3.5
 */

class Error
{
    /**
     * Error handler
     * Convert all errors to Exceptions by throwing an ErrorException
     * @param int $level Error level
     * @param string $message Error message
     * @param string $file Filename the error was raised in
     * @param int $line Line number in the file
     * @return void
     * @throws \ErrorException
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) { // To keep the operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler
     * @param \Exception @exception
     * @return void
     * @throws \Exception
     */
    public static function exceptionHandler($exception)
    {
        // Code 404 not found, else 500 general error
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        if (Config::SHOW_ERRORS) {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        } else {
            $log = dirname(__DIR__) . '/logs/AppExceptions/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();

            error_log($message);
            View::render("$code");
        }
    }
}