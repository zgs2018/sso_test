<?php
namespace Common\Behavior;

use Think\Behavior;

class CheckRefererBehavior extends Behavior
{
    public function run(&$params)
    {
        // TODO: Implement run() method.
        $http_referer               =   false;
        if( exists_key('HTTP_REFERER', $_SERVER) ){
            $http_referer           =   $_SERVER['HTTP_REFERER'];
        }
    }
}