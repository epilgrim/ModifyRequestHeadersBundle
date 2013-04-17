<?php

namespace Epilgrim\ModifyRequestHeadersBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        //$rootNode = $treeBuilder->root('epilgrim_reverse_proxy')
        $rootNode = $treeBuilder->root('epilgrim_modify_request_headers')
            ->children()
                ->scalarNode('listener_priority')
                    ->defaultValue('64')
                    ->info('must be higher than the priority of the modules making use of the new headers')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('headers')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                    ->children()
                        ->scalarNode('name')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('value')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
