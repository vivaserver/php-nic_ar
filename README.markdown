# NicAr Client

The NicAr Client [Composer](http://getcomposer.org/) package allows to you programatically extract information about any ".ar" (Argentina) domain name. This package is also the _official_ PHP client for the [public nic!alert API](http://api.nicalert.com.ar).

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

If for some reason you want to install the NicAr Client without the Composer package manager, there are two relevant files you need:

1. [Agent.php](https://github.com/vivaserver/php-restful_agent/blob/master/src/Restful/Agent.php)
2. [Client.php](https://github.com/vivaserver/php-nic_ar/blob/master/src/NicAr/Client.php)

Having both files on the PHP include path, you can require both on that order before creating an instance of the client:

    require 'Agent.php';
    require 'Client.php';
    $client = new NicAr\Client;

## Usage

Create a new instance of the NicAr Client after requiring the Composer autoloader and you should be ready to go.

    require 'vendor/autoload.php';
    $client = new NicAr\Client;

The NicAr Client supports lookups for domain names, domain transactions, entities, people and name servers.

The NicAr Client class constructor supports one boolean option for setting the responses to objects or associative arrays formats. By default the responses will be returned with an object format; to set it to an associative array format, instantiate the class with a TRUE argument:

    $client = new NicAr\Client(TRUE);

All the following usage examples will consider responses with an associative array format.

### Domain lookups

First, find out what kind of domain names you are allowed to lookup.

    $domains = $client->domains();

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

    $domains = $client->domains("vivaserver.com.ar");

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
      [name] => vivaserver
      [domain] => .com.ar
      [created_on] => 2004-11-18
      [expires_on] => 2013-11-18
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
          [responsible] => Array
            (
              [name] => Cristian Renato Arroyo
              [address] => Pje. Vucetich 676. Ciudad De Nieva.
              [city] => S. S. de jujuy
              [province] => Jujuy
              [zip_code] => 4600
              [country] => Argentina
              [phone] => (0388)155827713
              [fax] => (0388)155827713
              [work_hours] => 8am-1pm
            )
          [administrative] => Array
            (
              [name] => Dynamic DNS Network Services
              [address] => 210 Park Ave. #267
              [city] => Worcester
              [province] => 
              [zip_code] => MA 01609
              [country] => USA
              [phone] => 1-508-798-2145
              [fax] => 1-508-798-5748
              [activity] => Network Services
            )
          [technical] => Array
            (
              [name] => Andre Dure
              [address] => Humahuaca 1303
              [city] => Capital Federal
              [province] => Ciudad de Buenos Aires
              [zip_code] => C1405BIA
              [country] => Argentina
              [phone] => 49588864
              [fax] => 43335885
              [work_hours] => 10 a 22
            )
        )
      [dns_servers] => Array
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

### Entities lookups

All registered domains have a related entities (registrant/administrative contacts) and persons (responsible/technical contacts).

    $client->entities("Dynamic DNS Network Services");

A typical response for an entity resource would be:

    Array
    (
      [name] => Dynamic DNS Network Services
      [type] => ADMINISTRADORA
      [address] => 210 Park Ave. #267
      [city] => Worcester
      [province] => 
      [country] => USA
      [activity] => Network Services
      [handle] => NICAR-E607791
    )

### People lookups

    $client->people("Andre Dure");

A typical response for a person resource would be:

    Array
    (
      [name] => Andre Dure
      [handle] => NICAR-P425476
    )

### Name Servers lookups

Name servers can also be queried by hostname or IP.

    $client->name_servers("ns1.mydyndns.org");

A typical response would be:

    Array
    (
      [host] => ns1.mydyndns.org
      [ip] => 
      [owner] => Andre Dure
      [operator] => Andre Dure
      [handle] => NICAR-H12587
    )

Name servers can also be queried by it's IP address, should it be available.

### Domain transactions lookups

If a domain name has no recent transactions, a `NicAr\NoContent` exception will be raised. Otherwise an array of recent transactions will be returned.

    $client->transactions("amazon.com.ar");

A typical, non-empty response would be:

    Array
    (
      [0] => Array
        (
          [id] => REN19949812
          [created_at] => 2013-07-11T12:10:57-03:00
          [description] => Renovacion de Nombre
          [status] => FINALIZADO
          [notes] => Tramite finalizado el 11/07/13.
        )
    )

Transactions can also be queried by it's unique identifier:

    $client->transactions("REN19949812");

A successful response would look like:

    Array
    (
      [domain] => amazon.com.ar
      [created_at] => 2013-07-11T12:10:57-03:00
      [description] => Renovacion de Nombre
      [status] => FINALIZADO
      [notes] => Tramite finalizado el 11/07/13.
    )

### Check a domain status

You can also check if a given domain name resolves OK to it's name servers, thus effectively know if it's available online or not.

    $client->status("www.nic.ar");

A successful response would be like:

    Array
    (
      [domain] => www.nic.ar
      [online] => 1
      [offline] => 
      [ip] => 200.16.109.25
      [host] => roldan.nic.ar
    )

But also note that a domain name without the "www." may or may not resolve in the same way.

    $client->status("nic.ar");

A successful response would be like:

    Array
    (
      [domain] => nic.ar
      [online] => 1
      [offline] => 
      [ip] => 200.16.109.19
      [host] => firpo.nic.ar
    )

## Full API reference

The full documentation of the [public nic!alert API](http://api.nicalert.com.ar) is available at [http://api.nicalert.com.ar/docs](http://api.nicalert.com.ar/docs) if you want to write your own client, use any other language, or just use CURL in a RESTful way.

## License

This software is released under the [MIT License](http://www.opensource.org/licenses/MIT).

## Copyright

&copy;2013 [Cristian R. Arroyo](mailto:cristian.arroyo@vivaserver.com)
