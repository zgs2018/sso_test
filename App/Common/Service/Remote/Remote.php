<?php
namespace Common\Service\Remote;

use Common\Service\Service;

class Remote extends Service
{
    protected static $name        =   'Soap';

    public static function name($name=false)
    {
        // TODO: Implement name() method.
        $name && (static::$name=$name);
        return static::$name;
    }
}