<?php
namespace Common\Service;

abstract class Service
{
    abstract public static function name();

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        $currentClasspath               =   static::class;
        $currentNamespace               =   __NAMESPACE__;
        $callClassname                  =   '';
        dump($currentNamespace);exit;
        $classprefixname                =   static::name();
        $classspace             =   __NAMESPACE__ . '\\' . $classprefixname . '\\' .$classprefixname . 'Service';
        return (new $classspace)->{$name}(...$arguments);
    }
}