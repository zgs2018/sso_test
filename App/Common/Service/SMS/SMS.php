<?php
namespace Common\Service\SMS;

use Common\Service\Service;

class SMS extends Service
{
    protected static $name         =   'Bao';

    public static function name($name=false)
    {
        // TODO: Implement name() method.
        $name && (static::$name=$name);
        return static::$name;
    }
}