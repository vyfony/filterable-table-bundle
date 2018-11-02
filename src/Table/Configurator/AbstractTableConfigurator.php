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

use Countable;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\Routing\RouterInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\FilterConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Checkbox\CheckboxHandlerInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\Column\ColumnMetadataInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\TableMetadata;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\TableMetadataInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Paginator\Page\Page;
use Vyfony\Bundle\FilterableTableBundle\Table\Paginator\Page\PageInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Paginator\Paginator;
use Vyfony\Bundle\FilterableTableBundle\Table\Paginator\PaginatorInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
abstract class AbstractTableConfigurator implements TableConfiguratorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var FilterConfiguratorInterface
     */
    private $filterConfigurator;

    /**
     * @var string
     */
    private $defaultSortBy;

    /**
     * @var string
     */
    private $defaultSortOrder;

    /**
     * @var string
     */
    private $listRoute;

    /**
     * @var string
     */
    private $showRoute;

    /**
     * @var array
     */
    private $showRouteParameters;

    /**
     * @var int
     */
    private $pageSize;

    /**
     * @var int
     */
    private $paginatorTailLength;

    /**
     * @param RouterInterface             $router
     * @param FilterConfiguratorInterface $filterConfigurator
     * @param string                      $defaultSortBy
     * @param string                      $defaultSortOrder
     * @param string                      $listRoute
     * @param string                      $showRoute
     * @param array                       $showRouteParameters
     * @param int                         $pageSize
     * @param int                         $paginatorTailLength
     */
    final public function __construct(
        RouterInterface $router,
        FilterConfiguratorInterface $filterConfigurator,
        string $defaultSortBy,
        string $defaultSortOrder,
        string $listRoute,
        string $showRoute,
        array $showRouteParameters,
        int $pageSize,
        int $paginatorTailLength
    ) {
        $this->router = $router;
        $this->filterConfigurator = $filterConfigurator;
        $this->defaultSortBy = $defaultSortBy;
        $this->defaultSortOrder = $defaultSortOrder;
        $this->listRoute = $listRoute;
        $this->showRoute = $showRoute;
        $this->showRouteParameters = $showRouteParameters;
        $this->pageSize = $pageSize;
        $this->paginatorTailLength = $paginatorTailLength;
    }

    /**
     * @param DoctrinePaginator $doctrinePaginator
     * @param array             $queryParameters
     *
     * @return TableMetadataInterface
     */
    public function getTableMetadata(
        DoctrinePaginator $doctrinePaginator,
        array $queryParameters
    ): TableMetadataInterface {
        return (new TableMetadata())
            ->setCheckboxHandlers($this->createCheckboxHandlers())
            ->setColumnMetadataCollection($this->getColumnMetadataCollection($queryParameters))
            ->setRowDataCollection($doctrinePaginator)
            ->setPaginator($this->createPaginator($doctrinePaginator, $queryParameters))
            ->setListRoute($this->listRoute)
            ->setShowRoute($this->showRoute)
            ->setShowRouteParameters($this->showRouteParameters)
            ->setQueryParameters($queryParameters)
        ;
    }

    /**
     * @return array
     */
    public function getDefaultTableParameters(): array
    {
        return [
            'sortBy' => $this->defaultSortBy,
            'sortOrder' => $this->defaultSortOrder,
            'page' => 1,
        ];
    }

    /**
     * @return ColumnMetadataInterface[]
     */
    abstract protected function createColumnMetadataCollection(): array;

    /**
     * @return CheckboxHandlerInterface[]
     */
    abstract protected function createCheckboxHandlers(): array;

    /**
     * @param array $queryParameters
     *
     * @return ColumnMetadataInterface[]
     */
    private function getColumnMetadataCollection(array $queryParameters): array
    {
        $columnMetadataCollection = array_merge(
            $this->createColumnMetadataCollection(),
            $this->createFilterDependentColumnMetadataCollection($queryParameters)
        );

        $this->applySortParameters($columnMetadataCollection, $queryParameters);

        return $columnMetadataCollection;
    }

    /**
     * @param array $queryParameters
     *
     * @return ColumnMetadataInterface[]
     */
    private function createFilterDependentColumnMetadataCollection(array $queryParameters): array
    {
        $filterDependentColumnMetadataCollection = [];

        foreach ($this->filterConfigurator->getTableParameters() as $tableParameter) {
            $filterDependentColumnMetadataCollection[] = $tableParameter->getColumnMetadataCollection($queryParameters);
        }

        if (\count($filterDependentColumnMetadataCollection) > 0) {
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
     * @param Countable $rows
     * @param array     $queryParameters
     *
     * @return PaginatorInterface
     */
    private function createPaginator(Countable $rows, array $queryParameters): PaginatorInterface
    {
        $pagesCount = (int) ceil(\count($rows) / $this->pageSize);

        $pages = array_fill(1, $pagesCount, null);

        array_walk($pages, function (&$page, int $pageIndex, array $queryParameters): void {
            $page = $this->createPage($pageIndex, $queryParameters);
        }, $queryParameters);

        return new Paginator($this->paginatorTailLength, (int) $queryParameters['page'], $pages);
    }

    /**
     * @param int   $pageIndex
     * @param array $formData
     *
     * @return PageInterface
     */
    private function createPage(int $pageIndex, array $formData): PageInterface
    {
        $formData['page'] = $pageIndex;

        return new Page(
            $pageIndex,
            $this->router->generate($this->listRoute, $formData)
        );
    }
}
