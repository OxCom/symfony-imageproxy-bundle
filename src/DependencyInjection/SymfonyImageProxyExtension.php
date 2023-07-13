<?php

namespace SymfonyImageProxyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Reference;
use SymfonyImageProxyBundle\Providers\ImgProxy\Builder as ImgProxyBuilder;
use SymfonyImageProxyBundle\Providers\ImgProxy\Security as ImgProxySecurity;
use SymfonyImageProxyBundle\Providers\Thumbor\Builder as ThumborBuilder;
use SymfonyImageProxyBundle\Providers\Thumbor\Security as ThumborSecurity;

class SymfonyImageProxyExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->configureImgProxy($container, $config);
        $this->configureThumbor($container, $config);
    }

    private function configureImgProxy(ContainerBuilder $container, array $config)
    {
        $config = $config['imgproxy'] ?? [];

        if (empty($config)) {
            return;
        }

        $dSecurity = $container->getDefinition(ImgProxySecurity::class);
        $dSecurity->setArguments([
            $config['key'] ?? '',
            $config['salt'] ?? ''
        ]);

        $dUrlBuilder = $container->getDefinition(ImgProxyBuilder::class);
        $dUrlBuilder->setArguments([
            new Reference(ImgProxySecurity::class),
            $config['host'] ?? 'localhost',
        ]);
    }

    private function configureThumbor(ContainerBuilder $container, array $config)
    {
        $config = $config['thumbor'] ?? [];

        if (empty($config)) {
            return;
        }

        $dSecurity = $container->getDefinition(ThumborSecurity::class);
        $dSecurity->setArguments([
            $config['key'] ?? '',
            $config['salt'] ?? ''
        ]);

        $dUrlBuilder = $container->getDefinition(ThumborBuilder::class);
        $dUrlBuilder->setArguments([
            new Reference(ThumborSecurity::class),
            $config['host'] ?? 'localhost',
        ]);
    }
}
