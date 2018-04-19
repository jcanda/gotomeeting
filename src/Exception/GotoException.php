<?php

namespace Jcanda\Gotomeeting\Exception;

// Shortcuts for simpler usage
use \Monolog\Logger;
use \Monolog\Formatter\LineFormatter;
use \Monolog\Handler\StreamHandler;

class GotoException extends \Exception
{

    function __construct($exceptionMessage = 'Error')
    {
        $this->Log_Save($exceptionMessage);
    }

    public function Log_Save($exceptionMessage)
    {
        $log = new Logger('GOTOMEETING');
        // Line formatter without empty brackets in the end
        $formatter = new LineFormatter(null, null, false, true);

        // Error level handler
        $errorHandler = new StreamHandler('log/error_goto.log', Logger::ERROR);
        $errorHandler->setFormatter($formatter);

        $log->pushHandler($errorHandler);
        $log->error($exceptionMessage);
    }
}