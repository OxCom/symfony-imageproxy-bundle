<?php

namespace SymfonyImageProxyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Reference;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder;
use SymfonyImageProxyBundle\Providers\ImgProxy\Security;

class SymfonyImageProxyExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->configureImgProxy($container, $config);
    }

    private function configureImgProxy(ContainerBuilder $container, array $config)
    {
        $config = $config['imgproxy'] ?? [];

        if (empty($config)) {
            return;
        }

        $dSecurity = $container->getDefinition(Security::class);
        $dSecurity->setArguments([
            $config['key'] ?? '',
            $config['salt'] ?? ''
        ]);

        $dUrlBuilder = $container->getDefinition(Builder::class);
        $dUrlBuilder->setArguments([
            new Reference(Security::class),
            $config['host'] ?? 'localhost',
        ]);
    }

}
