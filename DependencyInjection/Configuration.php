<?php

namespace Enhavo\Bundle\RoutingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_routing');
        $rootNode
            ->children()
                ->arrayNode('classes')
                    ->useAttributeAsKey('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->variableNode('router')->end()
                        ->variableNode('auto_generators')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
