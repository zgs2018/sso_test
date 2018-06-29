<?php
namespace Common\Service;

abstract class Service
{
    protected static $name;

    public static $classSubfix      =   'Service';

    abstract public static function name();

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        // 入口类的类路径
        $entryClasspath                 =   get_called_class();
        // 分割点
        $splitPoint                     =   strripos ($entryClasspath, '\\');
        // 服务名称
        $serviceName                    =   substr( $entryClasspath, $splitPoint+1 );
        // 入口类的命名空间
        $entryNamespace                 =   substr( $entryClasspath, 0, $splitPoint );
        // 被执行类的类名
        $calledClassname                =   static::$name . self::$classSubfix;
        // 被执行类的类路径
        $calledClasspath                =   $entryNamespace . '\\' . $calledClassname;
        class_exists($calledClasspath) || E("{$serviceName}服务的{$calledClassname}扩展未注册");
        //
        $called                         =   new $calledClasspath;
        return $called->{$name}(...$arguments);
    }
}