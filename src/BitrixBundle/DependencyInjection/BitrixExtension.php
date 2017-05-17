<?php


namespace BitrixBundle\DependencyInjection;


use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BitrixExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        /**
         * @var ConfigurationInterface $configuration
         */
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);


        $loader = new YamlFileLoader(
            $container,
            new FileLocator(dirname(__DIR__) . '/Resources/config')
        );
        $loader->load('config.yml');

        $rootDir = $container->getParameter('kernel.root_dir');
        if ($config['server']) {
            if ($config['server']['root']) {
                $container->setParameter('bitrix.root', realpath($rootDir . $config['server']['root']));
            }
            if ($config['server']['index_files']) {
                $container->setParameter('bitrix.index_files', $config['server']['index_files']);
            }
            if ($config['server']['urlrewrite']) {
                $container->setParameter('bitrix.urlrewrite', $config['server']['urlrewrite']);
            } else {
                $container->setParameter('bitrix.urlrewrite', dirname(__DIR__) . '/Resources/php/urlrewrite.php');
            }
        }

    }
}
