<?php

namespace OAuth2\Client;

use OAuth2\Client\OAuth2PasswordClient;
use Symfony\Component\HttpFoundation\Request;

class OAuth2PasswordClientStub extends OAuth2PasswordClient
{
    protected $secret;

    public function __construct($publicId, $secret, array $allowedGrantTypes = array(), array $redirectUris = array()) {

        $this->setSecret($secret);
        $this->setPublicId($publicId);
        $this->setAllowedGrantTypes($allowedGrantTypes);
        $this->setRedirectUris($redirectUris);
    }

    /**
     *
     * @param array  $redirectUris An array of strings that contains allowed redirected URIs for authorization requests
     * @return IOAuth2RegisteredClient The client itself (for chained setter calls)
     */
    public function setRedirectUris(array $redirectUris) {

        $this->redirectUris = $redirectUris;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUris() {

        return $this->redirectUris;
    }

    /**
     * @param  string        $publicId The public ID of the client
     * @return IOAuth2Client           The client itself (for chained setter calls)
     */
    public function setPublicId($publicId) {

        $this->publicId = $publicId;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicId() {

        return $this->publicId;
    }

    /**
     * @param  array $allowedGrantTypes An array of strings that contains allowed grant types
     * @return IOAuth2Client            The client itself (for chained setter calls)
     */
    public function setAllowedGrantTypes(array $allowedGrantTypes) {

        $this->allowedGrantTypes = $allowedGrantTypes;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowedGrantType($grant_type) {

        return in_array($grant_type, $this->allowedGrantTypes, true);
    }

    public function setSecret($secret) {

        $this->secret = hash('sha256', $secret);
        return $this;
    }

    public function checkCredentials(Request $request) {

        $credential = $this->getCredentials($request);

        return ($credential['client_id'] === $this->getPublicId()) && ( hash('sha256', $credential['client_secret']) === $this->secret);
    }
}
