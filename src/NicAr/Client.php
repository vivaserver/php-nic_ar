<?php
namespace NicAr;

# NicAr\Client
# This is the _official_ PHP client for accessing the public nic!alert API
#
# (c)2013 Cristian R. Arroyo <cristian.arroyo@vivaserver.com>

class NoContent extends \Exception {}
class NotFound  extends \Exception {}

class CaptchaError      extends \Exception {}
class ExpectationError  extends \Exception {}
class ParameterError    extends \Exception {}
class PreconditionError extends \Exception {}
class RequestError      extends \Exception {}
class ServiceError      extends \Exception {}
class TimeoutError      extends \Exception {}
class UnavailableError  extends \Exception {}

class Client {
  const API_URI = 'http://api.nicalert.com.ar';

  private $agent;  # Restful_agent is the only dependency
  private $assoc;  # return responses as associative arrays?
  private $api_host;

  public function __construct($assoc=FALSE, $api_hosts=array()) {
    if (!empty($api_hosts)) {  # multiple API hosts, anyone?
      $idx = array_rand($api_hosts);
      $this->api_host = $api_hosts[$idx];
    }
    else $this->api_host = self::API_URI;

    $this->assoc = $assoc;
    $this->agent = new \Restful\Agent;
  }

  public function domains($domain=NULL) {
    if (!empty($domain)) {
      $domain = trim($domain);
      $response = $this->agent->get("{$this->api_host}/domains/{$domain}");
    }
    else $response = $this->agent->get("{$this->api_host}/domains");

    return $this->result_for($response);
  }

  public function entities($name) {
    $name = urlencode(trim($name));
    $response = $this->agent->get("{$this->api_host}/entities/{$name}");
    return $this->result_for($response);
  }

  public function people($name) {
    $name = urlencode(trim($name));
    $response = $this->agent->get("{$this->api_host}/people/{$name}");
    return $this->result_for($response);
  }

  public function transactions($domain) {
    $domain = trim($domain);
    $response = $this->agent->get("{$this->api_host}/transactions/{$domain}");
    return $this->result_for($response);
  }

  public function name_servers($host) {
    $host = trim($host);
    $response = $this->agent->get("{$this->api_host}/name_servers/{$host}");
    return $this->result_for($response);
  }

  public function status($domain) {
    $domain = trim($domain);
    $response = $this->agent->get("{$this->api_host}/status/{$domain}");
    return $this->result_for($response);
  }

  private function result_for($response) {
    try {
      switch ($response->code) {
        case 200:
          return json_decode($response->body,$this->assoc);
        break;
        case 204:
          throw new NoContent;
        break;
        case 400:
          throw new ParameterError($response->body);
        break;
        case 404:
          throw new NotFound;
        break;
        case 406:
          throw new RequestError($response->body);
        break;
        case 408:
          throw new TimeoutError($response->body);
        break;
        case 412:
          throw new PreconditionError($response->body);
        break;
        case 417:
          throw new ExpectationError($response->body);
        break;
        case 424:
          throw new CaptchaError($response->body);
        break;
        case 500:
          throw new ServiceError($response->body);
        break;
        case 503:
          throw new UnavailableError($response->body);
        break;
      }
    }
    catch (\Exception $e) { throw $e; }  # whatever else ought to be non-API's
  }
}
