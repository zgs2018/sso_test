<?php
namespace Home\Controller;

use Common\Service\Remote\Remote;
use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        dump( Remote::create('laozhou','28') );
    }
}