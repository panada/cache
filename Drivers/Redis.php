<?php

namespace Panada\Cache\Drivers;

use Panada\Cache\CacheInterface;
use Panada\Cache\CacheTrait;
use Panada\Cache\CacheDI;

/**
 * Panada Redis API Driver.
 *
 * @package	Cache
 * @license http://opensource.org/licenses/MIT
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 1.0
 */

/**
 * Make sure Redis extension is enabled
 */
class Redis implements CacheInterface
{
	use CacheTrait;
	use CacheDI;
	
	private $config = [
        'host' => 'localhost',
        'port' => 6379,
        'persistentId' => false, // or set a string as persistent id
		'reserved' => null,
		'retryInterval' => 0,
        'timeout' => 0,
		'password' => null
    ];
	
	private $DIObject;
    
    public function __construct($config = [])
    {
		if(! extension_loaded('redis'))
			throw new \Exception('Redis extension that required by Driver Redis is not available.');
		
		$this->DIObject = new \Redis;
		
		$this->config = array_merge($this->config, $config);
			
		if ($this->config['persistentId']){
			$this->DIObject->pconnect(
				$this->config['host'],
				$this->config['port'],
				$this->config['timeout'],
				$this->config['persistentId'],
				$this->config['retryInterval']
			);
		}
		else{
			$this->DIObject->connect(
				$this->config['host'],
				$this->config['port'],
				$this->config['timeout'],
				$this->config['reserved'],
				$this->config['retryInterval']
			);
		}
	
		$this->auth($this->config['password']);
	}
	
    /**
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @param string $namespace
     * @return void
     */
    public function setValue( $key, $value, $expire = 0, $namespace = false )
    {
		$key = $this->keyToNamespace($key, $namespace);
        $return = $this->DIObject->set($key, $value, $expire);
	
		return $return;
    }
    
    /**
     * Cached the value if the key doesn't exists,
     * other wise will false.
     *
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @param string $namespace
     * @return void
     */
    public function addValue( $key, $value, $expire = 0, $namespace = false )
    {
		
		$key = $this->keyToNamespace($key, $namespace);
		$return = $this->DIObject->setnx($key, $value, $expire);
	
		return $return;
    }
    
    /**
     * Update cache value base on the key given.
     *
     * @param string $key
     * @param mix $value
     * @param int $expire
     * @param string $namespace
     * @return void
     */
    public function updateValue( $key, $value, $expire = 0, $namespace = false )
    {
	
		$key = $this->keyToNamespace($key, $namespace);
		$return = $this->DIObject->set($key, $value, $expire);
	
		return $return;
    }
    
    /**
     * @param string $key
     * @param string $namespace
     * @return mix
     */
    public function getValue($key, $namespace = false)
    {
		$key = $this->keyToNamespace($key, $namespace);
        $return = $this->DIObject->get($key);
	
		return $return;
    }
    
    /**
     * @param string $key
     * @param string $namespace
     * @return void
     */
    public function deleteValue( $key, $namespace = false)
    {    
		$key = $this->keyToNamespace($key, $namespace);
		
        return $this->DIObject->delete($key);
    }
    
    /**
     * Flush all cached object.
     * @return bool
     */
    public function flushValues()
    {    
		return $this->DIObject->flushDB();
    }
    
    /**
     * Increment numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to increment the item's value
     */
    public function incrementBy($key, $offset = 1)
    {
		return $this->DIObject->incr($key, $offset);
    }
    
    /**
     * Decrement numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to decrement the item's value
     */
    public function decrementBy($key, $offset = 1)
    {
		return $this->DIObject->decr($key, $offset);
    }
}
