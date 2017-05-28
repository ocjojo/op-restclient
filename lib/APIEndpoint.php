<?php

namespace OOPRestClient;

/**
 * Class APIEndpoint
 * @author Lukas Ehnle <me@ehnle.fyi>
 */
class APIEndpoint implements \ArrayAccess
{
	private $parts;

	private $subs;

	private $params;

	/**
     * BaseOperations constructor.
     */
    public function __construct($parts = [], $newPart = NULL)
    {
        $this->parts = $parts;
        if($newPart){
        	$this->parts[] = $newPart;
        }

        $this->subs = array();
		$this->params = new Param();
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

    private function execute($method, $params) {
        $params = array_merge($params, $this->params->value());
        return RestClient::getInstance()->execute(
            implode('/', $this->parts),
            $method,
            $params
        );
    }

	/**
     * @param mixed $name property name
     * @return mixed|null property value
     */
    public function __get($name)
    {
    	if(!isset($this->subs[$name])){
    		$this->subs[$name] = new APIEndpoint($this->parts, $name);
    	}
    	return $this->subs[$name];
    }

    /**
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
     * @param mixed $offset
     * @return bool indicating if this offset is instantiated
     */
    public function offsetExists($offset)
    {
        return isset($this->params[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed returns the instance at this offset
     */
    public function offsetGet($offset)
    {
        if(!isset($this->params[$offset])){
            $this->params[$offset] = new Param();
        }
        return $this->params[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->params[$offset] = $value;
    }

    /**
     * @param mixed $offset offset of the instance to be destroyed
     */
    public function offsetUnset($offset)
    {
        unset($this->params[$offset]);
    }

}
