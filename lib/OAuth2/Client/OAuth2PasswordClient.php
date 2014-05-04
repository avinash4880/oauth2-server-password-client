<?php

namespace OAuth2\Client;

use OAuth2\Client\IOAuth2ConfidentialClient;
use OAuth2\Client\OAuth2RegisteredClient;
use OAuth2\Util\OAuth2RequestBody;
use Symfony\Component\HttpFoundation\Request;
use OAuth2\Exception\OAuth2BadRequestException;

class OAuth2PasswordClient extends OAuth2RegisteredClient implements IOAuth2ConfidentialClient
{
    protected $secret;

    public function __construct($publicId, $secret, array $allowedGrantTypes = array(), array $redirectUris = array()) {

        $this->setSecret($secret);
        parent::__construct($publicId, $allowedGrantTypes, $redirectUris);
    }


    /**
     * @param string $secret
     */
    public function setSecret($secret) {

        $this->secret = $secret;
        return $this;
    }

    public function getSecret() {

        return $this->secret;
    }

    public function checkCredentials(Request $request) {

        if (!$request->isSecure()) {
            throw new OAuth2BadRequestException('invalid_request', 'The request must be secured');
        }

        $credentials = array();
        if (is_array($credential = $this->findCredentialsFromAuthenticationScheme($request))) {
            $credentials[] = $credential;
        }

        if (is_array($credential = $this->findCredentialsFromRequestBody($request))) {
            $credentials[] = $credential;
        }

        if (count($credentials) > 1) {
            throw new OAuth2BadRequestException('invalid_request', 'Only one authentication method may be used to authenticate the client.');
        }

        if (count($credentials) < 1) {
            return false;
        }

        $credential = current($credentials);

        return ($credential['client_id'] === $this->getPublicId()) && ($credential['client_secret'] === $this->getSecret());
    }

    protected function findCredentialsFromAuthenticationScheme(Request $request) {

        if ($request->server->get('PHP_AUTH_USER') && $request->server->get('PHP_AUTH_PW')) {
            return array(
                'client_id' => $request->server->get('PHP_AUTH_USER'),
                'client_secret' => $request->server->get('PHP_AUTH_PW'),
            );
        }
        return false;
    }

    protected function findCredentialsFromRequestBody(Request $request) {

        $parameters = OAuth2RequestBody::getParameters($request);
        if (isset($parameters['client_id']) && isset($parameters['client_secret'])) {
            return array(
                'client_id' => $parameters['client_id'],
                'client_secret' => $parameters['client_secret'],
            );
        }
        return false;
    }
}
