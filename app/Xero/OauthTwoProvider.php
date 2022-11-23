<?php

namespace App\Xero;

use League\OAuth2\Client\Provider\GenericProvider;

class OauthTwoProvider extends GenericProvider
{
    // overwrite client_ID (api_key) and Client_secret (api_secret)
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