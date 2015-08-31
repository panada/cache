<?php

namespace Panada\Cache;

trait CacheTrait
{
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