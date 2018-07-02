<?php
namespace Auth\Controller;
use Think\Controller;
use Think\Hook;

class IndexController extends Controller
{
    public function _initialize ()
    {
        Hook::listen( 'authorized_interceptor' );
    }

    public function index ()
    {
        $this->display();
    }

    public function login ()
    {
        $this->display();
    }
}