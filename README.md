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

This is a confidential client identified with its client_id and a password.

# Prerequisites #

This library needs OAuth2 Server.

# Installation #

Installation is a quick 3 steps process:

* Download and install the library
* Extend with your classes
* Add the client type support to your OAuth2 Server

##Step 1: Install the library##

The preferred way to install this bundle is to rely on Composer. Just check on Packagist the version you want to install (in the following example, we used "dev-master") and add it to your `composer.json`:

    {
        "require": {
            // ...
            "spomky-labs/oauth2-server-password-client": "1.0.*@dev"
        }
    }

##Step 2: Create your classes##

This library needs to persist clients to your filesystem or database.

Your first job, then, is to create this class for your application.
This class can look and act however you want: add any properties or methods you find useful.
