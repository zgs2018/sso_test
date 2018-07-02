<?php
namespace Common\Behavior;

use Think\Behavior;
use Think\Log;

class CheckOriginBehavior extends Behavior
{
    public function run(&$params)
    {
        // TODO: Implement run() method.
        $http_origin                =   false;
        if( exists_key( 'HTTP_ORIGIN', $_SERVER ) ){
            $http_origin        =   $_SERVER['HTTP_ORIGIN'];
        }elseif( exists_key( 'HTTP_REFERER', $_SERVER ) ){
            $http_origin        =   $_SERVER['HTTP_REFERER'];
        }else{
            self::termination( '' );
            return ;
        }
        // 来源判断
        if( $http_origin === false )    return ;

        if( static::checkOrigin($http_origin,C('ALLOW_ORIGIN')) ){
            static::allowOrigin($http_origin);
            Log::write( $http_origin, Log::NOTICE, '', LOG_PATH . 'Origin/' );
            return ;
        } else{
            static::termination($http_origin,true);
        }
    }

    /**
     * @ 检测来源是否合法
     * @param $main_domain
     * @param $allows
     * @return bool
     */
    public static function checkOrigin ($http_origin,$allows)
    {
        // 获取来源主域
        $resolved                   =   resolve_url($http_origin);
        $main_domain                =   $resolved['main'] . '.' . $resolved['postfix'];
        // 请求来源
        if( in_array(trim($main_domain), $allows) )
            return true;
        return false;
    }

    /**
     * @ 允许跨域操作
     * @param $domain
     * @param string $methods
     * @param string $credentials
     */
    public static function allowOrigin ($domain, $methods='GET', $credentials='true')
    {
        header('Access-Control-Allow-Origin: '.$domain);
        header('Access-Control-Allow-Methods: '.$methods);
        header('Access-Control-Allow-Headers: x-requested-with');
        header('Access-Control-Allow-Credentials: '.$credentials);
        return;
    }

    /**
     * @ 禁止跨域结束
     * @param $domain
     * @param bool $record
     */
    public static function termination ($domain, $record=false)
    {
        $data       =   [
            'result'        =>  403,
            'error'         =>  'Access Deny!',
        ];
        if( $record ){
            $message        =   "ORIGIN:'{$domain}' try to access {$_SERVER['REQUEST_URI']} !";
            Log::write($message);
        }
        header('Content-Type:application/json; charset=utf-8');
        $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
        exit($handler.'('.json_encode($data,0).');');
    }
}