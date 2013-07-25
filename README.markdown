# NicAr Client

The NicAr Client [Composer](http://getcomposer.org/) package allows to you programatically extract information about any ".ar" (Argentina) domain name. 

## Installation

The easiest way is to install it as any [Composer](http://getcomposer.org/) package. Just add an entry to your [composer.json](http://getcomposer.org/doc/01-basic-usage.md#composer-json-project-setup) file requiring the latest available version:

    ...
    "require": {
      "vivaserver/nic_ar": "dev-master"
      ...
    }
    ...

The package will be automatically installed when you execute the `composer install` command.

## Usage

Create a new instance of the RESTful Agent after requiring the Composer autoloader and you should be ready to go.

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

## Full API reference

The full documentation of the [public nic!alert API](http://api.nicalert.com.ar) is available at [http://api.nicalert.com.ar](http://api.nicalert.com.ar) if you want to write your own client, use any other language, or just use CURL in a RESTful way.

## License

This software is released under the [MIT License](http://www.opensource.org/licenses/MIT).

## Copyright

&copy;2013 [Cristian R. Arroyo](mailto:cristian.arroyo@vivaserver.com)
