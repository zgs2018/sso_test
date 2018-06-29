<?php
namespace Common\Provide;

use Think\Exception;
use ReflectionClass;
class WSDL
{
    private $class_name     = '';
    private $service_name   = '';
    private $save_name      = '';

    /**
     * @ 初始化构造 传入基本信息
     * WSDL constructor.
     * @param array $info
     */
    public function __construct($info=[]) {
        $this->class_name           =   exists_key( 'class_name', $info )   ? $info['class_name'] : E('参数缺失：1');
        $this->service_name         =   exists_key( 'service_name', $info ) ? $info['service_name'] : E('参数缺失：2');
        $this->save_name            =   exists_key( 'save_name', $info )    ? $info['save_name'] : E('参数缺失：3');
    }

    /**
     * @ 生成wsdl文件
     * @return bool|int
     * @throws Exception
     */
    public function createWSDL()
    {
        if (empty($this->service_name)) {
            throw new Exception('No service name.');
        }
        $headerWSDL = "<?xml version=\"1.0\" ?>\n";
        $headerWSDL.= "<definitions name=\"$this->service_name\" targetNamespace=\"urn:$this->service_name\" xmlns:wsdl=\"http://schemas.xmlsoap.org/wsdl/\" xmlns:soap=\"http://schemas.xmlsoap.org/wsdl/soap/\" xmlns:tns=\"urn:$this->service_name\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:SOAP-ENC=\"http://schemas.xmlsoap.org/soap/encoding/\" xmlns=\"http://schemas.xmlsoap.org/wsdl/\">\n";
        $headerWSDL.= "<types xmlns=\"http://schemas.xmlsoap.org/wsdl/\" />\n";

        if (empty($this->class_name)) {
            throw new Exception('No class name.');
        }

        $class = new ReflectionClass($this->class_name);

        if (!$class->isInstantiable()) {
            throw new Exception('Class is not instantiable.');
        }

        $methods = $class->getMethods();

        $portTypeWSDL = '<portType name="'.$this->service_name.'Port">';
        $bindingWSDL = '<binding name="'.$this->service_name.'Binding" type="tns:'.$this->service_name."Port\">\n<soap:binding style=\"rpc\" transport=\"http://schemas.xmlsoap.org/soap/http\" />\n";
        $serviceWSDL = '<service name="'.$this->service_name."\">\n<documentation />\n<port name=\"".$this->service_name.'Port" binding="tns:'.$this->service_name."Binding\"><soap:address location=\"http://".$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['PHP_SELF']."\" />\n</port>\n</service>\n";
        $messageWSDL = '';
        foreach ($methods as $method) {
            if ($method->isPublic() && !$method->isConstructor()) {
                $portTypeWSDL.= '<operation name="'.$method->getName()."\">\n".'<input message="tns:'.$method->getName()."Request\" />\n<output message=\"tns:".$method->getName()."Response\" />\n</operation>\n";
                $bindingWSDL.= '<operation name="'.$method->getName()."\">\n".'<soap:operation soapAction="urn:'.$this->service_name.'#'.$this->class_name.'#'.$method->getName()."\" />\n<input><soap:body use=\"encoded\" namespace=\"urn:$this->service_name\" encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\" />\n</input>\n<output>\n<soap:body use=\"encoded\" namespace=\"urn:$this->service_name\" encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\" />\n</output>\n</operation>\n";
                $messageWSDL.= '<message name="'.$method->getName()."Request\">\n";
                $parameters = $method->getParameters();
                foreach ($parameters as $parameter) {
                    $messageWSDL.= '<part name="'.$parameter->getName()."\" type=\"xsd:string\" />\n";
                }
                $messageWSDL.= "</message>\n";
                $messageWSDL.= '<message name="'.$method->getName()."Response\">\n";
                $messageWSDL.= '<part name="'.$method->getName()."\" type=\"xsd:string\" />\n";
                $messageWSDL.= "</message>\n";
            }
        }
        $portTypeWSDL.= "</portType>\n";
        $bindingWSDL.= "</binding>\n";
        $fso = fopen($this->save_name . ".wsdl" , "w");
        return fwrite($fso, sprintf('%s%s%s%s%s%s', $headerWSDL, $portTypeWSDL, $bindingWSDL, $serviceWSDL, $messageWSDL, '</definitions>'));
    }

    public function getDiscovery() {
        return "<?xml version=\"1.0\" ?>\n<disco:discovery xmlns:disco=\"http://schemas.xmlsoap.org/disco/\" xmlns:scl=\"http://schemas.xmlsoap.org/disco/scl/\">\n<scl:contractRef ref=\"http://".$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['PHP_SELF']."?wsdl\" />\n</disco:discovery>";
    }
}
