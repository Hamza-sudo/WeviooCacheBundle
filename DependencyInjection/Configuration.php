<?php


namespace Wevioo\WeviooCacheBundle\DependencyInjection;


use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;


class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wevioo');

        $rootNode
            ->children()
            ->scalarNode('wevioo_cache')
            ->cannotBeEmpty()
            ->defaultValue('Wevioo\WeviooCacheBundle\Component\WeviooCache')
            ->end()
            ->end();
        return $treeBuilder;
    }

}