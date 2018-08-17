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

namespace Vyfony\Bundle\FilterableTableBundle\Table\Configurator;

use Vyfony\Bundle\FilterableTableBundle\DataCollector\DataCollectorInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\FilterConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\Column\ColumnMetadataInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\TableMetadata;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\TableMetadataInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
abstract class AbstractTableConfigurator implements TableConfiguratorInterface
{
    /**
     * @var DataCollectorInterface
     */
    private $dataCollector;

    /**
     * @var FilterConfiguratorInterface
     */
    private $filterConfigurator;

    /**
     * @param DataCollectorInterface      $dataCollector
     * @param FilterConfiguratorInterface $filterConfigurator
     */
    public function __construct(DataCollectorInterface $dataCollector, FilterConfiguratorInterface $filterConfigurator)
    {
        $this->dataCollector = $dataCollector;
        $this->filterConfigurator = $filterConfigurator;
    }

    /**
     * @param array  $formData
     * @param array  $queryParameters
     * @param string $entityClass
     *
     * @return TableMetadataInterface
     */
    public function getTableMetadata(
        array $formData,
        array $queryParameters,
        string $entityClass
    ): TableMetadataInterface {
        return (new TableMetadata())
            ->setColumnMetadataCollection($this->getColumnMetadataCollection($queryParameters))
            ->setRowDataCollection($this->dataCollector->getRowsData($formData, $entityClass))
            ->setListRoute($this->getListRoute())
            ->setShowRoute($this->getShowRoute())
            ->setShowRouteParameters($this->getShowRouteParameters())
            ->setQueryParameters($queryParameters);
    }

    /**
     * @return array
     */
    public function getDefaultSortParameters(): array
    {
        return $this->fillSortParametersTemplate($this->getDefaultSortBy(), $this->getDefaultSortOrder());
    }

    /**
     * @return ColumnMetadataInterface[]
     */
    abstract protected function factoryColumnMetadataCollection(): array;

    /**
     * @return string
     */
    abstract protected function getListRoute(): string;

    /**
     * @return string
     */
    abstract protected function getShowRoute(): string;

    /**
     * @return string
     */
    abstract protected function getDefaultSortBy(): string;

    /**
     * @return string
     */
    abstract protected function getDefaultSortOrder(): string;

    /**
     * @return string[]
     */
    abstract protected function getShowRouteParameters(): array;

    /**
     * @param array $queryParameters
     *
     * @return ColumnMetadataInterface[]
     */
    private function getColumnMetadataCollection(array $queryParameters): array
    {
        $columnMetadataCollection = array_merge(
            $this->factoryColumnMetadataCollection(),
            $this->getFilterDependentColumnMetadataCollection($queryParameters)
        );

        $this->applySortParameters($columnMetadataCollection, $queryParameters);

        return $columnMetadataCollection;
    }

    /**
     * @param array $queryParameters
     *
     * @return ColumnMetadataInterface[]
     */
    private function getFilterDependentColumnMetadataCollection(array $queryParameters): array
    {
        $filterDependentColumnMetadataCollection = [];

        foreach ($this->filterConfigurator->getTableParameters() as $tableParameter) {
            $filterDependentColumnMetadataCollection[] = $tableParameter->getColumnMetadataCollection($queryParameters);
        }

        if (count($filterDependentColumnMetadataCollection) > 0) {
            return array_merge(...$filterDependentColumnMetadataCollection);
        }

        return [];
    }

    /**
     * @param ColumnMetadataInterface[] $columnMetadataCollection
     * @param array                     $queryParameters
     */
    private function applySortParameters(array $columnMetadataCollection, array $queryParameters): void
    {
        foreach ($columnMetadataCollection as $columnMetadata) {
            if ($columnMetadata->getIsSortable()) {
                $columnMetadata->setSortParameters(
                    $this->createSortParametersForColumn($columnMetadata->getName(), $queryParameters)
                );
            }
        }
    }

    /**
     * @param string $columnName
     * @param array  $formData
     *
     * @return array
     */
    private function createSortParametersForColumn(string $columnName, array $formData): array
    {
        $sortBy = $columnName;
        $sortOrder = 'desc' === $formData['sortOrder'] || $columnName !== $formData['sortBy'] ? 'asc' : 'desc';

        $formData['sortBy'] = $sortBy;
        $formData['sortOrder'] = $sortOrder;

        return $formData;
    }

    /**
     * @param string $sortBy
     * @param string $sortOrder
     *
     * @return string[]
     */
    private function fillSortParametersTemplate(string $sortBy, string $sortOrder): array
    {
        return [
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ];
    }
}
