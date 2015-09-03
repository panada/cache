<?php

namespace Panada\Cache\Drivers;

use Panada\Cache\CacheInterface;
use Panada\Cache\CacheTrait;
use Panada\Cache\CacheDI;

/**
 * Panada Memcache API Driver.
 *
 * @package	Cache
 * @license http://opensource.org/licenses/MIT
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 1.0
 */
class Memcache implements CacheInterface
{
	use CacheTrait;
	use CacheDI;
	
    protected $config = [
        'server' => [
            [
                'host' => 'localhost',
                'port' => 11211,
                'persistent' => false,
				'compressThreshold' => [20000, 0.2],
				'saslAuthData' => [false, false]
            ],
        ]
	];
	
	protected $DIObject;
    
    /**
     * Load configuration from config file.
     * @return void
     */
    
    public function __construct($config = [])
    {
		$this->init($config);
    }
	
	protected function init($config)
	{
		if( !extension_loaded('memcache'))
			throw new \Exception('Memcache extension that required by Memcache Driver is not available.');
		
		$this->DIObject	= new \Memcache;
		$this->config	= array_merge($this->config, $config);
		
		foreach($this->config['server'] as $server) {
			$this->DIObject->addServer($server['host'], $server['port'], $server['persistent']);
			$this->DIObject->setCompressThreshold($server['compressThreshold'][0], $server['compressThreshold'][1]);
		}
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
        
		return $this->DIObject->set($key, $value, 0, $expire);
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
		
		return $this->DIObject->add($key, $value, 0, $expire); 
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
		
		return $this->DIObject->replace($key, $value, 0, $expire);
    }
    
    /**
     * @param string $key
     * @param string $namespace
     * @return mix
     */
    public function getValue( $key, $namespace = false )
    {    
		$key = $this->keyToNamespace($key, $namespace);
        
		return $this->DIObject->get($key);
    }
    
    /**
     * @param string $key
     * @param string $namespace
     * @return void
     */
    public function deleteValue( $key, $namespace = false )
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
		return $this->DIObject->flush();
    }
    
    /**
     * Increment numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to increment the item's value
     */
    public function incrementBy($key, $offset = 1)
    {
		$this->DIObject->add($key, 0);
		
		return $this->DIObject->increment($key, $offset);
    }
    
    /**
     * Decrement numeric item's value
     *
     * @param string $key The key of the item
     * @param int $offset The amount by which to decrement the item's value
     */
    public function decrementBy($key, $offset = 1)
    {
		return $this->DIObject->decrement($key, $offset);
    }
}
