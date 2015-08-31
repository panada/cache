<?php

namespace Panada\Cache\Tests;

use Panada\Cache\Cache;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->cache = new Cache;
		$this->cache->deleteValue('counter');
    }
    
    public function testBasicCRUD()
    {
        $key = 'foo';
        $val = 'bar';
        $val2 = 'bar2';
        
        $this->assertTrue( (boolean) $this->cache->setValue($key, $val));
        $this->assertEquals($val, $this->cache->getValue($key));
        $this->assertTrue( (boolean) $this->cache->updateValue($key, $val2));
        $this->assertEquals($val2, $this->cache->getValue($key));
        $this->assertTrue( (boolean) $this->cache->deleteValue($key));
        $this->assertEquals(false, $this->cache->getValue($key));
    }
    
    public function testCounter()
    {
        $key = 'counter';
        $this->assertEquals(2, $this->cache->incrementBy($key, 2));
        $this->assertEquals(7, $this->cache->incrementBy($key, 5));
        $this->assertEquals(6, $this->cache->decrementBy($key, 1));
    }
    
    public function testNamespace()
    {
        $namespace = 'comment_id_29';
        
        $key1 = 'comment_page_1';
        $val1 = 'abc';
        $this->cache->setValue($key1, $val1, 0, $namespace);
        $this->assertEquals($val1, $this->cache->getValue($key1, $namespace));
	
        $key2 = 'comment_page_2';
        $val2 = 'def';
        $this->cache->setValue($key2, $val2, 0, $namespace);
        $this->assertEquals($val2, $this->cache->getValue($key2, $namespace));
        
        $this->assertTrue( (boolean) $this->cache->deleteValue($namespace));
        
        $this->assertEquals(false, $this->cache->getValue($key1, $namespace));
        $this->assertEquals(false, $this->cache->getValue($key2, $namespace));
    }
}
