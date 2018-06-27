<?php

if( !function_exists('') ){

}

if( !function_exists('str2array') ){

    function str2array($str,$delimiter=',')
    {
        if( is_array($str) )  return $str;
        if( !is_string($str) )  return false;
        if( $str === "" )   return [];
        return explode($delimiter, $str);
    }
}

if( !function_exists('exists_key') ){
    /**
     * @param $key
     * @param $array
     * @param int $mode 1.严谨模式 2.模糊模式
     * @return bool
     */
    function exists_key ($key, $array, $mode=1)
    {
        // 验证key合法度
        if( !is_array( $array ) )
            return false;
        $result             =   false;
        if(is_string($key)){
            switch ($mode){
                case 1:
                    $result =   array_key_exists($key,$array) && $array[$key];
                    break;
                case 2:
                    $result =   array_key_exists($key,$array);
                    break;
            }
        }
        return (boolean)$result;
    }
}