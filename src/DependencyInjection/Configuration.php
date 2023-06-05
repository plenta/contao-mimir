<?php

declare(strict_types=1);

/**
 * Plenta Mimir Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2023, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

namespace Plenta\Mimir\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('plenta_mimir');
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('webhook')
                    ->cannotBeEmpty()
                    ->defaultValue('')
                ->end()
                ->arrayNode('exceptions')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('whitelist')
                            ->arrayPrototype()
                            ->end()
                        ->end()
                        ->arrayNode('blacklist')
                            ->arrayPrototype()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('message')
                    ->cannotBeEmpty()
                    ->defaultValue('Exception in ##exception_url##: ##exception_message## (##exception_file## in line ##exception_line##) [##exception_class##]')
                ->end()
                ->booleanNode('debug')
                    ->defaultFalse()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
