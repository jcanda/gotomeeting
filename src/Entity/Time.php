<?php

namespace Jcanda\Gotomeeting\Entity;

class Time extends EntityAbstract
{
    /* Model Schema
      {
      "startTime": "2017-09-20T12:00:00Z",
      "endTime": "2017-09-20T13:00:00Z"
      }
     */
    public $starttime;
    public $endtime;

    public function __construct($starttime, $endtime)
    {
        $this->starttime = $starttime;
        $this->endtime = $endtime;
    }
}