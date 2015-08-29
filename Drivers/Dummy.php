<?php

namespace Panada\Cache\Drivers;

use Panada\Cache\CacheInterface;

/**
 * Panada Local Memory Cacher.
 * This class useful when you calling an object twice or
 * more in a single run time.
 *
 * @package Driver
 * @subpackage	Cache
 * @author	Iskandar Soesman
 * @since	Version 0.3
 */
class Dummy implements CacheInterface
{    
    static private $holder = [];
    
    /**
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @return void
     */
    public function setValue($key, $value, $expire = 0, $namespace = false)
    {
        $key = $this->keyToNamespace($key, $namespace);
        
        self::$holder[$key] = $value;
        
        return true;
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
    public function addValue($key, $value, $expire = 0, $namespace = false)
    {    
        return $this->getValue($key, $namespace) ? false : $this->setValue($key, $value, $expire, $namespace);
    }
    
    /**
     * Update cache value base on the key given.
     *
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @return void
     */
    public function updateValue( $key, $value, $expire = 0, $namespace = false)
    {    
        return $this->setValue($key, $value, $expire, $namespace);
    }
    
    /**
     * @param string $key
     * @return mix
     */
    public function getValue($key, $namespace = false)
    {
        $key = $this->keyToNamespace($key, $namespace);
        
        if( isset(self::$holder[$key]) )
            return self::$holder[$key];
        
        return false;
    }
    
    /**
     * @param string $key
     * @return void
     */
    public function deleteValue($key, $namespace = false)
    {
        $key = $this->keyToNamespace($key, $namespace);
        
        unset(self::$holder[$key]);
        
        return true;
    }
    
    /**
     * Flush all cached object.
     * @return bool
     */
    public function flushValues()
    {    
        unset(self::$holder);
        
        return true;
    }
    
    /**
     * Increment numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to increment the item's value
     */
    public function incrementBy($key, $offset = 1)
    {    
        $incr = $this->getValue($key) + $offset;
        
        $this->updateValue($key, $incr);
	
        return $incr;
    }
    
    /**
     * Decrement numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to decrement the item's value
     */
    public function decrementBy($key, $offset = 1)
    {
        $decr = $this->getValue($key) - $offset;
        
        $this->updateValue($key, $decr);
        
        return $decr;
    }
    
    /**
     * Namespace usefull when we need to wildcard deleting cache object.
     *
     * @param string $namespaceKey
     * @return int Unixtimestamp
     */
    private function keyToNamespace($key, $namespaceKey = false)
    {
        if( ! $namespaceKey )
            return $key;
        
        if( ! $namespaceValue = $this->getValue($namespaceKey) ){
            $namespaceValue = mt_rand();
            $this->setValue($namespaceKey, $namespaceValue, 0);
        }
	
        return $namespaceValue.$key;
    }
}
