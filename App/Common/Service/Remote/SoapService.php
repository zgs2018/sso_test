<?php
namespace Common\Service\Remote;
use Common\Service\Service;

class SoapService
{
    public function create ($filename)
    {
        return "创建文件：{$filename}";
    }
}