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
 * @param $json
 * @param $propertyName
 * @return null
 */

namespace Barion\Helpers;

class JSON {

    /**
     * Gets the value of the specified property from the json
     *
     * @param mixed $json The json
     * @param string $propertyName
     * @return ?string The value of the property as string
     */ 
    public static function getString($json, $propertyName)
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
     * Gets the value of the specified property from the json
     *
     * @param mixed $json The json
     * @param string $propertyName
     * @return ?int The value of the property as int
     */ 
    public static function getInt($json, $propertyName)
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_array($value) || is_object($value)) {
            return null;
        }
        
        return intval($value);
        //return isset($json[$propertyName]) ? (int)$json[$propertyName] : null;
    }
    
    
    /**
     * Gets the value of the specified property from the json
     *
     * @param mixed $json The json
     * @param string $propertyName
     * @return ?float The value of the property as float
     */ 
    public static function getFloat($json, $propertyName)
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_array($value) || is_object($value)) {
            return null;
        }
        
        return floatval($value);
        //return isset($json[$propertyName]) ? (float)$json[$propertyName] : null;
    }
    
    /**
     * Gets the value of the specified property from the json
     *
     * @param mixed $json The json
     * @param string $propertyName
     * @return ?bool The value of the property as bool
     */ 
    public static function getBool($json, $propertyName)
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_array($value) || is_object($value)) {
            return null;
        }
        
        return (bool)$value;
        //return isset($json[$propertyName]) ? (bool)$json[$propertyName] : null;
    }        
    
    /**
     * Gets the value of the specified property from the json
     *
     * @param mixed $json The json
     * @param string $propertyName
     * @return ?array<mixed> The value of the property as an array
     */ 
    public static function getArray($json, $propertyName)
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_array($value)) {
            return $value;
        }
        
        return null;
        //return isset($json[$propertyName]) ? (array)$json[$propertyName] : null;
    }
    
    /**
     * Gets the value of the specified property from the json
     *
     * @param mixed $json The json
     * @param string $propertyName
     * @return ?object The value of the property as object
     */ 
    public static function getObject($json, $propertyName)
    {
        if (!isset($json[$propertyName])) {
            return null;
        }
        
        $value = $json[$propertyName];
        
        if (is_object($value)) {
            return $value;
        }
        
        return null;
        //return isset($json[$propertyName]) ? (object)$json[$propertyName] : null;
    }    

}