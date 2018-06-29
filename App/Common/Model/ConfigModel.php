<?php

namespace Common\Model;

class ConfigModel extends MxModel
{
    protected $tableName                =   'config';

    public function getConfig ($name=false)
    {
        if( $name===false )
            return false;
        return $this->field('value')
            ->where(['name'=>['eq',$name]])
            ->getField('value');
    }

}