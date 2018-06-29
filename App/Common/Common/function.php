<?php

if( !function_exists('') ){

}

if( !function_exists('str2array') ){

    /**
     * @
     * @param $str
     * @param string $delimiter
     * @return array|bool
     */
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

if( !function_exists('resolve_url') ){
    /**
     * @ 解析url
     * @param $domain
     * @return array
     */
    function resolve_url ($url)
    {
        preg_match(
            "/^(?:([^:]+):\/\/)?(?:(?:([a-z^\.]+)\.)?([^\.]+)\.([^\/]+)[\/]?)([^\?^#]+)?(?:[\?]([^#]+))?(?:[#](.*))?$/",
            $url,
            $matches
        );

        return [
            'origin'        =>  $url,
            'protocol'      =>  key_exists(1,$matches) ? $matches[1] : '',
            'prefix'        =>  key_exists(2,$matches) ? $matches[2] : '',
            'main'          =>  key_exists(3,$matches) ? $matches[3] : '',
            'postfix'       =>  key_exists(4,$matches) ? $matches[4] : '',
            'path'          =>  key_exists(5,$matches) ? $matches[5] : '',
            'params'        =>  key_exists(6,$matches) ? $matches[6] : '',
            'anchor'        =>  key_exists(7,$matches) ? $matches[7] : '',
        ];
    }
}

if( !function_exists('curl') ){

    function curl ($options=[],$post_data=[])
    {
        // 默认配置
        $default_aoptions               =   [
            CURLOPT_URL                     =>  false,             // 请求参数
            CURLOPT_RETURNTRANSFER          =>  true,           // return web page
            CURLOPT_HEADER                  =>  false,          // don't return headers
            CURLOPT_FOLLOWLOCATION          =>  true,           // follow redirects
            CURLOPT_ENCODING                =>  "",             // handle all encodings
            CURLOPT_USERAGENT               =>  "spider",       // who am i
            CURLOPT_AUTOREFERER             =>  true,           // set referer on redirect
            CURLOPT_CONNECTTIMEOUT          =>  120,            // timeout on connect
            CURLOPT_TIMEOUT                 =>  120,            // timeout on response
            CURLOPT_MAXREDIRS               =>  10,             // stop after 10 redirects
            CURLOPT_POST                    =>  0,              // i am sending post data
            CURLOPT_POSTFIELDS              =>  $post_data,     // this are my post vars
            CURLOPT_SSL_VERIFYHOST          =>  0,              // don't verify ssl
            CURLOPT_SSL_VERIFYPEER          =>  false,          //
            CURLOPT_VERBOSE                 =>  1,              //
            CURLOPT_USERPWD                 =>  false,
        ];
        // 执行参数
        $_options                       =   array_replace_recursive( $default_aoptions, $options );
        $_options[CURLOPT_URL] || E('请求路径缺失');
        // 初始化curl会话
        $ch                             =   curl_init();
        // 参数配置
        curl_setopt_array( $ch, $_options );
        // 执行
        $exec                           =   curl_exec($ch);
        // 结果处理
        $result['exec']                 =   $exec;
        if( $exec === false ){
            // 错误代号
            $result['errno']            =   curl_errno($ch);
            // 错误信息
            $result['error']            =   curl_error($ch);
        }
        // 句柄的信息
        $result['info']                 =   curl_getinfo($ch);
        return $result;
    }
}
