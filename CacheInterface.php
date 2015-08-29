<?php

namespace Panada\Cache;

/**
 * Interface for Session Drivers
 *
 * @package  Session
 * @author   Iskandar Soesman <k4ndar@yahoo.com>
 * @link     http://panadaframework.com/
 * @license  http://opensource.org/licenses/MIT
 * @since    version 1.0.0
 */
interface CacheInterface
{    
    public function setValue( $key, $value, $expire = 0, $namespace = false );
    public function addValue( $key, $value, $expire = 0, $namespace = false );
    public function updateValue( $key, $value, $expire = 0, $namespace = false );
    public function getValue( $key, $namespace = false );
    public function deleteValue( $key, $namespace = false );
    public function flushValues();
    public function incrementBy($key, $offset = 1);
    public function decrementBy($key, $offset = 1);
}