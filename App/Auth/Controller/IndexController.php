<?php
namespace Auth\Controller;
use Think\Controller;

class IndexController extends Controller
{
    public function index ()
    {
        $this->display();
    }

    public function login ()
    {
        dump( I() );
        exit;
        $this->display();
    }
}