<?php

namespace OAuth2\Client;

use OAuth2\Client\OAuth2PasswordClient;
use Symfony\Component\HttpFoundation\Request;

class OAuth2PasswordClientStub extends OAuth2PasswordClient
{
    public function setSecret($secret) {

        $this->secret = hash('sha256', $secret);
        return $this;
    }

    public function checkCredentials(Request $request) {

        $credential = $this->getCredentials($request);

        return ($credential['client_id'] === $this->getPublicId()) && ( hash('sha256', $credential['client_secret']) === $this->secret);
    }
}
