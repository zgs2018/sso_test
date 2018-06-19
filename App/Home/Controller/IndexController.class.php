<?php
namespace Home\Controller;

use Course\Model\CourseModel;
use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $this->display();
    }

    public function test()
    {
        $result     =   [
            'name'      =>  'Luke',
            'age'       =>  18,
            'sex'       =>  'male',
        ];

        $this->display();
        $this->ajaxReturn( $result, 'xml' );
    }
}