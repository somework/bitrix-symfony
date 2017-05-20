<?php


namespace BitrixBundle\Proxy;


use Symfony\Component\DependencyInjection\ContainerInterface;

class Container
{
    /**
     * @var ContainerInterface
     */
    protected static $container;

    protected function __construct()
    {
    }

    /**
     * @return ContainerInterface
     * @throws \RuntimeException
     */
    public static function getInstance()
    {
        if (static::$container instanceof ContainerInterface) {
            return static::$container;
        }
        throw new \RuntimeException('ContainerInterface was not set');
    }

    public static function setInstance(ContainerInterface $container)
    {
        /** @noinspection DisallowWritingIntoStaticPropertiesInspection */
        static::$container = $container;
    }
}