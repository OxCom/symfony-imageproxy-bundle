<?php

namespace SymfonyImageProxyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const CONFIG_NAME = 'symfony_image_proxy';

    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder(self::CONFIG_NAME);

        $builder->getRootNode()
            ->children()
                ->arrayNode('imgproxy')
                    ->children()
                        ->scalarNode('host')->defaultValue('localhost')->end()
                        ->scalarNode('key')->defaultValue('')->end()
                        ->scalarNode('salt')->defaultValue('')->end()
                    ->end()
                ->end()
                ->arrayNode('thumbor')
                    ->children()
                        ->scalarNode('host')->defaultValue('localhost')->end()
                        ->scalarNode('key')->defaultValue('')->end()
                        ->scalarNode('salt')->defaultValue('')->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
