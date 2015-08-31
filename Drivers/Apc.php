<?php

namespace Panada\Cache\Drivers;

use Panada\Cache\CacheInterface;
use Panada\Cache\CacheTrait;

/**
 * Panada APC API Driver.
 *
 * @package	Cache
 * @license http://opensource.org/licenses/MIT
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.2
 *
 * Install APC on Ubuntu: aptitude install libpcre3-dev;
 * pecl install apc
 */
class Apc implements CacheInterface
{
    use CacheTrait;
    
    public function __construct()
    {    
        /**
        * Makesure APC extension is enabled
        */
       if(! extension_loaded('apc'))
           throw new \Exception('APC extension that required by APC Driver is not available.');
    }
    
    /**
     * DI method for calling APC function dinamicly
     * 
     * @param string $name
     * @param mix $arguments
     * @return mix
     */
    public function __call($name, $arguments)
    {    
        return call_user_func_array($name, $arguments);
    }
    
    /**
     * PHP Magic method for calling a static method dinamicly
     * 
     * @param string $name
     * @param mix $arguments
     * @return mix
     */
    public static function __callStatic($name, $arguments)
    {    
        return call_user_func_array($name, $arguments);
    }
    
    /**
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @return void
     */
    public function setValue($key, $value, $expire = 0, $namespace = false)
    {    
        $key = $this->keyToNamespace($key, $namespace);
        
        return apc_store($key, $value, $expire); 
    }
    
    /**
     * Cached the value if the key doesn't exists,
     * other wise will false.
     *
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @return void
     */
    public function addValue( $key, $value, $expire = 0, $namespace = false )
    {    
        $key = $this->keyToNamespace($key, $namespace);
        
        return apc_add($key, $value, $expire);
    }
    
    /**
     * Update cache value base on the key given.
     *
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @return void
     */
    public function updateValue( $key, $value, $expire = 0, $namespace = false )
    {    
        $key = $this->keyToNamespace($key, $namespace);
        
        return $this->setValue($key, $value, $expire);
    }
    
    /**
     * @param string $key
     * @return mix
     */
    public function getValue( $key, $namespace = false )
    {    
        $key = $this->keyToNamespace($key, $namespace);
        
        return apc_fetch($key); 
    }
    
    /**
     * @param string $key
     * @return void
     */
    public function deleteValue( $key, $namespace = false )
    {    
        $key = $this->keyToNamespace($key, $namespace);
        
        return apc_delete($key);
    }
    
    /**
     * Flush all cached object.
     * @return bool
     */
    public function flushValues()
    {    
        return apc_clear_cache('user');
    }
    
    /**
     * Increment numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to increment the item's value
     */
    public function incrementBy($key, $offset = 1)
    {
        if(! apc_exists($key)){
            if(! apc_store($key, 0)){
                return false;
            }
            
            return apc_inc($key, $offset);
        }
        else {
            return apc_inc($key, $offset);
        }
    }
    
    /**
     * Decrement numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to decrement the item's value
     */
    public function decrementBy($key, $offset = 1)
    {
        return apc_dec($key, $offset);
    }
}