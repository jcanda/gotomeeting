<?php

namespace Jcanda\Gotomeeting;

require_once __DIR__ . '/../config/goto.php';

use Jcanda\Gotomeeting\Traits\MeetingOperations;
use Jcanda\Gotomeeting\Traits\OrganizerOperations;

class Meeting extends GotoAbstract
{

    use MeetingOperations,
        OrganizerOperations;

    function __construct($authType = 'direct')
    {
        parent::__construct($authType = 'direct');
    }
}