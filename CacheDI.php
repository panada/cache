<?php

namespace Panada\Cache;

trait CacheDI
{
    /**
     * Use magic method 'call' to pass user method
     * into driver method
     *
     * @param string @name
     * @param array @arguments
     */
    public function __call($name, $arguments)
    {    
        return call_user_func_array([$this->DIObject, $name], $arguments);
    }
    
    /**
     * PHP Magic method for calling a class property dinamicly
     * 
     * @param string $name
     * @return mix
     */
    public function __get($name)
    {    
        return $this->DIObject->$name;
    }
    
    /**
     * PHP Magic method for set a class property dinamicly
     * 
     * @param string $name
     * @param mix $value
     * @return void
     */
    public function __set($name, $value)
    {    
        $this->DIObject->$name = $value;
    }
}