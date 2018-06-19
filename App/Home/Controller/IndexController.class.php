<?php
namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Controller;
use Think\Controller\RpcController;

class IndexController extends Controller
{
    public function index()
    {
        $this->display();
    }

    public function test ()
    {
        $ip     =   get_client_ip();

        $ipObj = new \Org\Net\IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
        $area = $ipObj->getlocation('101.132.107.193'); // 获取某个IP地址所在的位置

        dump($area);

    }

}