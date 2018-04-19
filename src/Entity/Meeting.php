<?php

namespace Jcanda\Gotomeeting\Entity;

class Meeting extends EntityAbstract
{
    /* Model Schema
      {
      "subject": "string",
      "startTime": "2017-09-20T12:00:00Z",
      "endTime": "2017-09-20T13:00:00Z",
      "passwordrequired": false,
      "conferencecallinfo": "VoIP",
      "timezonekey": "",
      "meetingtype": "scheduled",
      }
     */
    public $subject;
    public $starttime;
    public $endtime;
    public $passwordrequired = false;
    public $conferencecallinfo = 'VoIP';
    public $timezonekey;
    public $meetingtype = 'scheduled';

    public function __construct($parameterArray = null)
    {
        if (isset($parameterArray) && is_array($parameterArray)) {

            //required
            $this->subject = $parameterArray['subject'];
            $this->starttime = $parameterArray['starttime'];
            $this->endtime = $parameterArray['endtime'];

            //optional
            $this->conferencecallinfo = (isset($parameterArray['conferencecallinfo']) ? $parameterArray['conferencecallinfo'] : $this->conferencecallinfo);
            $this->passwordrequired = (isset($parameterArray['passwordrequired']) ? $parameterArray['passwordrequired'] : $this->passwordrequired);
            $this->timezonekey = '';
        }
    }
}