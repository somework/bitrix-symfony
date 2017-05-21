<?php


namespace BitrixBundle\Routing;


use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class BitrixLoader extends Loader
{
    protected $loaded = false;

    /**
     * @var string
     */
    private $bitrixRoot;

    public function __construct($bitrixRoot)
    {
        $this->bitrixRoot = $bitrixRoot;
    }

    /**
     * Loads a resource.
     *
     * @param mixed       $resource The resource
     * @param string|null $type     The resource type or null if unknown
     *
     * @throws \Exception If something went wrong
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "bitrix" loader twice');
        }

        $realResource = implode(DIRECTORY_SEPARATOR, [
            rtrim($this->bitrixRoot, DIRECTORY_SEPARATOR),
            ltrim($resource, DIRECTORY_SEPARATOR),
        ]);

        if (!is_file($realResource) || !file_exists($realResource) || !is_readable($realResource)) {
            throw new \InvalidArgumentException(sprintf('Cant find urlrewrite for %s', $realResource));
        }

        $routes = new RouteCollection();

        $arUrlRewrite = [];
        /** @noinspection PhpIncludeInspection */
        include $realResource;
        foreach ($arUrlRewrite as $rewriteRule) {
            if ($rewriteRule['RULE']) {
                parse_str($rewriteRule['RULE'], $stringPart);
//                dump($stringPart);
            }
        }
//        die();
        return $routes;
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed       $resource A resource
     * @param string|null $type     The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return 'bitrix' === $type;
    }
}