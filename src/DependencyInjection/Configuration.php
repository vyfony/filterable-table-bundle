<?php

declare(strict_types=1);

/*
 * This file is part of VyfonyFilterableTableBundle project.
 *
 * (c) Anton Dyshkant <vyshkant@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vyfony\Bundle\FilterableTableBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root('vyfony_filterable_table')
                ->children()
                    ->scalarNode('table_configurator')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('filter_configurator')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('entity_class')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('default_sort_by')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('default_sort_order')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('list_route')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('show_route')->isRequired()->cannotBeEmpty()->end()
                    ->variableNode('show_route_parameters')->isRequired()->cannotBeEmpty()->end()
                    ->integerNode('page_size')->isRequired()->min(0)->end()
                    ->integerNode('paginator_tail_length')->isRequired()->min(0)->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
