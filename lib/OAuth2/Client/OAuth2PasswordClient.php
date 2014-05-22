<?php

namespace OAuth2\Client;

use OAuth2\Client\IOAuth2PasswordClient;
use OAuth2\Client\OAuth2RegisteredClient;
use OAuth2\Util\OAuth2RequestBody;
use Symfony\Component\HttpFoundation\Request;
use OAuth2\Exception\OAuth2BadRequestException;

abstract class OAuth2PasswordClient extends OAuth2RegisteredClient implements IOAuth2PasswordClient
{
    protected function getCredentials(Request $request) {

        if (!$request->isSecure()) {
            throw new OAuth2BadRequestException('invalid_request', 'The request must be secured');
        }

        $credentials = array();
        $methods = array(
            'findCredentialsFromAuthenticationScheme',
            'findCredentialsFromRequestBody',
        );

        foreach ($methods as $method) {
            if (is_array($credential = $this->$method($request))) {
                $credentials[] = $credential;
            }
        }

        if (count($credentials) > 1) {
            throw new OAuth2BadRequestException('invalid_request', 'Only one authentication method may be used to authenticate the client.');
        }

        if (count($credentials) < 1) {
            return false;
        }

        return current($credentials);
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
