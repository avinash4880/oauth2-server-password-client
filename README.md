Password Client for OAuth2 Server
=================================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Spomky-Labs/oauth2-server-password-client/badges/quality-score.png?s=6440dc8583cd1b282fd29a862160a3ce8ed7c907)](https://scrutinizer-ci.com/g/Spomky-Labs/oauth2-server-password-client/)
[![Code Coverage](https://scrutinizer-ci.com/g/Spomky-Labs/oauth2-server-password-client/badges/coverage.png?s=3f3b77c29b2cc58fd36c5385b033353dc576e3c4)](https://scrutinizer-ci.com/g/Spomky-Labs/oauth2-server-password-client/)

[![Build Status](https://travis-ci.org/Spomky-Labs/oauth2-server-password-client.svg?branch=master)](https://travis-ci.org/Spomky-Labs/oauth2-server-password-client)
[![HHVM Status](http://hhvm.h4cc.de/badge/spomky-labs/oauth2-server-password-client.png)](http://hhvm.h4cc.de/package/spomky-labs/oauth2-server-password-client)

[![Latest Stable Version](https://poser.pugx.org/spomky-labs/oauth2-server-password-client/v/stable.png)](https://packagist.org/packages/spomky-labs/oauth2-server-password-client)
[![Latest Unstable Version](https://poser.pugx.org/spomky-labs/oauth2-server-password-client/v/unstable.png)](https://packagist.org/packages/spomky-labs/oauth2-server-password-client)
[![Total Downloads](https://poser.pugx.org/spomky-labs/oauth2-server-password-client/downloads.png)](https://packagist.org/packages/spomky-labs/oauth2-server-password-client)
[![License](https://poser.pugx.org/spomky-labs/oauth2-server-password-client/license.png)](https://packagist.org/packages/spomky-labs/oauth2-server-password-client)


This library adds a new type of client for OAuth2 Server: Password Client.

This is a confidential client identified with its ID and a password.

# Prerequisites #

This library needs OAuth2 Server.

It has been tested on `PHP 5.3` to `PHP 5.6` and `HHVM`.

# Installation #

Installation is a quick 2 steps process:

* Download and install the library
* Extend with your class

##Step 1: Install the library##

The preferred way to install this bundle is to rely on Composer. Just check on Packagist the version you want to install (in the following example, we used the stable release) and add it to your `composer.json`:

    {
        "require": {
            // ...
            "spomky-labs/oauth2-server-password-client": "1.0.*"
        }
    }

##Step 2: Create your Client class##

This library provides an abstract class to ease your work: `OAuth2\Client\OAuth2PasswordClient`.

You can extend it or create your own class. You just need to define how to store passwords and a way to check them when requested.

Feel free to add all setters and getters you need.

It the following example, we store a hash (SHA-256) of the password.
	
	<?php
	
	namespace ACME\MyOAuth2Server\Client;
	
	use OAuth2\Client\OAuth2PasswordClient;
	use Symfony\Component\HttpFoundation\Request;
	
	class MyPasswordClient extends OAuth2PasswordClient
	{
	    public function setSecret($secret) {
	
	        $this->secret = md5($secret);
	        return $this;
	    }
	
	    public function checkCredentials(Request $request) {
	
	        $credential = $this->getCredentials($request);
	
	        return ($credential['client_id'] === $this->getPublicId()) && ( md5($credential['client_secret']) === $this->secret);
	    }
	}


It this another example, we store a salt and use it to encrypt the password.
	
	<?php
	
	namespace ACME\MyOAuth2Server\Client;
	
	use OAuth2\Client\OAuth2PasswordClient;
	use Symfony\Component\HttpFoundation\Request;
	
	class MyPasswordClient extends OAuth2PasswordClient
	{
	    protected $salt;

	    public function setSecret($secret) {
	
			$this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
	        $this->secret = hash('sha256', $this->salt.$secret);
	        return $this;
	    }
	
	    public function checkCredentials(Request $request) {
	
	        $credential = $this->getCredentials($request);
	
	        return ($credential['client_id'] === $this->getPublicId()) && ( hash('sha256', $this->salt.$credential['client_secret']) === $this->secret);
	    }
	}

And voilà!

Now, you just have to store your clients with your ClientManager and you are ready to use them.