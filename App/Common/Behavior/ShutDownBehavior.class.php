<?php
namespace Common\Behavior;
use Think\Behavior;

class ShutDownBehavior extends Behavior
{
    public function run(&$params)
    {
        // TODO: Implement run() method.
        if( C('APP_SWITCH')!==true ){
            static::shutDown();
        }
    }

    public static function shutDown ($message='维护中，请稍后再试'){
        exit("<h3>{$message}</h3>");
    }
}