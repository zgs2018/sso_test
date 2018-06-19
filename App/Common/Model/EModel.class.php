<?php
namespace Common\Model;

use Think\Model;

abstract class EModel extends Model
{
    protected $dbName               =   'education';

    protected $tablePrefix          =   '';
}