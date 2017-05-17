<?php


namespace BitrixBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     * @throws \RuntimeException
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bitrix');

        $rootNode
            ->children()
                ->arrayNode('server')
                    ->children()
                        ->scalarNode('root')->end()
                        ->arrayNode('index_files')
                            ->useAttributeAsKey('key')
                            ->prototype('scalar')->end()
                        ->end()
                        ->scalarNode('urlrewrite')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}