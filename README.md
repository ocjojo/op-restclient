# OP-Restclient
**O**OP - Object-oriented programming  
**P**HP  
**REST-Client**

A generic REST API client for PHP using objects to compose requests. This is based on and extends the excellent [tcdent/php-restclient](https://github.com/tcdent/php-restclient).

https://github.com/ocjojo/op-restclient
(c) 2017 Lukas Ehnle <me@ehnle.fyi>

## Installation

``` sh
$ composer require ocjojo/op-restclient
```


## Basic Usage

``` php
$api = new OPRestclient\Client([
    'base_url' => "https://jsonplaceholder.typicode.com"
]);
// GET https://jsonplaceholder.typicode.com/posts/1?param_1=hello&param_2=world
$result = $api->posts->{1}->get([
	'param_1' => "hello",
	'param_2' => "world"
]);
if($result->info->http_code == 200)
    var_dump($result->decode_response());
```



## Options for the client

See the documentation of [tcdent/php-restclient](https://github.com/tcdent/php-restclient) for the configurable options.
OPRestClient extends tcdent's RestClient, so all options and methods apply equally.


## Standard Parameters

You are able to set standard parameters for consecutive requests to the same endpoint, e.g. if you want access data at an endpoint with paging. Like so:

``` php
$api = new OPRestClient\Client([
    'base_url' => "https://api.twitter.com/1.1", 
    'format' => "json", 
     // https://dev.twitter.com/docs/auth/application-only-auth
    'headers' => ['Authorization' => 'Bearer '.OAUTH_BEARER]
]);

$api->search->tweets['q'] = '#php';
//both results will have the query parameter q set to '#php'
$result = $api->search->tweets->get(['page' => '1']);
$result2 = $api->search->tweets->get(['page' => '2']);

// to delete a standard parameter of a route
unset($api->search->tweets['q']);

// to delete all standard parameters of a route
$api->search->tweets->reset();
```

Standard Parameters will be set until you unset them manually.