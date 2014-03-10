# NicAr\Client

The NicAr\Client [Composer](http://getcomposer.org/) package allows to you programatically extract information about any ".ar" (Argentina) domain name. This package is also the _official_ PHP client for the [public nic!alert API](http://api.nicalert.me).

## Installation

The easiest way is to install it as any [Composer](http://getcomposer.org/) package. Just add an entry to your [composer.json](http://getcomposer.org/doc/01-basic-usage.md#composer-json-project-setup) file requiring the latest available version:

    ...
    "require": {
      "vivaserver/nic_ar": "dev-master"
      ...
    }
    ...

The package will be automatically installed when you execute the `composer install` command.

### Installation without Composer

If for some reason you want to install the NicAr\Client without the Composer package manager, there are two relevant files you need:

1. [Agent.php](https://github.com/vivaserver/php-restful_agent/blob/master/src/Restful/Agent.php)
2. [Client.php](https://github.com/vivaserver/php-nic_ar/blob/master/src/NicAr/Client.php)

Having both files on the PHP include path, you can require both on that order before creating an instance of the client:

    require 'Agent.php';
    require 'Client.php';
    $client = new NicAr\Client;

## Usage

Create a new instance of the NicAr\Client after requiring the Composer autoloader and you should be ready to go.

    require 'vendor/autoload.php';
    $client = new NicAr\Client;

The NicAr\Client class constructor has two initialization options, *both optional*:

1. The first is a string that can be set to an API token to by-pass the CAPTCHA challenge that the NIC.ar website sometimes requests you to solve before answering your domain lookup. Please refer to the nic!alert API [official documentation](http://api.nicalert.me/docs) for more details.  

        $client = new NicAr\Client("234d3cdf979e04db3d709ed8");

2. The second is a boolean option for setting the responses to objects or associative arrays formats. By default the responses will be returned in an object format, just like the [json_decode](http://php.net/json_decode) function would. To set it to an associative array format, pass the TRUE argument:  

        $client = new NicAr\Client(NULL, TRUE);

All the following usage examples will consider responses with an associative array format.

### Domain lookups

The NicAr\Client supports lookups for domain names. First, find out what kind of domain names you are allowed to lookup.

    $domains = $client->whois();

A typical response would be:

    Array
    (
      [0] => .com.ar
      [1] => .gov.ar
      [2] => .int.ar
      [3] => .mil.ar
      [4] => .net.ar
      [5] => .org.ar
      [6] => .tur.ar
    )

All the following lookups will raise a `NicAr\NotFound` exception if the requested resource could not be found.

    $domains = $client->whois("vivaserver.com.ar");

The response for an existing, registered, delegated domain would be like this:

    Array
    (
      [status] => Array
        (
          [available] => 
          [delegated] => 1
          [expiring] => 
          [phasing_out] => 
          [pending] => 
          [registered] => 1
        )
      [name] => vivaserver.com.ar
      [created_on] => 2004-11-18
      [expires_on] => 2014-11-18
      [message] => 
      [contacts] => Array
        (
          [registrant] => Array
            (
              [name] => Cristian Renato Arroyo
              [occupation] => Diseno de Paginas Web
              [address] => Pje. Vucetich 676. Ciudad De Nieva
              [city] => S. S. de Jujuy
              [province] => Jujuy
              [zip_code] => 4600
              [country] => Argentina
              [phone] => (0388)155827713
              [fax] => (0388)155827713
            )
          [responsible] => 
          [administrative] => 
          [technical] => 
        )
      [name_servers] => Array
        (
          [primary] => Array
            (
              [host] => ns1.mydyndns.org
              [ip] => 
            )
          [secondary] => Array
            (
              [host] => ns2.mydyndns.org
              [ip] => 
            )
          [alternate1] => Array
            (
              [host] => ns3.mydyndns.org
              [ip] => 
            )
          [alternate2] => Array
            (
              [host] => ns4.mydyndns.org
              [ip] => 
            )
          [alternate3] => Array
            (
              [host] => ns5.mydyndns.org
              [ip] => 
            )
        )
    )

### Check a domain status

You can also check if a given domain name resolves OK to it's name servers, thus effectively know if it's available online or not.

    $client->status("www.nic.ar");

A successful response would be like:

    Array
    (
      [status] => Array
        (
          [domain] => www.nic.ar
          [online] => 1
          [offline] => 
          [ip] => 200.16.109.25
          [host] => roldan.nic.ar
        )
    )

But also note that a domain name without the "www." may or may not resolve in the same way.

    $client->status("nic.ar");

A successful response would be like:

    Array
    (
      [status] => Array
        (
          [domain] => nic.ar
          [online] => 1
          [offline] => 
          [ip] => 200.16.109.19
          [host] => firpo.nic.ar
        )
    )

## Full API reference

The full documentation of the [public nic!alert API](http://api.nicalert.me) is available at [http://api.nicalert.me/docs](http://api.nicalert.me/docs) if you want to write your own client, use any other language, or just use CURL in a RESTful way.

## License

This software is released under the [MIT License](http://www.opensource.org/licenses/MIT).

## Copyright

&copy;2014 [Cristian R. Arroyo](mailto:cristian.arroyo@vivaserver.com)
