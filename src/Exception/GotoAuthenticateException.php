<?php

namespace Jcanda\Gotomeeting\Exception;

class GotoAuthenticateException extends GotoException
{

    function __construct($exceptionMessage = 'Error Acceso')
    {
        parent::__construct($exceptionMessage);
    }
}