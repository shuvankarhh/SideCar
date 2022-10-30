<?php

namespace App\Xero;

use League\OAuth2\Client\Provider\GenericProvider;

class OauthTwoProvider extends GenericProvider
{
    // overwrite client_ID and Client_secret
    public function setClientID($val)
    {
        $this->clientId = $val;
    }

    public function setClientSecret($val)
    {
        $this->clientSecret = $val;
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }
}