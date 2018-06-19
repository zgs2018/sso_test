<?php

if( !function_exists('') ){

}

if( !function_exists('str2array') ){

    function str2array($str,$delimiter=',')
    {
        if( !is_string($str) )  return false;
        if( $str === "" )   return [];
        return explode($delimiter, $str);
    }
}
