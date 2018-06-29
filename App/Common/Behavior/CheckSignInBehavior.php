<?php
namespace Common\Behavior;

use Think\Behavior;

class CheckSignInBehavior extends Behavior
{
    public function run(&$params)
    {
        // TODO: Implement run() method.

        if( $_SERVER['REDIRECT_URL'] == '/login' )
           return ;

        if( !self::is_login() ){
            redirect('/login');
        }

    }

    public static function is_login ()
    {
        return session( '?_user' );
    }
}