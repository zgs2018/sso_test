<?php
namespace Home\Model;

use Common\Model\EModel;

class LetterModel extends EModel
{
    protected $tableName                    =   'letter';

    public function unreadCount ($where=null)
    {
        $_where['status']           =  ['eq',0];
        if( !is_null($where) && is_array($where) )
            $_where         =   array_merge( $_where, $where );
        return $this->where($_where)->count();
    }
}