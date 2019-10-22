<?php

namespace Jcanda\Gotomeeting;

use Jcanda\Gotomeeting\Traits\GotoClient;

class DirectLogin
{

    use GotoClient;
    protected $path = '/oauth/v2/token';

    public function authenticate()
    {
        return $this->getAuthObject($this->path, $this->getParameters());
    }

    private function getParameters()
    {
        return [
            'grant_type' => "password",
            'username' => GOTO_DIRECT_USER,
            'password' => GOTO_CONSUMER_SECRET,
        ];
    }
}