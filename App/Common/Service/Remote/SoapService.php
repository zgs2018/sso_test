<?php
namespace Common\Service\Remote;
use Common\Service\Service;
use Common\Provide\WSDL;

class SoapService
{
    /**
     * @ 生成 wsdl服务 文件
     * @param $class
     * @param $service
     * @return bool|int
     */
    public function createWSDL ($class, $service, $cover=false)
    {
        $info               =   [
            'class_name'        =>  $class,
            'service_name'      =>  $service,
            'save_name'         =>  $this->wsdl_path($service),
        ];
        // 服务是否存在、是否替换
        (!$cover&&$this->wsdl_name($service)) && E( "wsdl:{$service}服务已存在" );
        $wsdl               =   new WSDL( $info );
        return $wsdl->createWSDL();
    }

    /**
     * @ 获取wsdl服务文件目录
     * @param $service
     * @return string
     */
    public function wsdl_path ($service)
    {
        return  COMMON_PATH . 'Service/Remote/WSDL/' . $service;
    }

    /**
     * @ 获取wsdl服务文件路径
     * @param bool $service
     * @return bool|string
     */
    public function wsdl_name ($service=false)
    {
        $service===false && E("wsdl:服务名未传入");
        return file_exists( $this->wsdl_path($service).'.wsdl' )
            ? $this->wsdl_path($service).'.wsdl'
            : false;
    }

    /**
     * @ 创建soap服务
     * @param $server
     * @param array $methods
     * @return \SoapServer
     */
    public function create_soap_server ($server, $methods=[])
    {
        $options                =   [
            'soap_version'          =>  SOAP_1_1,
        ];
        $server                 =   new \SoapServer( 'http://www.tp3.self/' . $this->wsdl_name( $server ), $options );

        if( $methods ){
            foreach ( $methods as $func ){
                $server->addFunction($func);
            }
        }
        $server->handle();

        return $server;
    }
}