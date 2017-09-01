<?php

namespace OPRestclient;
/**
 * OOP PHP REST Client
 * https://github.com/ocjojo/op-restclient
 * (c) 2017 Lukas Ehnle <me@ehnle.fyi>
 */

/**
 * Client is just a wrapper class for tcdent/RestClient
 * it is implemented as singleton
 */
class Client extends \RestClient {

	private $api;

	public function __construct($options=[])
    {
        parent::__construct($options);

        $this->api = new APIEndpoint($this);
    }

	/**
     * @param mixed $name property name
     * @return mixed value of the property
     * @throws NoResponseException
     * @throws RequirementsException
     */
    public function __get($name)
    {
        return $this->api->__get($name);
    }

    /**
     * @param mixed $name name of the property
     * @param mixed $value value to set for the property
     * @throws NoResponseException
     * @throws RequirementsException
     */
    public function __set($name, $value)
    {
        return $this->api->__set($name, $value);
    }

    /**
     * @param $name property name
     * @return bool indicating if the property is instantiated
     */
    public function __isset($name)
    {
        return $this->api->__isset($name);
    }

    /**
     * @param $name property name of the instance to be destroyed
     */
    public function __unset($name)
    {
        $this->api->__unset($name);
    }
}
