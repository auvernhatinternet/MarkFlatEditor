<?php

namespace MarkFlat\MarkFlatEditor\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('mark_flat_editor');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('admin_password')
                    ->defaultValue('%env(default:admin:MARKFLAT_EDITOR_ADMIN_PASSWORD)%')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
