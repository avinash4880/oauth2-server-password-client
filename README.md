Password Client for OAuth2 Server
=================================

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
