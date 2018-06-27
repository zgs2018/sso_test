<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        dump(C('SESSION_OPTIONS'));
        dump(session_id());
        dump( cookie() );
    }

}