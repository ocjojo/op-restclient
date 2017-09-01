<?php

namespace OPRestclient;

/**
 * Class APIEndpoint
 * @author Lukas Ehnle <me@ehnle.fyi>
 * Implements one specific endpoint with REST (Get, Post, Patch, Delete) methods.
 * Sub-endpoints may be accessed via properties.
 * Implements ArrayAccess for setting standard parameters via array access.
 */
class APIEndpoint implements \ArrayAccess
{
	private $client; //reference to the client

    private $parts; //array containing the url-parts

	private $subs; // array containing instantiated sub-endpoints

	private $params; // ParameterBag containing the standard parameters

	/**
     * BaseOperations constructor.
     */
    public function __construct(Client $client = NULL, $parts = [], $newPart = NULL)
    {
        $this->client = $client;
        $this->parts = $parts;
        $this->subs = array();
        $this->params = new ParameterBag();
        
        if($newPart){
        	$this->parts[] = $newPart;
        }
    }

	public function get($params = []){
		return $this->execute('get', $params);
	}

	public function patch($params = []){
		return $this->execute('patch', $params);
	}

	public function post($params = []){
		return $this->execute('post', $params);
	}

	public function delete($params = []){
		return $this->execute('delete', $params);
	}

    public function reset(){
        $this->params->reset();
    }

    public function getParameters(){
        return $this->params->getValues();
    }

    private function execute($method, $params) {
        $params = $this->params->mergeAndGetValues($params);
        return $this->client->execute(
            implode('/', $this->parts), //create url from parts
            $method,
            $params
        );
    }

	/**
     * allows access to sub-endpoints via object properties
     * @param mixed $name property name
     * @return mixed|null property value
     */
    public function __get($name)
    {
    	if(!isset($this->subs[$name])){
    		$this->subs[$name] = new APIEndpoint($this->client, $this->parts, $name);
    	}
    	return $this->subs[$name];
    }

    /**
     * allows setting standard parameters for multiple endpoints by assigning a nested array
     * @param string $name name of the property to change
     * @param array $arr new values for the property
     * @return void
     */
    public function __set($name, $arr)
    {
    	if(is_array($arr)){
    		$sub = $this->__get($name);
    		foreach ($arr as $key => $value) {
    			$sub[$key] = $value;
    		}
    	} else {
            throw new \Error("Use array notation to assign a single parameter");
    	}
    }

    /**
     * @param mixed $parameter
     * @return bool indicating if the parameter is set
     */
    public function offsetExists($parameter)
    {
        return isset($this->params[$parameter]);
    }

    /**
     * @param mixed $parameter the parameter to get
     * @return mixed returns the parameter
     */
    public function offsetGet($parameter)
    {
        if(!isset($this->params[$parameter])){
            $this->params[$parameter] = new ParameterBag();
        }
        return $this->params[$parameter];
    }

    /**
     * sets a parameter to the value
     * @param mixed $parameter
     * @param mixed $value
     */
    public function offsetSet($parameter, $value)
    {
        $this->params[$parameter] = $value;
    }

    /**
     * @param mixed $parameter parameter to be deleted
     */
    public function offsetUnset($parameter)
    {
        unset($this->params[$parameter]);
    }

}
