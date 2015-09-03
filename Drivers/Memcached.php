<?php

namespace Panada\Cache\Drivers;

/**
 * Panada Memcache API Driver.
 *
 * @package	Cache
 * @license http://opensource.org/licenses/MIT
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 1.0
 */
class Memcached extends Memcache
{
    public function __construct($config = [])
    {
		parent::__construct($config);
    }
	
	protected function init($config)
	{
		if( ! extension_loaded('memcached') )
			throw new \Exception('Memcached extension that required by Driver memcached is not available.');
        
		$this->DIObject = new \Memcached;
		$this->config	= array_merge($this->config, $config);
		
        foreach($this->config['server'] as $server){
			$this->DIObject->addServer($server['host'], $server['port'], $server['persistent']);
		}
	}
}
