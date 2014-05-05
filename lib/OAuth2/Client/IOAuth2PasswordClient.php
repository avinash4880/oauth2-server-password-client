<?php

namespace OAuth2\Client;

use OAuth2\Client\IOAuth2ConfidentialClient;

interface IOAuth2PasswordClient extends IOAuth2ConfidentialClient
{
    /**
     * @param string $secret
     */
    public function setSecret($secret);
}
