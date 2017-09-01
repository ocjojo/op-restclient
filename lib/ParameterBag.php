<?php

namespace OPRestclient;

/**
 * Class ParameterBag
 * @author Lukas Ehnle <me@ehnle.fyi>
 * implements ArrayAccess to get/set standard parameters for an url.
 * is a light wrapper for an Array that handles merging of nested values
 */
class ParameterBag implements \ArrayAccess
{
	private $values;

	public function __construct()
    {
        $this->values = [];
    }

    /**
     * returns the standard parameters as [nested] associative array
     * @return array the standard parameters
     */
    public function getValues()
    {
        $values = [];
        foreach ($this->values as $key => $value) {
            $values[$key] = $value instanceOf ParameterBag ? $value->getValues() : $value;
        }
    	return $values;
    }

    /**
     * merges the provided parameters array with the standard parameters and returns them.
     * In case of duplicate keys, the provided parameters will be used.
     * @param  array  $arr additional parameters to be merged
     * @return array      the merged array
     */
    public function mergeAndGetValues(array &$arr)
    {
        $values = $this->getValues();
        return $this->arrayMergeRecursiveDistinct($values, $arr);
    }

    /**
     * resets the ParameterBag's values
     */
    public function reset(){
        $this->values = [];
    }

    /**
     * merges two arrays with the second overwriting the first one, if keys are identical
     * sub-arrays are merged recursively
     * @param  array  &$array1 the first array
     * @param  array  &$array2 the second array
     * @return array          the merged array
     */
    private function arrayMergeRecursiveDistinct(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value)
        {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])){
                $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }

	/**
     * @param mixed $offset
     * @return bool indicating if this offset is instantiated
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed returns the instance at this offset
     */
    public function offsetGet($offset)
    {
        if(!isset($this->values[$offset])){
            $this->values[$offset] = new ParameterBag();
        }
        return $this->values[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if(is_array($value)){
            foreach ($value as $key => $value) {
                $this->values[$offset][$key] = $value;
            }
        } else {
        	$this->values[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset offset of the instance to be destroyed
     */
    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

}
