<?php

/*
*  Helper functions
*/

function jget($json, $propertyName)
{
    return isset($json[$propertyName]) ? $json[$propertyName] : null;
}

function endsWith($haystack, $needle) {
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

?>