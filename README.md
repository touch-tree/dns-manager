# @cloudflare-panel

An website management platform that provides DNS management, SSL and TLS configuration, pagerule configuration and URL forwarding and redirections
to make it easier to manage websites. You can also preset certain settings so you don't have to configure settings that are default for each website.

## Requirements
- PHP version 7.4

## Installation
First, navigate to your webserver's document root and clone this repo:

    git clone https://github.com/echtyushi/cloudflare-panel

## Credentials

For the config of our application make changes to `config/app.example.php` and rename it to `app.php` when done.

### php.ini

For the `php.ini` we need to enable the extension opcache.

1.  Enable opcache extension:

        zend_extension=opcache

2.  Add opcache settings in `php.ini`:

        opcache.enable=1
        opcache.memory_consumption=128
        opcache.interned_strings_buffer=8
        opcache.max_accelerated_files=4000
