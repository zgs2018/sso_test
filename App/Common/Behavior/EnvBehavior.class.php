<?php

namespace Common\Behavior;

use Think\Behavior;

class EnvBehavior extends Behavior
{
    public function run(&$params)
    {
        // TODO: Implement run() method.
        $envfile            =   __ROOT__.'.env';
        if( !is_file($envfile) ) return ;
        $file_array         =   file( $envfile, FILE_IGNORE_NEW_LINES  );
        $file_array         =   array_filter( $file_array );
        $file_array         =   array_map( function($v){
            $item       =   explode( '=', $v );
            return $item;
        }, $file_array );
        foreach ($file_array as $value){
            if( !array_key_exists( 0, $value ) || !array_key_exists( 0, $value ) )
                continue;
            apache_setenv( $value[0], $value[1] );
        }

        return ;
    }
}