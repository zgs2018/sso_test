<?php
namespace Home\Controller;

use Think\Controller;
use Think\Log;

class AuthController extends Controller
{
    public function auth()
    {
        throw new \SoapFault('evnew', '无权访问');
    }

    public function index ()
    {
//        session('name','laozhou');
        return session();
    }
}