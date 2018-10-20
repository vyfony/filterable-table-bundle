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
     * @param RouterInterface             $router
     * @param FilterConfiguratorInterface $filterConfigurator
     */
    public function __construct(
        RouterInterface $router,
        FilterConfiguratorInterface $filterConfigurator
    ) {
        $this->router = $router;
        $this->filterConfigurator = $filterConfigurator;
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
            ->setColumnMetadataCollection($this->getColumnMetadataCollection($queryParameters))
            ->setRowDataCollection($doctrinePaginator)
            ->setPaginator($this->createPaginator($doctrinePaginator, $queryParameters))
            ->setListRoute($this->getListRoute())
            ->setShowRoute($this->getShowRoute())
            ->setShowRouteParameters($this->getShowRouteParameters())
            ->setQueryParameters($queryParameters)
        ;
    }

    /**
     * @return array
     */
    public function getDefaultTableParameters(): array
    {
        return [
            'sortBy' => $this->getDefaultSortBy(),
            'sortOrder' => $this->getDefaultSortOrder(),
            'limit' => $this->getDefaultLimit(),
            'offset' => $this->getDefaultOffset(),
        ];
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
     * @return int
     */
    abstract protected function getDefaultLimit(): int;

    /**
     * @return int
     */
    abstract protected function getDefaultOffset(): int;

    /**
     * @return int
     */
    abstract protected function getPaginatorTailLength(): int;

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
        $pageSize = $this->getDefaultLimit();

        $pagesCount = (int) ceil(\count($rows) / $pageSize);

        $currentPageIndex = $queryParameters['offset'] / $pageSize;

        $pages = array_fill(0, $pagesCount, null);

        array_walk($pages, function (&$page, int $pageIndex, array $queryParameters) use ($pageSize): void {
            $page = $this->createPage($pageIndex, $pageSize, $queryParameters);
        }, $queryParameters);

        return new Paginator($this->getPaginatorTailLength(), $currentPageIndex, $pages);
    }

    /**
     * @param int   $pageIndex
     * @param int   $pageSize
     * @param array $formData
     *
     * @return PageInterface
     */
    private function createPage(int $pageIndex, int $pageSize, array $formData): PageInterface
    {
        return new Page(
            $pageIndex,
            $this->router->generate(
                $this->getListRoute(),
                $this->createPageQueryParameters($pageIndex, $pageSize, $formData)
            )
        );
    }

    /**
     * @param int   $pageIndex
     * @param int   $pageSize
     * @param array $formData
     *
     * @return array
     */
    private function createPageQueryParameters(int $pageIndex, int $pageSize, array $formData): array
    {
        $queryParameters = $formData;

        $queryParameters['limit'] = $pageSize;
        $queryParameters['offset'] = $pageIndex * $pageSize;

        return $queryParameters;
    }
}
