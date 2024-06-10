<?php

/**
 * Copyright 2016 Barion Payment Inc. All Rights Reserved.
 * <p/>
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * <p/>
 * http://www.apache.org/licenses/LICENSE-2.0
 * <p/>
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Barion\Helpers;

class JSON {

    /**
     * Gets the value of the specified property from the JSON as a string.
     *
     * @param mixed $json The JSON
     * @param string $propertyName
     * @return ?string The value of the property as string
     */ 
    public static function getString(mixed $json, string $propertyName): ?string
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_array($value) || is_object($value)) {
            return null;
        }
        
        return strval($value);
    }
    
    /**
     * Gets the value of the specified property from the JSON as an integer number.
     *
     * @param mixed $json The JSON
     * @param string $propertyName
     * @return ?int The value of the property as int
     */ 
    public static function getInt(mixed $json, string $propertyName): ?int
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_array($value) || is_object($value)) {
            return null;
        }
        
        return intval($value);
    }
    
    
    /**
     * Gets the value of the specified property from the JSON as a floating point number.
     *
     * @param mixed $json The JSON
     * @param string $propertyName
     * @return ?float The value of the property as float
     */ 
    public static function getFloat(mixed $json, string $propertyName): ?float
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_array($value) || is_object($value)) {
            return null;
        }
        
        return floatval($value);
    }
    
    /**
     * Gets the value of the specified property from the JSON as a boolean.
     *
     * @param mixed $json The JSON
     * @param string $propertyName
     * @return ?bool The value of the property as bool
     */ 
    public static function getBool(mixed $json, string $propertyName): ?bool
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_array($value) || is_object($value)) {
            return null;
        }
        
        return (bool)$value;
    }        
    
    /**
     * Gets the value of the specified property from the JSON as an associative array.
     *
     * @param mixed $json The JSON
     * @param string $propertyName
     * @return ?array The value of the property as an array
     */ 
    public static function getArray(mixed $json, string $propertyName): ?array
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_array($value)) {
            return $value;
        }
        
        return null;
    }
    
    /**
     * Gets the value of the specified property from the JSON as a standard PHP object.
     *
     * @param mixed $json The JSON
     * @param string $propertyName
     * @return ?object The value of the property as object
     */ 
    public static function getObject(mixed $json, string $propertyName): ?object
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_object($value)) {
            return $value;
        }
        
        return null;
    }    

}