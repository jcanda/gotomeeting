<?php

namespace Jcanda\Gotomeeting\Entity;

class EntityAbstract
{

    public function toArray()
    {
        //list of variables to be filtered
        $blacklist = [
            'meetingKey',
            'registrationUrl',
            'participants',
        ];

        return array_filter(get_object_vars($this), function ($value, $key) use ($blacklist) {

            if (!in_array($key, $blacklist)) {
                return !empty($value);
            }
        }, ARRAY_FILTER_USE_BOTH);
    }
}