<?php


namespace BitrixBundle\Routing;


use Symfony\Component\Routing\CompiledRoute;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCompilerInterface;

class RouteCompiler implements RouteCompilerInterface
{

    /**
     * Compiles the current route instance.
     *
     * @param Route $route A Route instance
     *
     * @return CompiledRoute A CompiledRoute instance
     *
     * @throws \LogicException If the Route cannot be compiled because the
     *                         path or host pattern is invalid
     */
    public static function compile(Route $route)
    {
        return new CompiledRoute(
            '/',
            $route->getCondition(),
            $tokens,
            $pathVariables,
            $hostRegex,
            $hostTokens,
            $hostVariables,
            array_unique($variables)
        );
    }
}