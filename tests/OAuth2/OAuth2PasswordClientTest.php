<?php

namespace OAuth2;

use OAuth2\Client\IOAuth2Client;
use OAuth2\Client\OAuth2PasswordClientStub;
use OAuth2\Exception\IOAuth2Exception;
use Symfony\Component\HttpFoundation\Request;

class OAuth2PasswordClientTest extends \PHPUnit_Framework_TestCase
{
    /** 
     * @dataProvider testPasswordClientTypeData
     */
    public function testPasswordClientType(IOAuth2Client $client, Request $request, $expectedResult = null, $exception = null, $expectedMessage = null, $exceptedDescription = null)
    {
         try {
             $result = $client->checkCredentials($request);
             $this->assertSame($expectedResult,$result);

             if ($exception) {
                 $this->fail("The expected exception '$exception' was not thrown");
             } 
         } catch (\Exception $e) {
             if($exception === null || (!$e instanceof IOAuth2Exception and !$e instanceof $exception)){
                 throw $e;
             }
             $this->assertSame($expectedMessage, $e->getMessage());
             $this->assertSame($exceptedDescription,$e->getDescription());
         }
    }

    public function testPasswordClientTypeData()
    {
        return array(
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array(), null, array()),
                null,
                'OAuth2\Exception\OAuth2BadRequestException',
                'invalid_request',
                'The request must be secured'
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('HTTPS'=>'on')),
                false,
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('PHP_AUTH_USER'=>'foo','HTTPS'=>'on')),
                false,
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('PHP_AUTH_PW'=>'secret','HTTPS'=>'on')),
                false,
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('PHP_AUTH_USER'=>'foo','PHP_AUTH_PW'=>'secret','HTTPS'=>'on')),
                true,
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('HTTPS'=>'on'), http_build_query(array('client_id'=>'foo'))),
                false,
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('HTTPS'=>'on'), http_build_query(array('client_secret'=>'secret'))),
                false,
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('HTTPS'=>'on'), http_build_query(array('client_id'=>'foo','client_secret'=>'secret'))),
                false,
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('CONTENT_TYPE' => 'application/x-www-form-urlencoded','HTTPS'=>'on'), http_build_query(array('client_id'=>'foo','client_secret'=>'secret'))),
                true,
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('CONTENT_TYPE' => 'application/x-www-form-urlencoded; charset=UTF-8','HTTPS'=>'on'), http_build_query(array('client_id'=>'foo','client_secret'=>'secret'))),
                true,
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('CONTENT_TYPE' => 'application/x-www-form-foo','HTTPS'=>'on'), http_build_query(array('client_id'=>'foo','client_secret'=>'secret'))),
                false,
            ),
            array(
                new OAuth2PasswordClientStub('foo', 'secret'),
                $this->createRequest('/', 'GET', array('PHP_AUTH_USER'=>'foo','PHP_AUTH_PW'=>'secret','CONTENT_TYPE' => 'application/x-www-form-urlencoded','HTTPS'=>'on'), http_build_query(array('client_id'=>'foo','client_secret'=>'secret'))),
                null,
                'OAuth2\Exception\OAuth2BadRequestException',
                'invalid_request',
                'Only one authentication method may be used to authenticate the client.'
            ),
        );
    }

    protected function createRequest($uri, $method = 'GET', array $server = array(), $content = null, array $headers = array() )
    {
        $request = Request::create($uri,$method, array(), array(), array(), $server, $content);

        foreach ($headers as $key => $value) {
            $request->headers->set($key, $value);
        }
        return $request;
    }
}
