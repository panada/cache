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
    private $driver;
    protected static $instance = [];
    private $config = [
        'driver' => 'apc'
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
        $this->driver = new $driverNamespace($this->config);
    }
    
    /**
     * Use magic method 'call' to pass user method
     * into driver method
     *
     * @param string @name
     * @param array @arguments
     */
    public function __call($name, $arguments)
    {    
        return call_user_func_array([$this->driver, $name], $arguments);
    }
    
    /**
     * PHP Magic method for calling a class property dinamicly
     * 
     * @param string $name
     * @return mix
     */
    public function __get($name)
    {    
        return $this->driver->$name;
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
        $this->driver->$name = $value;
    }
}
