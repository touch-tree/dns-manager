# Site Manager

A domain management platform that provides DNS management, SSL/TLS configuration, pagerule configuration and URL forwarding and redirection to make it easier to add and manage sites. You can also preset certain settings so you don't have to configure settings that are default for each website.

## Requirements
- PHP version 7.4
- opcache for PHP 7.4

## Installation
First, navigate to your webserver's document root and clone this repository:

    git clone https://github.com/dlt-media/site-manager

## Credentials

For the config of our application make changes to `config/api.example.php` and rename it to `api.php` when done.

### php.ini

For the `php.ini` we need to enable the extension opcache.

1.  Enable opcache extension:

        zend_extension=opcache

2.  Add opcache settings in `php.ini`:

        opcache.enable=1
        opcache.memory_consumption=128
        opcache.interned_strings_buffer=8
        opcache.max_accelerated_files=4000

## Additional Notes

- The api_token inside of `api.php` needs to have sufficient rights and permissions to make changes to zones. See [permissions](https://developers.cloudflare.com/fundamentals/api/reference/permissions/).
- This application relies on opcache. See [opcache](https://www.php.net/manual/en/opcache.installation.php).
