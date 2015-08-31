<?php

namespace Panada\Cache;

/**
 * Panada cache API.
 *
 * @package	Cache
 * @link	http://panadaframework.com/
 * @license http://opensource.org/licenses/MIT
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.3
 */
class Cache
{
    use CacheDI;
    
    private $DIObject;
    protected static $instance = [];
    public $config = [
        'driver' => 'dummy'
    ];
    
    public function __construct($config = [])
    {
        $this->setOption($config);
    }
    
    public static function getInstance($type = 'default')
    {
        if (! isset(self::$instance[$type])) {
            self::$instance[$type] = new static(\Panada\Resource\Config::cache()[$type]);
        }
        
        return self::$instance[$type];
    }
    
    /**
     * Overrider for cache config option.
     *
     * @param array $option The new option.
     * @return void
     * @since version 1.0
     */
    public function setOption($config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->init();
    }
    
    /**
     * Instantiate the driver class
     *
     * @return void
     * @since version 1.0
     */
    private function init()
    {
        $driverNamespace = 'Panada\Cache\Drivers\\' . ucwords($this->config['driver']);
        $this->DIObject = new $driverNamespace($this->config);
    }
}
