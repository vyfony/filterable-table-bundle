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

use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\FilterConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Configurator\AbstractTableConfigurator;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class ConfiguratorsPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        $filterConfiguratorServiceId = $container->getParameter('vyfony_filterable_table.filter_configurator');
        $tableConfiguratorServiceId = $container->getParameter('vyfony_filterable_table.table_configurator');
        $entityClass = $container->getParameter('vyfony_filterable_table.entity_class');

        $tableConfiguratorServiceDefinition = $container->findDefinition($tableConfiguratorServiceId);
        $filterConfiguratorServiceDefinition = $container->findDefinition($filterConfiguratorServiceId);

        $container
            ->getDefinition('vyfony_filterable_table.table')
            ->setArgument(3, $tableConfiguratorServiceDefinition)
            ->setArgument(4, $filterConfiguratorServiceDefinition)
            ->setArgument(5, $entityClass)
        ;

        $container
            ->getDefinition('vyfony_filterable_table.data_collector')
            ->setArgument(1, $filterConfiguratorServiceDefinition)
        ;

        $container
            ->getDefinition('vyfony_filterable_table.form.filterable_table_type')
            ->setArgument(0, $filterConfiguratorServiceDefinition)
        ;

        $tableConfiguratorClassReflection = new ReflectionClass($tableConfiguratorServiceDefinition->getClass());

        if ($tableConfiguratorClassReflection->isSubclassOf(AbstractTableConfigurator::class)) {
            $constructorParameters = $tableConfiguratorClassReflection->getConstructor()->getParameters();

            foreach ($constructorParameters as $index => $constructorParameter) {
                $parameterClass = $constructorParameter->getClass();

                if (null !== $parameterClass && FilterConfiguratorInterface::class === $parameterClass->getName()) {
                    $tableConfiguratorServiceDefinition->setArgument($index, $filterConfiguratorServiceDefinition);
                }
            }
        }
    }
}
