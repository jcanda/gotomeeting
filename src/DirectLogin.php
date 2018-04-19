<?php

namespace Jcanda\Gotomeeting;

use Jcanda\Gotomeeting\Traits\GotoClient;

class DirectLogin
{

    use GotoClient;
    protected $path = '/oauth/access_token';

    public function authenticate()
    {
        return $this->getAuthObject($this->path, $this->getParameters());
    }

    private function getParameters()
    {
        return [
            'grant_type' => "password",
            'user_id' => GOTO_DIRECT_USER,
            'password' => GOTO_CONSUMER_SECRET,
            'client_id' => GOTO_CONSUMER_KEY,
        ];
    }
}