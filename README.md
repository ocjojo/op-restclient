# OP-Restclient
**O**OP **P**HP **REST-Client**

A generic REST API client for PHP using objects to compose requests. This is based on and extends the excellent [tcdent/php-restclient](https://github.com/tcdent/php-restclient).

https://github.com/ocjojo/op-restclient
(c) 2017 Lukas Ehnle <me@ehnle.fyi>

## Installation

``` sh
$ composer require ocjojo/op-restclient
```


## Basic Usage

``` php
$api = new OPRestClient\Client([
    'base_url' => "https://api.twitter.com/1.1", 
    'format' => "json", 
     // https://dev.twitter.com/docs/auth/application-only-auth
    'headers' => ['Authorization' => 'Bearer '.OAUTH_BEARER], 
]);
// GET http://api.twitter.com/1.1/search/tweets.json?q=%23php
$result = $api->search->tweets->get(['q' => '#php']);

if($result->info->http_code == 200)
    var_dump($result->decode_response());
```



## Options for the client

See the documentation of [tcdent/php-restclient](https://github.com/tcdent/php-restclient) for the configurable options.
OPRestClient extends tcdent's RestClient, so all options and methods apply equally.


## Standard Parameters

You are able to set standard parameters for consecutive requests to the same endpoint, e.g. if you want access data at an endpoint with paging. Like so:

``` php
$api->search->tweets['q'] = '#php';
//both results will have the query parameter q set to '#php'
$result = $api->search->tweets->get(['page' => '1']);
$result2 = $api->search->tweets->get(['page' => '2']);

// to delete a parameter
unset($api->search->tweets['q']);
```

Standard Parameters will be set until you unset them manually.