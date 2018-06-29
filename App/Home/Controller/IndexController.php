<?php
namespace Home\Controller;

use Common\Model\ConfigModel;
use Common\Service\Remote\Remote;
use Common\Service\SMS\SMS;
use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        dump( Remote::createWSDL( AuthController::class, 'Auth' ) );
    }

    public function server ()
    {
//        $soap               =   Remote::create_soap_server('Auth',['index']);

        $soapServer             =   new \SoapServer( null, [
            'location'  =>  'http://192.168.0.106:8083/soap/server',
            'uri'       =>  '123456',
            'trace'     =>  1,
        ] );

        $soapServer->setClass( AuthController::class );

        $soapServer->addFunction('index');

        $soapServer->handle();
    }

}