<?php
namespace Auth\Behavior;

use Think\Behavior;

class InterceptBehavior extends Behavior
{
    public function run(&$params)
    {
        // TODO: Implement run() method.
    }

    public static function auth_status()
    {
        return session('?_user');
    }
}