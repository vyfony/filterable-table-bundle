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

namespace Vyfony\Bundle\FilterableTableBundle\DependencyInjection\CompilerPass;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class ConfiguratorsPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $filterConfiguratorServiceId = $container->getParameter('vyfony_filterable_table.filter_configurator');
        $tableConfiguratorServiceId = $container->getParameter('vyfony_filterable_table.table_configurator');
        $entityClass = $container->getParameter('vyfony_filterable_table.entity_class');
        $defaultSortBy = $container->getParameter('vyfony_filterable_table.default_sort_by');
        $defaultSortOrder = $container->getParameter('vyfony_filterable_table.default_sort_order');
        $listRoute = $container->getParameter('vyfony_filterable_table.list_route');
        $showRoute = $container->getParameter('vyfony_filterable_table.show_route');
        $showRouteParameters = $container->getParameter('vyfony_filterable_table.show_route_parameters');
        $pageSize = $container->getParameter('vyfony_filterable_table.page_size');
        $paginatorTailLength = $container->getParameter('vyfony_filterable_table.paginator_tail_length');

        $tableConfiguratorServiceDefinition = $container->findDefinition($tableConfiguratorServiceId);
        $filterConfiguratorServiceDefinition = $container->findDefinition($filterConfiguratorServiceId);

        $container
            ->getDefinition('vyfony_filterable_table.table')
            ->setArgument('$tableConfigurator', $tableConfiguratorServiceDefinition)
            ->setArgument('$filterConfigurator', $filterConfiguratorServiceDefinition)
            ->setArgument('$entityClass', $entityClass)
        ;

        $container
            ->getDefinition('vyfony_filterable_table.data_collector')
            ->setArgument('$filterConfigurator', $filterConfiguratorServiceDefinition)
            ->setArgument('$pageSize', $pageSize)
        ;

        $container
            ->getDefinition('vyfony_filterable_table.form.type.filterable_table_type')
            ->setArgument('$filterConfigurator', $filterConfiguratorServiceDefinition)
            ->setArgument('$doctrine', $container->findDefinition(RegistryInterface::class))
            ->setArgument('$entityClass', $entityClass)
        ;

        $tableConfiguratorServiceDefinition
            ->setArgument('$router', $container->findDefinition(RouterInterface::class))
            ->setArgument('$filterConfigurator', $filterConfiguratorServiceDefinition)
            ->setArgument('$defaultSortBy', $defaultSortBy)
            ->setArgument('$defaultSortOrder', $defaultSortOrder)
            ->setArgument('$listRoute', $listRoute)
            ->setArgument('$showRoute', $showRoute)
            ->setArgument('$showRouteParameters', $showRouteParameters)
            ->setArgument('$pageSize', $pageSize)
            ->setArgument('$paginatorTailLength', $paginatorTailLength)
        ;
    }
}
